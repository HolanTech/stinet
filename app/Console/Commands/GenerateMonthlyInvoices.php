<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;

class GenerateMonthlyInvoices extends Command
{
    protected $signature = 'invoices:generate';
    protected $description = 'Generate monthly invoices for all customers';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $customers = Customer::all();
        foreach ($customers as $customer) {
            $invoice = new Invoice();
            $invoice->customer_id = $customer->id;
            $invoice->no_pelanggan = $customer->no_pelanggan;
            $invoice->invoice_number = 'INV' . str_pad(Invoice::max('id') + 1, 8, '0', STR_PAD_LEFT);
            $invoice->invoice_date = Carbon::now(); // Ganti dengan tanggal yang sesuai
            $invoice->amount = $this->calculateAmount($customer); // Implementasikan logika perhitungan amount
            $invoice->status = 'unpaid';
            $invoice->save();
        }

        $this->info('Monthly invoices generated successfully.');
    }

    private function calculateAmount($customer)
    {
        // Implementasikan logika untuk menghitung jumlah tagihan berdasarkan paket pelanggan
        return $customer->tagihan; // Contoh sederhana
    }
}
