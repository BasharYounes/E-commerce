<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'image',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at', 
        'updated_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'published_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Many-to-Many relationships through pivot tables
    public function likedAdvs()
    {
        return $this->belongsToMany(Adv::class, 'likes', 'user_id', 'adv_id');
    }

    public function favoriteAdvs()
    {
        return $this->belongsToMany(Adv::class, 'favorites', 'user_id', 'adv_id');
    }

    public function evaluatedAdvs()
    {
        return $this->belongsToMany(Adv::class, 'evaluations', 'user_id', 'adv_id')
                    ->withPivot('rating', 'comment')
                    ->withTimestamps();
    }

    public function reportedAdvs()
    {
        return $this->belongsToMany(Adv::class, 'reports', 'user_id', 'adv_id')
                    ->withPivot('type', 'content')
                    ->withTimestamps();
    }

    public function advs()
    {
        return $this->hasMany(Adv::class);
    }

}
