<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\GenerateMonthlyInvoices;
use App\Models\Customer;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Jadwal untuk menjalankan perintah secara bulanan pada tanggal dan jam tertentu
        $schedule->command('invoices:generate')->monthlyOn(26, '13:07');

        // Jika ingin mengatur jadwal berdasarkan tanggal bergabung pelanggan, bisa gunakan loop seperti ini
        // $customers = Customer::all();
        // foreach ($customers as $customer) {
        //     $joinDate = Carbon::parse($customer->tanggal_bergabung);
        //     $schedule->command('invoices:generate --customer_id=' . $customer->id)
        //         ->monthlyOn($joinDate->format('d'), $joinDate->format('H:i'));
        // }
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
