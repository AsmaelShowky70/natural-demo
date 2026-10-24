<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAuditRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_number',
        'audit_date',
        'auditor_name',
        'warehouse_type',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'audit_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(StockAuditItem::class);
    }
}
