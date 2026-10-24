<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagingMaterialLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot_number',
        'barcode',
        'packaging_material_id',
        'supplier_id',
        'supplier_lot_number',
        'receiving_date',
        'quantity',
        'available_quantity',
        'in_production_quantity',
        'unit',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'receiving_date' => 'date',
        'quantity' => 'decimal:3',
        'available_quantity' => 'decimal:3',
        'in_production_quantity' => 'decimal:3',
    ];

    public function packagingMaterial()
    {
        return $this->belongsTo(PackagingMaterial::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function batchPackagings()
    {
        return $this->hasMany(ProductionBatchPackaging::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'approved' => '<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle me-1"></i>معتمد (Approved)</span>',
            'pending' => '<span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-clock me-1"></i>قيد الفحص (Pending QC)</span>',
            'rejected' => '<span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-x-circle me-1"></i>مرفوض (Rejected)</span>',
            default => '<span class="badge bg-light text-dark border">غير محدد</span>',
        };
    }
}
