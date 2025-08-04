<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvRead extends Model
{
    protected $table = 'adv_reads';
    protected $fillable = [
        'image',
        'price',
        'location',
        'views_count',
        'interactions_count',
        'category_id',
        'description',
        'is_active',
        'user_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

}
