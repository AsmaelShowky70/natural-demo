<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_ar',
        'material_type',
        'supplier_id',
        'unit',
        'specification',
        'minimum_stock',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'minimum_stock' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function lots()
    {
        return $this->hasMany(RawMaterialLot::class);
    }

    public function totalAvailableStock(): float
    {
        return (float) $this->lots()->where('status', 'approved')->sum('available_quantity');
    }
}
