<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketVerificationController extends Controller
{
    public function show($token)
    {
        $data = $this->getTicketData($token);

        return view('verify.status', $data);
    }

    public function downloadPdf($token)
    {
        $data = $this->getTicketData($token);

        $pdf = Pdf::loadView('verify.pdf', $data);

        return $pdf->download(
            'ticket-report-' . $data['attendee']->ticket_code . '.pdf'
        );
    }

    private function getTicketData($token)
    {
        $attendee = Attendee::with('ticketCategory.event')
            ->where('verification_token', $token)
            ->firstOrFail();

        $eventDate = Carbon::parse(
            $attendee->ticketCategory->event->event_date
        );

        $isExpired = Carbon::now()->greaterThan($eventDate);

        return [
            'attendee' => $attendee,
            'isExpired' => $isExpired,
        ];
    }
}