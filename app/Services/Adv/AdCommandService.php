<?php

namespace App\Services\Adv;

use App\Models\Adv;
use App\Jobs\UpdateAdReadJob;
use Illuminate\Support\Facades\DB;

class AdCommandService
{
    public function createAd(array $data): Adv
    {
        $data['user_id'] = auth()->id();
        $ad = DB::transaction(function () use ($data) {
            return Adv::create($data);
        });
        UpdateAdReadJob::dispatch('created', $ad);
        return $ad;
    }

    public function updateAd(Adv $ad, array $data): Adv
    {
        $updatedAd = DB::transaction(function () use ($ad, $data) {
            $ad->update($data);
            return $ad;
        });
        UpdateAdReadJob::dispatch('updated', $updatedAd);
        return $updatedAd;
    }
    
    public function deleteAd(Adv $ad): void
    {
        DB::transaction(function () use ($ad) {
            $ad->delete();
        });
        UpdateAdReadJob::dispatch('deleted', $ad);
    }
}