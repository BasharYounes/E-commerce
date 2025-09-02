<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthunticateRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'document_path',
        'rejection_reason',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime'
    ];

    // العلاقة مع المستخدم الذي قدم الطلب
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع المسؤول الذي قام بمعالجة الطلب
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // نطاق للطلبات المعلقة
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // نطاق للطلبات المقبولة
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // نطاق للطلبات المرفوضة
    public function scopeRejected($query)
    {
    

    return $query->where('status', 'rejected');
    }
}
