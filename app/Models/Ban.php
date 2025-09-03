<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reason',
        'banned_until',
        'is_permanent',
    ];

    protected $casts = [
        'banned_until' => 'datetime',
        'restricted_actions' => 'array',
        'is_permanent' => 'boolean'
    ];

    // العلاقة مع المستخدم المحظور
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // نطاق للحظورات النشطة
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->where('is_permanent', true)
              ->orWhere('banned_until', '>', now());
        });
    }

    // التحقق إذا كان الحظر نشطًا
    public function isActive()
    {
        return $this->is_permanent || $this->banned_until > now();
    }
}
