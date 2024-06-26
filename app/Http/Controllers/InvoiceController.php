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
        $notif = new \Midtrans\Notification();

        try {
            $transaction = $notif->transaction_status;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            $invoice = Invoice::where('order_id', $orderId)->first();

            if ($transaction == 'capture') {
                if ($fraud == 'challenge') {
                    $invoice->update(['status' => 'challenged']);
                } else if ($fraud == 'accept') {
                    $invoice->update(['status' => 'paid']);
                }
            } elseif ($transaction == 'settlement') {
                $invoice->update(['status' => 'paid']);
            } elseif ($transaction == 'cancel' || $transaction == 'deny' || $transaction == 'expire') {
                $invoice->update(['status' => 'failed']);
            } elseif ($transaction == 'pending') {
                $invoice->update(['status' => 'pending']);
            }

            return response()->json(['message' => 'Payment updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Payment update failed', 'error' => $e->getMessage()]);
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
}
