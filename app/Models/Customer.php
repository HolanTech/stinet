<?php

namespace App\Models;

use App\Models\Paket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    protected $table = "customers";
    protected $guarded = [];

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }
}
