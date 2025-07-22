<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adv extends Model
{
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

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

   
}
