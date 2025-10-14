<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDetail extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database (tanpa underscore)
     */
    protected $table = 'servicedetails';

    protected $fillable = [
        'service_id',
        'serviceitem_id',
        'qty',
        'price',
        'subtotal',
    ];

    /**
     * Relasi ke tabel services
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Relasi ke tabel serviceitems
     */
    public function serviceitem()
    {
        return $this->belongsTo(ServiceItem::class, 'serviceitem_id');
    }
}