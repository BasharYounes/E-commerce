<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'adv_id',
        'user_id',
        'type',
        'content',
        'is_view'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function adv()
    {
        return $this->belongsTo(Adv::class, 'adv_id');
    }
}
