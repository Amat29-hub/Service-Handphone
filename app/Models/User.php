<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'image',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke service sebagai customer
    public function customerServices(): HasMany
    {
        return $this->hasMany(Service::class, 'customer_id');
    }

    // Relasi ke service sebagai teknisi
    public function technicianServices(): HasMany
    {
        return $this->hasMany(Service::class, 'technician_id');
    }
}
