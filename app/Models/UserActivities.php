<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivities extends Model
{
    protected $fillable = [
        'adv_id',
        'user_id',
        'activity_type',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Scope to filter activities by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Scope to filter activities by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter activities by advertisement.
     */
    public function scopeByAdvertisement($query, $advId)
    {
        return $query->where('adv_id', $advId);
    }
}
