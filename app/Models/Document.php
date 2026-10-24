<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'title',
        'file_name',
        'file_path',
        'production_batch_id',
        'raw_material_lot_id',
        'receiving_record_id',
        'packaging_material_lot_id',
        'upload_date',
        'uploaded_by',
        'description',
    ];

    protected $casts = [
        'upload_date' => 'date',
    ];

    public function batch()
    {
        return $this->belongsTo(ProductionBatch::class, 'production_batch_id');
    }

    public function rawMaterialLot()
    {
        return $this->belongsTo(RawMaterialLot::class, 'raw_material_lot_id');
    }

    public function receivingRecord()
    {
        return $this->belongsTo(ReceivingRecord::class, 'receiving_record_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getTypeTitleAttribute(): string
    {
        return match($this->document_type) {
            'coa' => 'شهادة تحليل (Certificate of Analysis - COA)',
            'qc_report' => 'تقرير فحص جودة (QC Report)',
            'production_report' => 'تقرير تشغيل إنتاج (Production Report)',
            'receiving_certificate' => 'سند إدخال واستلام (Receiving Note)',
            'supplier_certificate' => 'شهادة اعتماد مورد (Supplier Certificate)',
            'packaging_certificate' => 'شهادة فحص مواد تعبئة (Packaging Spec)',
            'audit_document' => 'محضر تدقيق ومراجعة (Audit Log Document)',
            default => 'مستند رسمي (General Document)',
        };
    }
}
