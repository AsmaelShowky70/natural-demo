<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'finished_goods_barcode',
        'product_id',
        'product_bom_id',
        'production_date',
        'production_start_time',
        'production_end_time',
        'shift',
        'production_line',
        'production_supervisor',
        'planned_quantity',
        'actual_quantity',
        'cartons_produced',
        'units_per_carton',
        'accepted_quantity',
        'rejected_quantity',
        'waste_quantity',
        'waste_unit',
        'productivity_yield',
        'scrap_percentage',
        'transferred_to_warehouse',
        'batch_status',
        'qc_status',
        'final_release_status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'production_date' => 'date',
        'production_start_time' => 'datetime',
        'production_end_time' => 'datetime',
        'planned_quantity' => 'decimal:3',
        'actual_quantity' => 'decimal:3',
        'cartons_produced' => 'integer',
        'units_per_carton' => 'integer',
        'accepted_quantity' => 'decimal:3',
        'rejected_quantity' => 'decimal:3',
        'waste_quantity' => 'decimal:3',
        'productivity_yield' => 'decimal:2',
        'scrap_percentage' => 'decimal:2',
        'transferred_to_warehouse' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function bom()
    {
        return $this->belongsTo(ProductBom::class, 'product_bom_id');
    }

    public function materials()
    {
        return $this->hasMany(ProductionBatchMaterial::class);
    }

    public function packagings()
    {
        return $this->hasMany(ProductionBatchPackaging::class);
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
        return match($this->batch_status) {
            'released' => '<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-patch-check-fill me-1"></i>مفرج عنه (Released)</span>',
            'in_production' => '<span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-gear-wide-connected me-1"></i>قيد التصنيع (In Production)</span>',
            'qc_pending' => '<span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-hourglass-split me-1"></i>بانتظار الجودة (QC Pending)</span>',
            'completed' => '<span class="badge bg-info-subtle text-info border border-info-subtle"><i class="bi bi-check-all me-1"></i>مكتمل الإنتاج (Completed)</span>',
            'on_hold' => '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="bi bi-pause-circle me-1"></i>معلق (On Hold)</span>',
            'rejected' => '<span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-x-octagon-fill me-1"></i>مرفوض (Rejected)</span>',
            default => '<span class="badge bg-light text-dark border">مسودة (Draft)</span>',
        };
    }
}
