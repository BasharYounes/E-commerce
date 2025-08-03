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

    protected $hidden = ['created_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Many-to-Many relationships through pivot tables
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes', 'adv_id', 'user_id');
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'adv_id', 'user_id');
    }

    public function evaluatedByUsers()
    {
        return $this->belongsToMany(User::class, 'evaluations', 'adv_id', 'user_id')
                    ->withPivot('rating', 'comment')
                    ->withTimestamps();
    }

    public function reportedByUsers()
    {
        return $this->belongsToMany(User::class, 'reports', 'adv_id', 'user_id')
                    ->withPivot('type', 'content')
                    ->withTimestamps();
    }

}
