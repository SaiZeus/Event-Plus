<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Jobs\SendTicketEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class MmqrCallbackController extends Controller
{
    /**
     * CB Bank MMQR Callback Handler
     * URL: https://eventplus.com.mm/api/payment/mmqr/callback
     */
    public function handleCallback(Request $request)
    {
        Log::info('MMQR Callback Received', $request->all());

        $referenceNo = $request->input('referenceNo');
        $transStatusCode = $request->input('transStatusCode');
        $qrReferenceNo = $request->input('qrReferenceNo');

        if (! $referenceNo) {
            return response()->json([
                'qrReferenceNo' => $qrReferenceNo,
                'returnCode' => '0014',
                'message' => 'Missing reference number.',
            ], 400);
        }

        try {
            return DB::transaction(function () use (
                $request,
                $referenceNo,
                $transStatusCode,
                $qrReferenceNo
            ) {
                // Lock the order to prevent concurrent callback processing.
                $order = Order::with('attendees.ticketCategory.event')
                    ->where('order_number', $referenceNo)
                    ->lockForUpdate()
                    ->first();

                if (! $order) {
                    return response()->json([
                        'qrReferenceNo' => $qrReferenceNo,
                        'returnCode' => '0006',
                        'message' => 'Invalid reference number.',
                    ], 404);
                }

                // Do not process an order that has already been paid.
                // This also prevents a later failure callback from downgrading it.
                $currentStatus = strtolower((string) $order->payment_status);

                if (in_array($currentStatus, ['paid', 'approved', 'completed'], true)) {
                    return response()->json([
                        'qrReferenceNo' => $qrReferenceNo,
                        'returnCode' => '0000',
                        'message' => 'The transaction is succeeded',
                    ]);
                }

                if ($transStatusCode === 'SUCCESS') {
                    // Check capacity before confirming payment.
                    foreach ($order->attendees as $attendee) {
                        $category = $attendee->ticketCategory;

                        if ($category && $category->capacity !== null) {
                            if (($category->tickets_sold + 1) > $category->capacity) {
                                // Flag the order for manual refund/support review.
                                $order->update([
                                    'payment_status' => 'refund_required',
                                    'transaction_id' => $request->input('transactionId'),
                                ]);

                                Log::warning(
                                    "MMQR Overbook Detected for Order {$referenceNo}. Flagged for refund."
                                );

                                return response()->json([
                                    'qrReferenceNo' => $qrReferenceNo,
                                    'returnCode' => '0007',
                                    'message' => 'Ticket capacity exceeded. Order flagged for refund.',
                                ]);
                            }
                        }
                    }

                    // Updating the payment status triggers the Order model observer.
                    // The observer generates ticket codes/bibs and increments tickets_sold.
                    $order->update([
                        'payment_status' => 'paid',
                        'payment_method' => 'MMQR',
                        'transaction_id' => $request->input('transactionId'),
                    ]);

                    // Do not increment tickets_sold here.
                    // Dispatch ticket emails without blocking the callback response.
                    foreach ($order->attendees as $attendee) {
                        SendTicketEmailJob::dispatch($attendee);
                    }

                    return response()->json([
                        'qrReferenceNo' => $qrReferenceNo,
                        'returnCode' => '0000',
                        'message' => 'The transaction is succeeded',
                    ]);
                }

                // Payment status is not SUCCESS.
                $order->update([
                    'payment_status' => 'failed',
                ]);

                return response()->json([
                    'qrReferenceNo' => $qrReferenceNo,
                    'returnCode' => '0007',
                    'message' => 'Transaction failed',
                ]);
            });
        } catch (Exception $e) {
            Log::error('MMQR Callback Transaction Exception', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'qrReferenceNo' => $qrReferenceNo,
                'returnCode' => '9999',
                'message' => 'Internal server error processing callback.',
            ], 500);
        }
    }
}
