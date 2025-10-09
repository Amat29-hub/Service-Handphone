<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'price',
        'is_active',
    ];

    // Relasi ke ServiceDetail
    public function serviceDetails(): HasMany
    {
        return $this->hasMany(ServiceDetail::class);
    }
}
