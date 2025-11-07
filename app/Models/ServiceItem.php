<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'serviceitems';

    protected $fillable = [
        'service_name',
        'price',
        'is_active',
    ];

    protected $dates = ['deleted_at'];
}