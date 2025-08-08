<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Get advertisements through activities.
     */
    public function activitiesAdvertisements()
    {
        return $this->belongsToMany(Adv::class, 'user_activities', 'user_id', 'adv_id')
                    ->withPivot('activity_type')
                    ->withTimestamps();
    }

    /**
     * Get advertisements by activity type.
     */
    public function advertisementsByActivityType($type)
    {
        return $this->belongsToMany(Adv::class, 'user_activities', 'user_id', 'adv_id')
                    ->withPivot('activity_type')
                    ->wherePivot('activity_type', $type)
                    ->withTimestamps();
    }

    /**
     * Get all activities performed by the user.
     */
    public function userActivities()
    {
        return $this->hasMany(UserActivities::class, 'user_id');
    }

    /**
     * Get activities by type.
     */
    public function userActivitiesByType($type)
    {
        return $this->hasMany(UserActivities::class, 'user_id')->where('activity_type', $type);
    }

    /**
     * Accessor: return full image URL if stored path exists
     */
    public function getImageAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        // If already a URL or public storage path, return as is
        if (is_string($value) && (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, '/storage/'))) {
            return $value;
        }
        return Storage::url($value);
    }

}
