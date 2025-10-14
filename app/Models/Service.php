<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_invoice',
        'customer_id',
        'technician_id',
        'handphone_id',
        'damage_description',
        'status',
        'received_date',
        'completed_date',
        'other_cost',
        'total_cost',
        'paid',
        'change',
        'status_paid',
    ];

    /**
     * Relasi ke pelanggan (User dengan role customer)
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Relasi ke teknisi (User dengan role technician)
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Relasi ke handphone
     */
    public function handphone()
    {
        return $this->belongsTo(Handphone::class, 'handphone_id');
    }

    /**
     * Relasi ke detail service
     */
    public function details()
    {
        return $this->hasMany(ServiceDetail::class, 'service_id');
    }
}