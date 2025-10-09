<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_invoice',
        'customer_id',
        'technician_id',
        'handphone_id',
        'damage_description',
        'estimated_cost',
        'status',
        'total_cost',
        'other_cost',
        'paid',
        'change',
        'paymentmethod',
        'status_paid',
        'received_date',
        'completed_date',
    ];

    // Relasi ke customer (user)
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Relasi ke technician (user)
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    // Relasi ke handphone
    public function handphone(): BelongsTo
    {
        return $this->belongsTo(Handphone::class);
    }

    // Relasi ke service details
    public function details(): HasMany
    {
        return $this->hasMany(ServiceDetail::class);
    }
}
