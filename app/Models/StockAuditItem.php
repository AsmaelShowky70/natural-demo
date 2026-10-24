<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAuditItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_audit_record_id',
        'lot_number',
        'item_code',
        'item_name',
        'system_quantity',
        'physical_quantity',
        'variance',
        'unit',
        'notes',
    ];

    protected $casts = [
        'system_quantity' => 'decimal:3',
        'physical_quantity' => 'decimal:3',
        'variance' => 'decimal:3',
    ];

    public function record()
    {
        return $this->belongsTo(StockAuditRecord::class, 'stock_audit_record_id');
    }
}
