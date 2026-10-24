<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot_number',
        'barcode',
        'raw_material_id',
        'supplier_id',
        'supplier_lot_number',
        'receiving_date',
        'production_date',
        'expiry_date',
        'quantity',
        'available_quantity',
        'in_production_quantity',
        'unit',
        'status',
        'moisture_percentage',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'receiving_date' => 'date',
        'production_date' => 'date',
        'expiry_date' => 'date',
        'quantity' => 'decimal:3',
        'available_quantity' => 'decimal:3',
        'in_production_quantity' => 'decimal:3',
        'moisture_percentage' => 'decimal:2',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function batchMaterials()
    {
        return $this->hasMany(ProductionBatchMaterial::class);
    }

    public function materialIssues()
    {
        return $this->hasMany(MaterialIssueOrder::class);
    }

    public function inspections()
    {
        return $this->hasMany(QualityInspection::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'approved' => '<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle me-1"></i>معتمد (Approved)</span>',
            'pending' => '<span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-clock me-1"></i>قيد الفحص (Pending QC)</span>',
            'quarantine' => '<span class="badge bg-info-subtle text-info border border-info-subtle"><i class="bi bi-shield me-1"></i>حجر صحي (Quarantine)</span>',
            'rejected' => '<span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-x-circle me-1"></i>مرفوض (Rejected)</span>',
            'consumed' => '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="bi bi-archive me-1"></i>مستهلك (Consumed)</span>',
            default => '<span class="badge bg-light text-dark border">غير محدد</span>',
        };
    }
}
