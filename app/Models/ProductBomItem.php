<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBomItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_bom_id',
        'raw_material_id',
        'quantity',
        'unit',
        'percentage',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'percentage' => 'decimal:3',
    ];

    public function bom()
    {
        return $this->belongsTo(ProductBom::class, 'product_bom_id');
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}
