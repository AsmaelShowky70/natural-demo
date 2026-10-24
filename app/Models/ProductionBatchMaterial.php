<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatchMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_batch_id',
        'raw_material_lot_id',
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

    public function rawMaterialLot()
    {
        return $this->belongsTo(RawMaterialLot::class, 'raw_material_lot_id');
    }
}
