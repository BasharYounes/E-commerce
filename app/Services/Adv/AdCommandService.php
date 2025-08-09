<?php

namespace App\Services\Adv;

use App\Models\Adv;
use App\Jobs\UpdateAdReadJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdCommandService
{
    public function createAd($request): Adv
    {  
        // Accept either a Request/FormRequest or an array of attributes
        if (is_array($request)) {
            $data = $request;
        } else {
            $data = $request->only(['title', 'description', 'price', 'phone', 'category_id','location']);
        }

        $data['user_id'] = auth()->id();
        
        $ad = DB::transaction(function () use ($data,$request) {
            
            if (!is_array($request)) {
                if ($request->hasFile('image') && $request->file('image')->isValid()) {
                    $filename = time() . '.' . $request->file('image')->extension();
                    $path = Storage::disk('public')->putFileAs(
                        'advs',
                        $request->file('image'),
                        $filename,
                        ['visibility' => 'public']
                    );
                    $data['image'] = $path;
                }
            }
            return Adv::create($data);
        });
        UpdateAdReadJob::dispatch('created', $ad);
        return $ad;
    }

    public function updateAd(Adv $ad, $request): Adv
    {
        // Accept either a Request/FormRequest or an array of attributes
        if (is_array($request)) {
            $data = $request;
        } else {
            $data = $request->only(['title', 'description', 'price', 'phone', 'category_id','location']);
        }

        $updatedAd = DB::transaction(function () use ($ad, $data, $request) {

            if (!is_array($request)) {
                if ($request->hasFile('image') && $request->file('image')->isValid()) {
                    $filename = time() . '.' . $request->file('image')->extension();
                    $path = Storage::disk('public')->putFileAs(
                        'advs',
                        $request->file('image'),
                        $filename,
                        ['visibility' => 'public']
                    );
                    $data['image'] = $path;
                }
            }

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