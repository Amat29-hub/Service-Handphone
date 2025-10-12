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
        'service_item_id',
        'damage_description',
        'estimated_cost',
        'other_cost',
        'total_cost',
        'paid',
        'change',
        'paymentmethod',
        'status',
        'status_paid',
        'received_date',
        'completed_date',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'other_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'paid' => 'decimal:2',
        'change' => 'decimal:2',
        'received_date' => 'datetime',
        'completed_date' => 'datetime',
    ];

    /**
     * 🔹 Relasi ke pelanggan (User)
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * 🔹 Relasi ke teknisi (User)
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * 🔹 Relasi ke handphone
     */
    public function handphone(): BelongsTo
    {
        return $this->belongsTo(Handphone::class);
    }

    /**
     * 🔹 Relasi ke item servis utama (service_item_id)
     */
    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class, 'service_item_id');
    }

    /**
     * 🔹 Relasi ke detail item tambahan (jika ada beberapa item)
     */
    public function items(): HasMany
    {
        return $this->hasMany(ServiceDetail::class);
    }

    /**
     * 🔹 Helper label status servis
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'accepted' => 'Diterima',
            'process' => 'Dalam Proses',
            'finished' => 'Selesai',
            'taken' => 'Sudah Diambil',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status ?? '-'),
        };
    }

    /**
     * 🔹 Helper label status pembayaran
     */
    public function getStatusPaidLabelAttribute(): string
    {
        return match ($this->status_paid) {
            'paid' => 'Lunas',
            'debt' => 'Hutang',
            'unpaid' => 'Belum Bayar',
            default => ucfirst($this->status_paid ?? '-'),
        };
    }

    /**
     * 🔹 Hitung total biaya otomatis (estimasi + tambahan)
     */
    public function getTotalAmountAttribute(): float
    {
        return ($this->estimated_cost ?? 0) + ($this->other_cost ?? 0);
    }

    /**
     * 🔹 Hitung kekurangan otomatis (total - yang sudah dibayar)
     */
    public function getRemainingPaymentAttribute(): float
    {
        return max(0, $this->total_cost - ($this->paid ?? 0));
    }
}