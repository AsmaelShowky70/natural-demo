<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiving_number',
        'receiving_type',
        'supplier_id',
        'receiving_date',
        'vehicle_number',
        'driver_name',
        'received_by',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'receiving_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(ReceivingItem::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
