<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'name_ar',
        'email',
        'password',
        'role',
        'department',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isWarehouse(): bool
    {
        return in_array($this->role, ['admin', 'warehouse']);
    }

    public function isProduction(): bool
    {
        return in_array($this->role, ['admin', 'production_manager']);
    }

    public function isQuality(): bool
    {
        return in_array($this->role, ['admin', 'quality_control']);
    }

    public function isAuditor(): bool
    {
        return $this->role === 'viewer';
    }

    public function getRoleTitleAttribute(): string
    {
        return match($this->role) {
            'admin' => 'الإدارة العليا (Admin)',
            'production_manager' => 'مدير الإنتاج (Production)',
            'quality_control' => 'مراقبة الجودة (QC/QA)',
            'warehouse' => 'أمين المستودع (Warehouse)',
            default => 'مدقق ومراجع (Auditor / Viewer)',
        };
    }
}
