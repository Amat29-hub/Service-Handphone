<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Handphone extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'handphones';

    protected $fillable = [
        'image',
        'brand',
        'model',
        'release_year',
        'is_active',
    ];

    protected $dates = ['deleted_at'];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}