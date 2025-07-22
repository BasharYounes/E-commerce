<?php

namespace App\Repositories;

use App\Models\Adv;

class AdvRepository
{
    public function findAdv($id)
    {
        return Adv::findOrFail($id);
    }
}