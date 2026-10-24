<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBom extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'version',
        'name',
        'name_ar',
        'effective_date',
        'is_active',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'is_active' => 'boolean',
        'version' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(ProductBomItem::class);
    }
}
