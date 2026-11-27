<?php

namespace App\Exports\Concerns;

use App\Models\Attendee;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Shared by EventAttendeesSheet and EventCategoryAttendeesSheet so both
 * sheets have the same columns, the same numbers and the same look.
 */
trait BuildsAttendeeReport
{
    /** Cache of order id => [attendee id => net ticket price], used to share a group discount. */
    protected array $orderNetCache = [];

    /* =========================================================
       LOOK
    ========================================================= */

    protected function palette(): array
    {
        return [
            'ink'         => 'FF1F2937',
            'muted'       => 'FF6B7280',
            'line'        => 'FFE5E7EB',
            'zebra'       => 'FFF9FAFB',
            'white'       => 'FFFFFFFF',
            'indigo_dark' => 'FF312E81',
            'indigo'      => 'FF4F46E5',
            'indigo_mid'  => 'FF6366F1',
            'indigo_soft' => 'FFEEF2FF',
            'indigo_text' => 'FF3730A3',
            'green_soft'  => 'FFDCFCE7',
            'green_text'  => 'FF166534',
            'amber_soft'  => 'FFFEF3C7',
            'amber_text'  => 'FF92400E',
            'gray_soft'   => 'FFF3F4F6',
            'red_text'    => 'FFB42318',
            'subtitle'    => 'FFC7D2FE',
        ];
    }

    protected function solid(string $argb): array
    {
        return ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $argb]];
    }

    /* =========================================================
       COLUMNS  (letter => [heading, width])
    ========================================================= */

    protected function reportColumns(): array
    {
        return [
            'A' => ['Reg Code', 16],
            'B' => ['Runner Name', 26],
            'C' => ['Father Name', 24],
            'D' => ['BIB', 16],
            'E' => ['Category', 22],
            'F' => ['Promo Applied', 26],
            'G' => ['Group Buy', 30],
            'H' => ['Order No', 18],
            'I' => ['Contact', 44],
            'J' => ['Emergency (ICE)', 20],
            'K' => ['NRC / Passport', 22],
            'L' => ['Country', 16],
            'M' => ['Demographics', 30],
            'N' => ['Health & ITRA', 40],
            'O' => ['Address & Experience', 52],
        ];
    }

    /* =========================================================
       DATA
    ========================================================= */

    protected function paidStatuses(): array
    {
        return ['paid', 'approved', 'completed'];
    }

    /** Paid attendees of an event (optionally one category), sorted by ticket number. */
    protected function paidAttendees(Event $event, $categoryId = null): Collection
    {
        $query = Attendee::with(['ticketCategory', 'promoCode', 'order'])
            ->whereHas('ticketCategory', function ($q) use ($event) {
                $q->where('event_id', $event->id);
            })
            ->whereHas('order', function ($q) {
                $q->whereIn(DB::raw('LOWER(payment_status)'), $this->paidStatuses());
            });

        if (! is_null($categoryId)) {
            $query->where('ticket_category_id', $categoryId);
        }

        return $query->get()->sort(function ($a, $b) {
            preg_match('/(\d+)/', (string) ($a->ticket_code ?? ''), $matchA);
            preg_match('/(\d+)/', (string) ($b->ticket_code ?? ''), $matchB);

            $numA = isset($matchA[1]) ? (int) $matchA[1] : PHP_INT_MAX;
            $numB = isset($matchB[1]) ? (int) $matchB[1] : PHP_INT_MAX;

            if ($numA === $numB) {
                return ((int) $a->id) <=> ((int) $b->id);
            }

            return $numA <=> $numB;
        })->values();
    }

    /* =========================================================
       MONEY  (same rules as CheckoutController)
    ========================================================= */

    protected function ticketPrice(Attendee $attendee): float
    {
        $category = $attendee->ticketCategory;

        if (! $category) {
            return 0.0;
        }

        $isForeigner = strtolower((string) $attendee->nationality) === 'foreigner';

        return ($isForeigner && $category->foreign_price)
            ? (float) $category->foreign_price
            : (float) $category->local_price;
    }

    protected function promoDiscount(Attendee $attendee, float $price): float
    {
        $promo = $attendee->promoCode;

        if (! $promo) {
            return 0.0;
        }

        $eventMatch = (int) $promo->event_id === (int) optional($attendee->ticketCategory)->event_id;
        $categoryMatch = is_null($promo->ticket_category_id)
            || (int) $promo->ticket_category_id === (int) $attendee->ticket_category_id;

        if (! $eventMatch || ! $categoryMatch) {
            return 0.0;
        }

        $discount = $promo->discount_type === 'percentage'
            ? $price * ((float) $promo->discount_value / 100)
            : (float) $promo->discount_value;

        return min($price, max(0.0, $discount));
    }

    /** This runner's share of the order's group buy discount, split by ticket price. */
    protected function groupDiscountShare(Attendee $attendee): float
    {
        $order = $attendee->order;

        if (! $order || ! $order->isGroupBuy() || (float) $order->group_buy_discount <= 0) {
            return 0.0;
        }

        if (! isset($this->orderNetCache[$order->id])) {
            $order->loadMissing('attendees.ticketCategory', 'attendees.promoCode');

            $nets = [];

            foreach ($order->attendees as $member) {
                $price = $this->ticketPrice($member);
                $nets[$member->id] = max(0.0, $price - $this->promoDiscount($member, $price));
            }

            $this->orderNetCache[$order->id] = $nets;
        }

        $nets = $this->orderNetCache[$order->id];
        $sum = array_sum($nets);

        if ($sum <= 0) {
            return 0.0;
        }

        return (float) $order->group_buy_discount * (($nets[$attendee->id] ?? 0) / $sum);
    }

    protected function financials(Attendee $attendee): array
    {
        $gross = $this->ticketPrice($attendee);
        $promo = $this->promoDiscount($attendee, $gross);
        $group = $this->groupDiscountShare($attendee);

        return [
            'gross' => $gross,
            'promo' => $promo,
            'group' => $group,
            'net'   => max(0.0, $gross - $promo - $group),
        ];
    }

    protected function totals(Collection $attendees): array
    {
        $totals = ['tickets' => $attendees->count(), 'gross' => 0.0, 'promo' => 0.0, 'group' => 0.0, 'net' => 0.0];

        foreach ($attendees as $attendee) {
            $f = $this->financials($attendee);

            $totals['gross'] += $f['gross'];
            $totals['promo'] += $f['promo'];
            $totals['group'] += $f['group'];
            $totals['net']   += $f['net'];
        }

        return $totals;
    }

    protected function groupBuyStats(Collection $attendees): array
    {
        $groupAttendees = $attendees->filter(fn ($a) => $a->order && $a->order->isGroupBuy());
        $orders = $groupAttendees->pluck('order')->unique('id');

        return [
            'runners'  => $groupAttendees->count(),
            'orders'   => $orders->count(),
            'discount' => (float) $orders->sum('group_buy_discount'),
        ];
    }

    protected function buildBreakdown(Event $event, Collection $allPaid): array
    {
        $byCategory = $allPaid->groupBy('ticket_category_id');

        return $event->ticketCategories->map(function ($category) use ($byCategory) {
            $members = $byCategory->get($category->id, collect());
            $t = $this->totals($members);

            return [
                'name'    => $category->name,
                'tickets' => $t['tickets'],
                'gross'   => $t['gross'],
                'promo'   => $t['promo'],
                'group'   => $t['group'],
                'net'     => $t['net'],
            ];
        })->values()->all();
    }

    protected function money($amount): string
    {
        return rtrim(rtrim(number_format((float) $amount, 2), '0'), '.');
    }

    /** One row of text values, in the same order as reportColumns(). */
    protected function attendeeRow(Attendee $attendee): array
    {
        $promoText = '—';
        if ($attendee->promoCode) {
            $promo = $attendee->promoCode;
            $promoValue = $promo->discount_type === 'percentage'
                ? $promo->discount_value . '%'
                : number_format($promo->discount_value) . ' MMK';
            $promoText = $promo->code . ' (' . $promoValue . ')';
        }

        $order = $attendee->order;
        $groupText = '—';
        if ($order && $order->isGroupBuy()) {
            $label = $order->group_buy_name ?: $order->group_buy_quantity . '-ticket group';
            $groupText = $label . ' (' . $order->group_buy_quantity . ' tickets)'
                . "\n" . 'Order discount: ' . $this->money($order->group_buy_discount) . ' MMK';
        }

        $health = 'Blood: ' . ($attendee->blood_type ?? 'N/A');
        if (strtolower((string) $attendee->has_medical_condition) === 'yes') {
            $health .= "\n" . 'Medical: ' . ($attendee->medical_details ?: 'Yes');
        }
        if (strtolower((string) $attendee->itra) === 'yes') {
            $health .= "\n" . 'ITRA: ' . ($attendee->itra_details ?: 'Yes');
        }

        $dob = 'N/A';
        if ($attendee->date_of_birth) {
            try {
                $dob = Carbon::parse($attendee->date_of_birth)->format('Y-m-d');
            } catch (\Exception $e) {
                $dob = (string) $attendee->date_of_birth;
            }
        }

        $demographics = 'Gender: ' . ucfirst($attendee->gender ?? 'N/A')
            . "\n" . 'DOB: ' . $dob
            . "\n" . 'Nationality: ' . ($attendee->nationality ?? 'N/A')
            . "\n" . 'T-shirt: ' . ($attendee->tshirt_size ?? 'N/A');

        $contact = 'Email: ' . ($attendee->email ?? '')
            . "\n" . 'Phone: ' . ($attendee->phone ?? '')
            . ($attendee->viber ? "\n" . 'Viber: ' . $attendee->viber : '');

        $addressExp = 'Address: ' . ($attendee->address ?? 'N/A')
            . "\n" . 'Experience: ' . ($attendee->experience ?? 'N/A');

        return [
            (string) ($attendee->ticket_code ?? ''),
            (string) ($attendee->full_name ?? ''),
            (string) ($attendee->father_name ?? ''),
            (string) ($attendee->bib_number ?? $attendee->bib_name ?? ''),
            (string) (optional($attendee->ticketCategory)->name ?? ''),
            $promoText,
            $groupText,
            (string) ($order->order_number ?? ''),
            $contact,
            (string) ($attendee->emergency_contact ?? ''),
            (string) ($attendee->nrc_passport ?? ''),
            (string) ($attendee->country ?? ''),
            $demographics,
            $health,
            $addressExp,
        ];
    }

    /* =========================================================
       WRITING THE SHEET
    ========================================================= */

    /**
     * $report = [
     *   'title'     => string,
     *   'subtitle'  => string,
     *   'tab'       => argb colour of the sheet tab,
     *   'cards'     => [ ['label','value','from','to','tone','format'?], ... ],
     *   'breakdown' => (optional) rows from buildBreakdown(),
     * ]
     */
    protected function writeReport(Worksheet $sheet, Event $event, Collection $attendees, array $report): void
    {
        $c = $this->palette();
        $columns = $this->reportColumns();
        $last = array_key_last($columns);

        $sheet->setShowGridlines(false);
        $sheet->getTabColor()->setARGB($report['tab'] ?? $c['indigo']);
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Calibri')->setSize(10);

        foreach ($columns as $letter => [$heading, $width]) {
            $sheet->getColumnDimension($letter)->setWidth($width);
        }

        /* ---------- Title band ---------- */
        $sheet->mergeCells("A1:{$last}1");
        $sheet->mergeCells("A2:{$last}2");
        $sheet->setCellValue('A1', $report['title']);
        $sheet->setCellValue('A2', $report['subtitle']);

        $sheet->getStyle("A1:{$last}2")->applyFromArray(['fill' => $this->solid($c['indigo_dark'])]);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 17, 'color' => ['argb' => $c['white']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
        ]);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['size' => 10, 'color' => ['argb' => $c['subtitle']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(40);
        $sheet->getRowDimension(2)->setRowHeight(22);
        $sheet->getRowDimension(3)->setRowHeight(10);

        /* ---------- Summary cards (rows 4-5) ---------- */
        $tones = [
            'indigo' => [$c['indigo_soft'], $c['indigo_text']],
            'green'  => [$c['green_soft'], $c['green_text']],
            'amber'  => [$c['amber_soft'], $c['amber_text']],
            'gray'   => [$c['gray_soft'], $c['ink']],
        ];

        foreach ($report['cards'] as $card) {
            [$fill, $text] = $tones[$card['tone'] ?? 'indigo'];
            $from = $card['from'];
            $to = $card['to'];

            $sheet->mergeCells("{$from}4:{$to}4");
            $sheet->mergeCells("{$from}5:{$to}5");
            $sheet->setCellValue("{$from}4", strtoupper($card['label']));
            $sheet->setCellValue("{$from}5", $card['value']);

            $sheet->getStyle("{$from}4:{$to}5")->applyFromArray([
                'fill' => $this->solid($fill),
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['argb' => $c['white']]]],
            ]);
            $sheet->getStyle("{$from}4")->applyFromArray([
                'font' => ['bold' => true, 'size' => 8, 'color' => ['argb' => $c['muted']]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_BOTTOM, 'indent' => 1],
            ]);
            $sheet->getStyle("{$from}5")->applyFromArray([
                'font' => ['bold' => true, 'size' => 17, 'color' => ['argb' => $text]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
            ]);

            if (! empty($card['format'])) {
                $sheet->getStyle("{$from}5")->getNumberFormat()->setFormatCode($card['format']);
            }
        }

        $sheet->getRowDimension(4)->setRowHeight(20);
        $sheet->getRowDimension(5)->setRowHeight(34);
        $sheet->getRowDimension(6)->setRowHeight(12);

        $row = 7;

        /* ---------- Category breakdown ---------- */
        if (! empty($report['breakdown'])) {
            $sheet->setCellValue("A{$row}", 'TICKET CATEGORY BREAKDOWN');
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => $c['indigo_dark']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(24);
            $row++;

            $sheet->mergeCells("A{$row}:B{$row}");
            $heads = [
                'A' => 'Category',
                'C' => 'Tickets Sold',
                'D' => 'Gross Sales (MMK)',
                'E' => 'Promo Discounts',
                'F' => 'Group Buy Discounts',
                'G' => 'Net Revenue (MMK)',
            ];
            foreach ($heads as $letter => $text) {
                $sheet->setCellValue("{$letter}{$row}", $text);
            }
            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 9, 'color' => ['argb' => $c['white']]],
                'fill' => $this->solid($c['indigo_mid']),
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => $c['indigo']]]],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(30);
            $row++;

            $first = $row;
            $sum = ['tickets' => 0, 'gross' => 0.0, 'promo' => 0.0, 'group' => 0.0, 'net' => 0.0];

            foreach ($report['breakdown'] as $i => $item) {
                $sheet->mergeCells("A{$row}:B{$row}");
                $sheet->setCellValue("A{$row}", $item['name']);
                $sheet->setCellValue("C{$row}", $item['tickets']);
                $sheet->setCellValue("D{$row}", $item['gross']);
                $sheet->setCellValue("E{$row}", $item['promo']);
                $sheet->setCellValue("F{$row}", $item['group']);
                $sheet->setCellValue("G{$row}", $item['net']);

                if ($i % 2 === 1) {
                    $sheet->getStyle("A{$row}:G{$row}")->applyFromArray(['fill' => $this->solid($c['zebra'])]);
                }

                foreach (['tickets', 'gross', 'promo', 'group', 'net'] as $key) {
                    $sum[$key] += $item[$key];
                }

                $sheet->getRowDimension($row)->setRowHeight(22);
                $row++;
            }

            // Totals
            $sheet->mergeCells("A{$row}:B{$row}");
            $sheet->setCellValue("A{$row}", 'TOTAL');
            $sheet->setCellValue("C{$row}", $sum['tickets']);
            $sheet->setCellValue("D{$row}", $sum['gross']);
            $sheet->setCellValue("E{$row}", $sum['promo']);
            $sheet->setCellValue("F{$row}", $sum['group']);
            $sheet->setCellValue("G{$row}", $sum['net']);

            $totalRow = $row;

            $sheet->getStyle("A{$first}:G{$totalRow}")->applyFromArray([
                'font' => ['size' => 10, 'color' => ['argb' => $c['ink']]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => $c['line']]]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getStyle("A{$first}:A{$totalRow}")->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
            ]);
            $sheet->getStyle("C{$first}:C{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$first}:G{$totalRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("D{$first}:G{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setIndent(1);
            $sheet->getStyle("F{$first}:F{$totalRow}")->getFont()->getColor()->setARGB($c['amber_text']);
            $sheet->getStyle("G{$first}:G{$totalRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => $c['green_text']]],
            ]);
            $sheet->getStyle("A{$totalRow}:G{$totalRow}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => $this->solid($c['indigo_soft']),
                'borders' => ['top' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => $c['indigo']]]],
            ]);
            $sheet->getRowDimension($totalRow)->setRowHeight(26);

            $row = $totalRow + 2;
        }

        /* ---------- Participants table ---------- */
        $sheet->setCellValue("A{$row}", 'PARTICIPANTS');
        $sheet->getStyle("A{$row}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => $c['indigo_dark']]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension($row)->setRowHeight(24);
        $row++;

        $headerRow = $row;

        foreach ($columns as $letter => [$heading, $width]) {
            $sheet->setCellValue("{$letter}{$headerRow}", $heading);
        }

        $sheet->getStyle("A{$headerRow}:{$last}{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['argb' => $c['white']]],
            'fill' => $this->solid($c['indigo']),
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => $c['indigo_text']]]],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(34);

        $dataRow = $headerRow + 1;
        $firstData = $dataRow;
        $groupRows = [];
        $medicalRows = [];

        foreach ($attendees as $index => $attendee) {
            $col = 'A';

            foreach ($this->attendeeRow($attendee) as $value) {
                $sheet->setCellValueExplicit($col . $dataRow, $value, DataType::TYPE_STRING);
                $col++;
            }

            if ($attendee->order && $attendee->order->isGroupBuy()) {
                $groupRows[] = $dataRow;
            }

            if (strtolower((string) $attendee->has_medical_condition) === 'yes') {
                $medicalRows[] = $dataRow;
            }

            if ($index % 2 === 1) {
                $sheet->getStyle("A{$dataRow}:{$last}{$dataRow}")->applyFromArray(['fill' => $this->solid($c['zebra'])]);
            }

            $sheet->getRowDimension($dataRow)->setRowHeight(66);
            $dataRow++;
        }

        $lastData = $dataRow - 1;

        if ($lastData >= $firstData) {
            $sheet->getStyle("A{$firstData}:{$last}{$lastData}")->applyFromArray([
                'font' => ['size' => 9, 'color' => ['argb' => $c['ink']]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => $c['line']]]],
                'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
            ]);

            // Reg code and name stand out
            $sheet->getStyle("A{$firstData}:A{$lastData}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 10, 'color' => ['argb' => $c['indigo_text']]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getStyle("B{$firstData}:B{$lastData}")->getFont()->setBold(true)->setSize(10);

            // Short values read better centred
            foreach (['D', 'H', 'L'] as $letter) {
                $sheet->getStyle("{$letter}{$firstData}:{$letter}{$lastData}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            // Group buy cells: amber when used, quiet dash when not
            $sheet->getStyle("G{$firstData}:G{$lastData}")->applyFromArray([
                'font' => ['color' => ['argb' => 'FF9CA3AF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getStyle("F{$firstData}:F{$lastData}")->getFont()->getColor()->setARGB($c['indigo_text']);

            foreach ($groupRows as $r) {
                $sheet->getStyle("G{$r}")->applyFromArray([
                    'fill' => $this->solid($c['amber_soft']),
                    'font' => ['bold' => true, 'color' => ['argb' => $c['amber_text']]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
                ]);
            }

            // Medical condition in red so it isn't missed
            foreach ($medicalRows as $r) {
                $sheet->getStyle("N{$r}")->getFont()->getColor()->setARGB($c['red_text']);
            }

            $sheet->setAutoFilter("A{$headerRow}:{$last}{$lastData}");
        }

        /* ---------- View and print ---------- */
        $sheet->freezePane('A' . ($headerRow + 1));
        $sheet->getSheetView()->setZoomScale(90);

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd($headerRow, $headerRow);
        $sheet->getPageMargins()->setLeft(0.3)->setRight(0.3)->setTop(0.4)->setBottom(0.5);
        $sheet->getHeaderFooter()->setOddFooter(
            '&L&8' . str_replace('&', '&&', $event->title) . '&R&8Page &P of &N'
        );
    }

    protected function reportSubtitle(Event $event): string
    {
        $parts = [];

        if ($event->event_date) {
            try {
                $parts[] = Carbon::parse($event->event_date)->format('D, d M Y');
            } catch (\Exception $e) {
                // skip an unreadable date
            }
        }

        if (filled($event->location)) {
            $parts[] = $event->location;
        }

        $parts[] = 'Status: ' . ucfirst((string) $event->status);
        $parts[] = 'Generated ' . now()->format('d M Y, H:i');

        return implode('   •   ', $parts);
    }
}