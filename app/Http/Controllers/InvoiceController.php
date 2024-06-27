<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    public function cekTagihan(Request $request)
    {
        $request->validate([
            'no_pelanggan' => 'required',
        ]);

        try {
            $noPelanggan = $request->input('no_pelanggan');
            $invoice = Invoice::where('no_pelanggan', $noPelanggan)->first();

            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tagihan tidak ditemukan.',
                ], 404);
            }

            if (!empty($invoice->order_id) && $invoice->status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum memiliki tagihan bulan ini atau tagihan bulan ini telah dibayarkan.',
                ], 200);
            }

            if ($invoice->status !== 'paid') {
                $invoice->order_id = $invoice->invoice_number . '-' . time();
                $invoice->save();
            }

            $data = [
                'customerName' => $invoice->customer->name,
                'customerNumber' => $invoice->no_pelanggan,
                'invoice_number' => $invoice->invoice_number,
                'invoice_date' => $invoice->invoice_date,
                'status' => $invoice->status,
                'billingAmount' => $invoice->amount,
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in cekTagihan', ['exception' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data.',
            ], 500);
        }
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'no_pelanggan' => 'required',
            'donation' => 'nullable|numeric'
        ]);

        $noPelanggan = $request->input('no_pelanggan');
        $donation = $request->input('donation', 0);
        $invoice = Invoice::where('no_pelanggan', $noPelanggan)->first();

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found.'
            ], 404);
        }

        if ($invoice->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This invoice has already been paid.'
            ], 200);
        }

        $invoice->order_id = $invoice->invoice_number . '-' . time();
        $invoice->save();

        $totalAmount = $invoice->amount + $donation;
        $snapToken = $this->getSnapToken($invoice, $totalAmount);

        return response()->json([
            'success' => true,
            'data' => [
                'snapToken' => $snapToken,
                'totalAmount' => $totalAmount
            ]
        ]);
    }

    private function getSnapToken(Invoice $invoice, $totalAmount)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $invoice->order_id,
                'gross_amount' => $totalAmount
            ],
            'customer_details' => [
                'first_name' => $invoice->customer->name,
                'email' => $invoice->customer->email,
                'phone' => $invoice->customer->phone,
            ],
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Error getting Snap token', ['exception' => $e->getMessage()]);
            throw $e;
        }
    }

    public function midtransNotification(Request $request)
    {
        Log::info('Midtrans Notification Payload: ', $request->all());

        $request->validate([
            'transaction_time' => 'required|string',
            'transaction_status' => 'required|string',
            'transaction_id' => 'required|string',
            'status_message' => 'required|string',
            'status_code' => 'required|string',
            'signature_key' => 'required|string',
            'payment_type' => 'required|string',
            'order_id' => 'required|string',
            'merchant_id' => 'required|string',
            'gross_amount' => 'required|numeric',
            'fraud_status' => 'required|string',
            'currency' => 'required|string',
        ]);

        $transaction = $request->input('transaction_status');
        $orderId = $request->input('order_id');
        $fraud = $request->input('fraud_status');

        try {
            Log::info('Processing order ID: ' . $orderId);

            $invoice = Invoice::where('order_id', $orderId)->first();

            if (!$invoice) {
                Log::warning('Invoice not found for order ID: ' . $orderId);
                return response()->json(['message' => 'Invoice not found'], 404);
            }

            Log::info('Transaction status: ' . $transaction . ', Fraud status: ' . $fraud);

            // Update status invoice
            if ($transaction == 'capture') {
                if ($fraud == 'challenge') {
                    $invoice->status = 'challenged';
                } else if ($fraud == 'accept') {
                    $invoice->status = 'paid';
                }
            } elseif ($transaction == 'settlement') {
                $invoice->status = 'paid';
            } elseif ($transaction == 'cancel' || $transaction == 'deny' || $transaction == 'expire') {
                $invoice->status = 'failed';
            } elseif ($transaction == 'pending') {
                $invoice->status = 'pending';
            } else {
                Log::warning('Unhandled transaction status: ' . $transaction);
            }

            $invoice->save();
            Log::info('Invoice status updated to ' . $invoice->status . ' for order ID: ' . $orderId);

            return response()->json(['message' => 'Payment updated successfully']);
        } catch (\Exception $e) {
            Log::error('Payment update failed', ['exception' => $e->getMessage()]);
            return response()->json(['message' => 'Payment update failed', 'error' => $e->getMessage()], 500);
        }
    }


    public function showInvoice($invoice_number)
    {
        $invoice = Invoice::where('invoice_number', $invoice_number)->firstOrFail();
        return view('invoices.show', compact('invoice'));
    }

    public function getInvoiceHtml($invoice_number)
    {
        $invoice = Invoice::where('invoice_number', $invoice_number)->firstOrFail();
        return view('invoices.print', compact('invoice'))->render();
    }

    public function checkMidtransConfig()
    {
        try {
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $status = \Midtrans\Transaction::status('test-order-id');
            return response()->json(['message' => 'Midtrans configuration is correct', 'status' => $status]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Midtrans configuration failed', 'error' => $e->getMessage()], 500);
        }
    }
}
