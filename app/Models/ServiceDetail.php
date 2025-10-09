<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'serviceitem_id',
        'price',
    ];

    // Relasi ke service
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // Relasi ke service item
    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class);
    }
}
