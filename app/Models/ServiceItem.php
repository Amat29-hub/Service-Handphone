<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    use HasFactory;

    protected $table = 'serviceitems';

    protected $fillable = [
        'service_name',
        'price',
        'is_active',
    ];
}