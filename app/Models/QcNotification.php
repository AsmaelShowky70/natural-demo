<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'title_ar',
        'message',
        'message_ar',
        'target_id',
        'batch_or_lot_number',
        'status',
    ];
}
