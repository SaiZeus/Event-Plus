<?php

namespace App\Exports;

use App\Exports\Concerns\BuildsAttendeeReport;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class EventAttendeesSheet implements
    FromCollection,
    WithTitle,
    WithEvents
{
    use BuildsAttendeeReport;

    protected $event;
    protected $categoryId;
    protected $sheetTitle;

    public function __construct(Event $event, $categoryId = null, $sheetTitle = 'All')
    {
        $this->event = $event;
        $this->categoryId = $categoryId;
        $this->sheetTitle = $sheetTitle;
    }

    // The sheet is drawn in AfterSheet, so there is nothing to export here.
    public function collection()
    {
        return collect([]);
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // One query for every paid runner. The table may show only one category.
                $allPaid = $this->paidAttendees($this->event);

                $attendees = is_null($this->categoryId)
                    ? $allPaid
                    : $allPaid->where('ticket_category_id', $this->categoryId)->values();

                // Same revenue rule as the admin attendees page: paid order totals.
                $totalRevenue = Order::where(function ($q) {
                        $q->where('event_id', $this->event->id)
                          ->orWhereHas('attendees.ticketCategory', function ($sub) {
                              $sub->where('event_id', $this->event->id);
                          });
                    })
                    ->whereIn(DB::raw('LOWER(payment_status)'), $this->paidStatuses())
                    ->sum('total_amount');

                $groupStats = $this->groupBuyStats($allPaid);

                $this->writeReport($sheet, $this->event, $attendees, [
                    'title'    => strtoupper($this->event->title) . '  |  PARTICIPANTS & TICKET SALES REPORT',
                    'subtitle' => $this->reportSubtitle($this->event),
                    'tab'      => 'FF4F46E5',
                    'cards'    => [
                        [
                            'label'  => 'Total revenue',
                            'value'  => (float) $totalRevenue,
                            'format' => '#,##0" MMK"',
                            'from'   => 'A', 'to' => 'B',
                            'tone'   => 'green',
                        ],
                        [
                            'label'  => 'Total participants',
                            'value'  => $allPaid->count(),
                            'format' => '#,##0',
                            'from'   => 'C', 'to' => 'E',
                            'tone'   => 'indigo',
                        ],
                        [
                            'label'  => 'Group buy runners (' . $groupStats['orders'] . ' orders)',
                            'value'  => $groupStats['runners'],
                            'format' => '#,##0',
                            'from'   => 'F', 'to' => 'G',
                            'tone'   => 'amber',
                        ],
                        [
                            'label'  => 'Group buy discounts given',
                            'value'  => $groupStats['discount'],
                            'format' => '#,##0" MMK"',
                            'from'   => 'H', 'to' => 'I',
                            'tone'   => 'amber',
                        ],
                    ],
                    'breakdown' => $this->buildBreakdown($this->event, $allPaid),
                ]);
            },
        ];
    }
}