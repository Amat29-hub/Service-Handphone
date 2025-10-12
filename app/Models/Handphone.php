<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Handphone extends Model
{
    use HasFactory;

    protected $table = 'handphones'; // nama tabel di database

    protected $fillable = [
        'image',
        'brand',
        'model',
        'release_year',
        'is_active',
    ];

    // Relasi ke Service
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}