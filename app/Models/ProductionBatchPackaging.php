<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatchPackaging extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_batch_id',
        'packaging_material_lot_id',
        'quantity_used',
        'unit',
        'notes',
    ];

    protected $casts = [
        'quantity_used' => 'decimal:3',
    ];

    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class, 'production_batch_id');
    }

    public function packagingMaterialLot()
    {
        return $this->belongsTo(PackagingMaterialLot::class, 'packaging_material_lot_id');
    }
}
