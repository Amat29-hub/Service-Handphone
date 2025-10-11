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

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'other_cost' => 'decimal:2',
        'paid' => 'decimal:2',
        'change' => 'decimal:2',
        'received_date' => 'datetime',
        'completed_date' => 'datetime',
    ];

    /**
     * Relasi ke customer (User)
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Relasi ke teknisi (User)
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Relasi ke Handphone
     */
    public function handphone(): BelongsTo
    {
        return $this->belongsTo(Handphone::class);
    }

    /**
     * Relasi ke detail item servis
     */
    public function items(): HasMany
    {
        return $this->hasMany(ServiceDetail::class);
    }

    /**
     * Status label helper
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'accepted' => 'Diterima',
            'process' => 'Dalam Proses',
            'finished' => 'Selesai',
            'taken' => 'Sudah Diambil',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    /**
     * Hitung total keseluruhan biaya (estimated + other_cost)
     */
    public function getTotalAmountAttribute(): float
    {
        return ($this->estimated_cost ?? 0) + ($this->other_cost ?? 0);
    }
}