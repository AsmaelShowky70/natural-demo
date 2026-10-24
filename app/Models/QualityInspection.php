<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_number',
        'inspection_target',
        'production_batch_id',
        'raw_material_lot_id',
        'packaging_material_lot_id',
        'inspection_date',
        'inspector',
        'inspection_type',
        'overall_result',
        'approval_status',
        'moisture_percentage',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'moisture_percentage' => 'decimal:2',
    ];

    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class, 'production_batch_id');
    }

    public function rawMaterialLot()
    {
        return $this->belongsTo(RawMaterialLot::class, 'raw_material_lot_id');
    }

    public function packagingMaterialLot()
    {
        return $this->belongsTo(PackagingMaterialLot::class, 'packaging_material_lot_id');
    }

    public function results()
    {
        return $this->hasMany(QualityInspectionResult::class);
    }

    public function getResultBadgeAttribute(): string
    {
        return match($this->overall_result) {
            'pass' => '<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>ناجح (PASS)</span>',
            'fail' => '<span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-x-circle-fill me-1"></i>راسب (FAIL)</span>',
            default => '<span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-pause-circle me-1"></i>معلق (HOLD)</span>',
        };
    }
}
