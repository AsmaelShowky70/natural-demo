<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialIssueOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_number',
        'issue_date',
        'raw_material_lot_id',
        'quantity',
        'unit',
        'target_line',
        'issued_by',
        'received_by',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'quantity' => 'decimal:3',
    ];

    public function rawMaterialLot()
    {
        return $this->belongsTo(RawMaterialLot::class);
    }
}
