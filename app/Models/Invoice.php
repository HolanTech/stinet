<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'order_id', 'no_pelanggan', 'invoice_number', 'date', 'amount', 'status', 'donation'
    ];


    protected $dates = [
        'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'no_pelanggan', 'no_pelanggan'); // Sesuaikan foreign key dan local key jika berbeda
    }
}
