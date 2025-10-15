<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'amount',
        'status',
        'payment_date',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

