<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'sku',
        'name',
        'name_ar',
        'product_category_id',
        'product_type',
        'size',
        'unit',
        'description',
        'description_ar',
        'image_path',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function boms()
    {
        return $this->hasMany(ProductBom::class);
    }

    public function activeBom()
    {
        return $this->hasOne(ProductBom::class)->where('is_active', true)->latest('version');
    }

    public function batches()
    {
        return $this->hasMany(ProductionBatch::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
