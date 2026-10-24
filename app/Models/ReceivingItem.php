<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiving_record_id',
        'raw_material_id',
        'packaging_material_id',
        'lot_number',
        'supplier_lot_number',
        'quantity',
        'unit',
        'production_date',
        'expiry_date',
        'qc_status',
        'accepted_quantity',
        'rejected_quantity',
        'notes',
    ];

    protected $casts = [
        'production_date' => 'date',
        'expiry_date' => 'date',
        'quantity' => 'decimal:3',
        'accepted_quantity' => 'decimal:3',
        'rejected_quantity' => 'decimal:3',
    ];

    public function receivingRecord()
    {
        return $this->belongsTo(ReceivingRecord::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function packagingMaterial()
    {
        return $this->belongsTo(PackagingMaterial::class);
    }
}
