<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityInspectionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'quality_inspection_id',
        'parameter',
        'specification',
        'actual_result',
        'unit',
        'result',
        'notes',
    ];

    public function inspection()
    {
        return $this->belongsTo(QualityInspection::class, 'quality_inspection_id');
    }
}
