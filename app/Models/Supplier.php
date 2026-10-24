<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_ar',
        'contact_person',
        'email',
        'phone',
        'address',
        'country',
        'is_active',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function rawMaterials()
    {
        return $this->hasMany(RawMaterial::class);
    }

    public function rawMaterialLots()
    {
        return $this->hasMany(RawMaterialLot::class);
    }

    public function receivingRecords()
    {
        return $this->hasMany(ReceivingRecord::class);
    }
}
