<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'image',
        'name',
        'address',
        'phonenumber',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Service (sebagai customer)
    public function customerServices(): HasMany
    {
        return $this->hasMany(Service::class, 'customer_id');
    }

    // Relasi ke Service (sebagai technician)
    public function technicianServices(): HasMany
    {
        return $this->hasMany(Service::class, 'technician_id');
    }
}
