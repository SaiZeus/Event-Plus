<?php



namespace App\Http\Controllers;



use App\Models\Order;

use App\Models\Attendee;

use App\Models\User;

use App\Models\PromoCode;

use App\Models\TicketCategory;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use App\Jobs\SendTicketEmailJob;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Log;

use Exception;



class CheckoutController extends Controller

{

    public function process(Request $request)

    {

        $request->validate([

            'event_id'                         => 'required|exists:events,id',

            'attendees'                        => 'required|array|min:1',

            'attendees.*.ticket_category_id'   => 'required|exists:ticket_categories,id',

            'attendees.*.promo_code_id'        => 'nullable|exists:promo_codes,id',

            'attendees.*.full_name'            => 'required|string|max:255',

            'attendees.*.email'                => 'required|email|max:255',

            'attendees.*.phone'                => 'required|string|max:50',

            'attendees.*.viber'                => 'nullable|string|max:50',

            'attendees.*.nrc_passport'         => 'required|string|max:100',

            'attendees.*.nationality'          => 'required|string|max:100',

            'attendees.*.tshirt_size'          => 'nullable|string|max:10',

            'attendees.*.father_name'          => 'nullable|string|max:255',

            'attendees.*.emergency_contact'    => 'nullable|string|max:50',

            'attendees.*.country'              => 'nullable|string|max:100',

            'attendees.*.gender'               => 'nullable|string',

            'attendees.*.date_of_birth'        => 'nullable|sometimes|date',

            'attendees.*.bib_name'             => 'nullable|string|max:10',

            'attendees.*.blood_type'           => 'nullable|string',

            'attendees.*.has_medical_condition'=> 'nullable|string',

            'attendees.*.medical_details'      => 'nullable|string',

            'attendees.*.itra'                 => 'nullable|string',

            'attendees.*.itra_details'         => 'nullable|string',

            'attendees.*.address'              => 'nullable|string',

            'attendees.*.experience'           => 'nullable|string',

        ]);



        $totalAmount = 0;



        // 1. Calculate final discounted amount for order

        foreach ($request->attendees as $attendeeData) {

            $category = TicketCategory::findOrFail($attendeeData['ticket_category_id']);



            $itemPrice = (strtolower($attendeeData['nationality']) === 'foreigner' && $category->foreign_price)

                ? (float) $category->foreign_price

                : (float) $category->local_price;



            if (!empty($attendeeData['promo_code_id'])) {

                $promo = PromoCode::find($attendeeData['promo_code_id']);



                if ($promo) {

                    $isEventMatch = (int)$promo->event_id === (int)$request->event_id;

                    $isCategoryMatch = is_null($promo->ticket_category_id) || ((int)$promo->ticket_category_id === (int)$category->id);



                    if ($isEventMatch && $isCategoryMatch) {

                        if ($promo->discount_type === 'percentage') {

                            $itemDiscount = ($itemPrice * ((float)$promo->discount_value / 100));

                        } else {

                            $itemDiscount = (float) $promo->discount_value;

                        }



                        $itemPrice -= $itemDiscount;

                    }

                }

            }



            $totalAmount += max(0, $itemPrice);

        }



        // Round to 2 decimal places to ensure floating point accuracy for CB Bank payload

        $totalAmount = round($totalAmount, 2);



        $order = Order::create([

            'event_id'       => $request->event_id,

            'order_number'   => 'ORD-' . strtoupper(Str::random(8)),

            'total_amount'   => $totalAmount,

            'payment_method' => 'MMQR',

            'payment_status' => 'pending',

        ]);



        foreach ($request->attendees as $attendeeData) {

            $user = User::firstOrCreate(

                ['email' => $attendeeData['email']],

                [

                    'name'     => $attendeeData['full_name'],

                    'password' => bcrypt(Str::random(16)),

                ]

            );



            $userCode = 'EP-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);



            // Create attendee record with NULL codes while pending

            Attendee::create([

                'order_id'              => $order->id,

                'ticket_category_id'    => $attendeeData['ticket_category_id'],

                'promo_code_id'         => $attendeeData['promo_code_id'] ?? null,

                'full_name'             => $attendeeData['full_name'],

                'father_name'           => $attendeeData['father_name'] ?? null,

                'email'                 => $attendeeData['email'],

                'phone'                 => $attendeeData['phone'],

                'viber'                 => $attendeeData['viber'] ?? null,

                'emergency_contact'     => $attendeeData['emergency_contact'] ?? null,

                'nrc_passport'          => $attendeeData['nrc_passport'],

                'nationality'           => $attendeeData['nationality'],

                'country'               => $attendeeData['country'] ?? null,

                'gender'                => $attendeeData['gender'] ?? null,

                'date_of_birth'         => !empty($attendeeData['date_of_birth']) ? $attendeeData['date_of_birth'] : null,

                'bib_name'              => null, // Generated upon payment completion

                'tshirt_size'           => $attendeeData['tshirt_size'] ?? null,

                'blood_type'            => $attendeeData['blood_type'] ?? null,

                'has_medical_condition' => !empty($attendeeData['has_medical_condition']) ? $attendeeData['has_medical_condition'] : 'no',

                'medical_details'       => $attendeeData['medical_details'] ?? null,

                'itra'                  => !empty($attendeeData['itra']) ? $attendeeData['itra'] : 'no',

                'itra_details'          => $attendeeData['itra_details'] ?? null,

                'address'               => $attendeeData['address'] ?? null,

                'experience'            => $attendeeData['experience'] ?? null,

                'ticket_uuid'           => (string) Str::uuid(),

                'ticket_code'           => null, // Generated upon payment completion

                'user_code'             => $userCode,

            ]);

        }



        return redirect()->route('checkout.payment', $order);

    }



    protected function generateMmqrCode($orderNumber, $amount)

    {

        $env = config('mmqr.environment', 'uat');

        $config = config("mmqr.{$env}");



        try {

            $tokenResponse = Http::post("{$config['base_url']}/auth/token", [

                'userId'   => $config['user_id'],

                'password' => $config['password'],

            ]);



            if (!$tokenResponse->successful() || empty($tokenResponse['token'])) {

                Log::error('MMQR Auth Token Failed', ['response' => $tokenResponse->json()]);



                try {

                    Mail::raw("MMQR Auth Token Failed for Order: {$orderNumber}. Response: " . json_encode($tokenResponse->json()), function ($message) {

                        $message->to('eventplusticketing@gmail.com')

                                ->subject('❌ Payment Token Error - Order ' . $orderNumber);

                    });

                } catch (\Exception $mailEx) {

                    Log::error('Failed to send admin error email: ' . $mailEx->getMessage());

                }



                return null;

            }



            $token = $tokenResponse['token'];



            $qrResponse = Http::post("{$config['base_url']}/dynamicqr", [

                'token'                => $token,

                'sourceId'             => $config['source_id'],

                'transCurrency'        => '104',

                'transAmount'          => (float) $amount,

                'purposeOfTransaction' => 'Marathon Ticket',

                'referenceNo'          => $orderNumber,

                'merchantId'           => $config['mid'],

                'terminalId'           => $config['tid'],

            ]);



            if ($qrResponse->successful() && ($qrResponse['returnCode'] ?? null) === '0000') {

                return [

                    'qr_code_info'    => $qrResponse['qrCodeInfo'],

                    'qr_reference_no' => $qrResponse['qrReferenceNo'],

                    'expired_at'      => $qrResponse['qrExpiredDateTime'] ?? null,

                ];

            }



            Log::error('MMQR Dynamic QR Generation Failed', ['response' => $qrResponse->json()]);



            try {

                Mail::raw("MMQR QR Generation Failed for Order: {$orderNumber}. Response: " . json_encode($qrResponse->json()), function ($message) {

                    $message->to('eventplusticketing@gmail.com')

                            ->subject('❌ QR Generation Error - Order ' . $orderNumber);

                });

            } catch (\Exception $mailEx) {

                Log::error('Failed to send admin error email: ' . $mailEx->getMessage());

            }



            return null;

        } catch (Exception $e) {

            Log::error('MMQR Exception Encountered', ['error' => $e->getMessage()]);



            try {

                Mail::raw("MMQR Exception Encountered for Order: {$orderNumber}. Error: " . $e->getMessage(), function ($message) {

                    $message->to('eventplusticketing@gmail.com')

                            ->subject('❌ Payment Gateway Exception - Order ' . $orderNumber);

                });

            } catch (\Exception $mailEx) {

                Log::error('Failed to send admin error email: ' . $mailEx->getMessage());

            }



            return null;

        }

    }



    public function showPayment(Order $order)

    {

        if ($order->payment_status === 'paid') {

            return redirect()->route('checkout.success', $order);

        }



        if ($order->payment_status === 'pending' && $order->created_at->lt(now()->subMinutes(3))) {

            try {

                $orderInfoText = "Order Number: {$order->order_number}\nTotal Amount: {$order->total_amount}\nStatus: Expired (Pending > 3 mins)";

                Mail::raw("Payment session expired for order:\n\n{$orderInfoText}", function ($message) use ($order) {

                    $message->to('eventplusticketing@gmail.com')

                            ->subject('⚠️ Payment Session Expired - Order ' . $order->order_number);

                });

            } catch (\Exception $mailEx) {

                Log::error('Failed to send session expiration email to admin: ' . $mailEx->getMessage());

            }



            $order->attendees()->delete();

            $order->delete();



            return redirect()->route('home')->with('error', 'Your payment session has expired after 3 minutes. Please register again.');

        }



        $order->load('attendees.ticketCategory');

        $mmqrData = $this->generateMmqrCode($order->order_number, $order->total_amount);



        return view('checkout.payment', compact('order', 'mmqrData'));

    }



    public function completePayment(Request $request, Order $order)
    {
        // Do not process an order more than once.
        $currentStatus = strtolower((string) $order->payment_status);

        if (in_array($currentStatus, ['paid', 'approved', 'completed'], true)) {
            return redirect()->route('checkout.success', $order);
        }

        // The Order model observer handles ticket codes, bib numbers,
        // tickets_sold, and promo-code usage when payment becomes paid.
        $order->update(['payment_status' => 'paid']);

        $order->load('attendees');

        $attendeeSummary = [];

        foreach ($order->attendees as $attendee) {
            SendTicketEmailJob::dispatch($attendee);

            $attendeeSummary[] = "Name: {$attendee->full_name} | Email: {$attendee->email} | Ticket Code: {$attendee->ticket_code}";
        }

        // Keep the existing admin payment summary email.
        try {
            $successBody = "SUCCESSFUL PAYMENT CONFIRMATION\n\n"
                . "Order Number: {$order->order_number}\n"
                . "Total Amount: {$order->total_amount}\n\n"
                . "Attendees:\n"
                . implode("\n", $attendeeSummary);

            Mail::raw($successBody, function ($message) use ($order) {
                $message->to('eventplusticketing@gmail.com')
                    ->subject('✅ SUCCESS: Payment Completed - Order ' . $order->order_number);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send admin success summary email: ' . $e->getMessage());
        }

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)

    {

        $order->load('attendees');

        return view('checkout.success', compact('order'));

    }

}