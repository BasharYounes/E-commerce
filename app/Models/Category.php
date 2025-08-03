<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    protected $hidden = ['created_at', 'updated_at'];

    public function advs()
    {
        return $this->hasMany(Adv::class, 'category_id');
    }

    public function advReads()
    {
        return $this->hasMany(AdvRead::class, 'category_id');
    }
}
