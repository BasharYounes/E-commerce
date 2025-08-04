<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $table = 'evaluations';

    protected $fillable = [
        'adv_id',
        'user_id',
        'rating',
        'comment'
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
