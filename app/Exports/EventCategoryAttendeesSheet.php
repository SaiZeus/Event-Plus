<?php

namespace App\Exports;

use App\Exports\Concerns\BuildsAttendeeReport;
use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class EventCategoryAttendeesSheet implements FromCollection, WithTitle, WithEvents
{
    use BuildsAttendeeReport;

    protected $event;
    protected $categoryId;
    protected $sheetTitle;

    public function __construct(Event $event, $categoryId, $sheetTitle)
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

                $attendees = $this->paidAttendees($this->event, $this->categoryId);

                $totals = $this->totals($attendees);
                $groupStats = $this->groupBuyStats($attendees);

                $this->writeReport($sheet, $this->event, $attendees, [
                    'title'    => strtoupper($this->event->title) . '  |  ' . strtoupper($this->sheetTitle) . ' PARTICIPANTS REPORT',
                    'subtitle' => $this->reportSubtitle($this->event),
                    'tab'      => 'FF6366F1',
                    'cards'    => [
                        [
                            'label'  => 'Tickets sold',
                            'value'  => $totals['tickets'],
                            'format' => '#,##0',
                            'from'   => 'A', 'to' => 'B',
                            'tone'   => 'indigo',
                        ],
                        [
                            'label'  => 'Net revenue',
                            'value'  => $totals['net'],
                            'format' => '#,##0" MMK"',
                            'from'   => 'C', 'to' => 'E',
                            'tone'   => 'green',
                        ],
                        [
                            'label'  => 'Group buy runners',
                            'value'  => $groupStats['runners'],
                            'format' => '#,##0',
                            'from'   => 'F', 'to' => 'G',
                            'tone'   => 'amber',
                        ],
                        [
                            'label'  => 'Discounts given (promo + group buy)',
                            'value'  => $totals['promo'] + $totals['group'],
                            'format' => '#,##0" MMK"',
                            'from'   => 'H', 'to' => 'I',
                            'tone'   => 'gray',
                        ],
                    ],
                ]);
            },
        ];
    }
}