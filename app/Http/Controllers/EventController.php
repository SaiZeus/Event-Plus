<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['ticketCategories', 'items'])
            ->select('*')
            ->selectRaw("
                CASE 
                    WHEN status = 'live' THEN 1 
                    WHEN status = 'upcoming' THEN 2 
                    WHEN status = 'past' THEN 3 
                    ELSE 4 
                END as status_priority
            ")
            ->orderBy('status_priority', 'asc')
            ->orderBy('event_date', 'asc')
            ->get();

        $liveEvents     = $events->where('status', 'live');
        $upcomingEvents = $events->where('status', 'upcoming');
        $pastEvents     = $events->where('status', 'past');

        return view('index', compact('events', 'liveEvents', 'upcomingEvents', 'pastEvents'));
    }

    public function show(Event $event)
    {
        // GROUP BUY: also load the active group buy deals, smallest group first.
        $event->load([
            'ticketCategories',
            'promoCodes',
            'items',
            'groupBuys' => fn ($query) => $query->where('is_active', true)->orderBy('quantity'),
        ]);

        $totalTicketsSold = $event->ticketCategories->sum('tickets_sold');
        
        // Calculate remaining overall event capacity
        $overallCapacity = $event->overall_capacity;
        $overallTicketsRemaining = $overallCapacity !== null ? max(0, $overallCapacity - $totalTicketsSold) : null;
        $isEventSoldOut = $overallCapacity !== null && ($totalTicketsSold >= $overallCapacity);

        // GROUP BUY: build the deals the page can safely show.
        // A "selected categories" deal is dropped if none of its ticket types still exist.
        $categoryIds = $event->ticketCategories->pluck('id')->all();

        $groupBuys = $event->groupBuys->map(function ($deal) use ($categoryIds) {
            $allowedIds = null;

            if ($deal->ticket_scope === 'selected_categories') {
                $allowedIds = array_values(array_map(
                    'intval',
                    array_intersect($deal->allowed_ticket_category_ids ?? [], $categoryIds)
                ));

                if (empty($allowedIds)) {
                    return null;
                }
            }

            return [
                'id' => $deal->id,
                'name' => $deal->name,
                'quantity' => (int) $deal->quantity,
                'discount_amount' => (float) $deal->discount_amount,
                'ticket_scope' => $deal->ticket_scope,
                'allowed_ticket_category_ids' => $allowedIds,
            ];
        })->filter()->values();

        return view('events.show', compact(
            'event',
            'totalTicketsSold',
            'overallTicketsRemaining',
            'isEventSoldOut',
            'groupBuys' // GROUP BUY: added
        ));
    }

    /**
     * Show Intermediate Waiver Blade before Race Guide / Payment
     */
    public function showWaiver(Request $request, Event $event)
    {
        Log::info('=== STEP 2: WAIVER PAGE RECEIVED DATA ===');
        Log::info('Event ID:', ['event_id' => $event->id]);
        Log::info('Attendees Posted to Waiver:', $request->input('attendees', []));

        $attendeesInput = $request->input('attendees', session('_old_input.attendees', []));
        $promoCode      = $request->input('promo_code', session('_old_input.promo_code'));

        // GROUP BUY: carry the chosen deal through to the waiver step.
        $groupBuyId     = $request->input('group_buy_id', session('_old_input.group_buy_id'));

        if (empty($attendeesInput)) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Please complete registration details first.');
        }

        $attendees = [];
        foreach ($attendeesInput as $index => $data) {
            $nrcPassport = $data['nrc_passport'] ?? null;

            if (empty($nrcPassport)) {
                if (isset($data['nationality']) && $data['nationality'] === 'Foreigner') {
                    $nrcPassport = $data['passport_number'] ?? null;
                } else {
                    $state    = $data['nrc_state'] ?? '';
                    $district = $data['nrc_district'] ?? '';
                    $naing    = $data['nrc_naing'] ?? '';
                    $number   = $data['nrc_number'] ?? '';

                    if ($state && $district && $naing && $number) {
                        $nrcPassport = "{$state}/{$district}({$naing}){$number}";
                    }
                }
            }

            $data['nrc_passport'] = $nrcPassport;
            $attendees[$index] = $data;
        }

        return view('events.waiver', compact('event', 'attendees', 'promoCode', 'groupBuyId'));
    }

    /**
     * Handle Waiver Submission -> Route to Race Guide or Checkout Process
     */
    public function acceptWaiver(Request $request, Event $event)
    {
        $attendees = $request->input('attendees', []);
        $promoCode = $request->input('promo_code');

        // GROUP BUY: keep the chosen deal when moving on to the race guide.
        $groupBuyId = $request->input('group_buy_id');

        // === DEBUG LOG: WAIVER ACCEPT STEP ===
        Log::info('=== ACCEPT WAIVER METHOD EXECUTED ===', [
            'event_id'   => $event->id,
            'promo_code' => $promoCode,
            'attendees'  => $attendees,
        ]);

        foreach ($attendees as $idx => $att) {
            Log::info("Waiver Accept Attendee #{$idx} Check:", [
                'has_promo_id_key' => array_key_exists('promo_code_id', $att),
                'promo_code_id'    => $att['promo_code_id'] ?? 'MISSING/NULL',
            ]);
        }

        if (empty($attendees)) {
            Log::warning('Waiver Accept Failed: Attendees array empty');
            return redirect()->route('events.show', $event)
                ->with('error', 'Registration session expired. Please select your tickets again.');
        }

        // Strict non-empty check for race guide file paths
        $hasRaceGuide = !empty(trim($event->english_race_guide ?? '')) || !empty(trim($event->burmese_race_guide ?? ''));

        if ($hasRaceGuide) {
            Log::info('Routing to race_guide view. Checking if promo_code_id will be passed.');
            return view('events.race_guide', compact('event', 'attendees', 'promoCode', 'groupBuyId'));
        }

        Log::info('No race guide found. Forwarding request directly to CheckoutController@process');

        // If no Race Guide exists, forward directly to CheckoutController@process
        return app(CheckoutController::class)->process($request);
    }

    /**
     * Handle Race Guide Submission -> Route to Checkout Process
     */
    public function acceptRaceGuide(Request $request, Event $event)
    {
        $attendees = $request->input('attendees', []);

        if (empty($attendees)) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Registration session expired. Please select your tickets again.');
        }

        return app(CheckoutController::class)->process($request);
    }
}