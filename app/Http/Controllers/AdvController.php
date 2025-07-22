<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;

use App\Services\Adv\AdCommandService;
use App\Services\Adv\AdQueryService;

use App\Http\Requests\Adv\StoreAdRequest;
use App\Http\Requests\Adv\SearchAdRequest;
use App\Http\Requests\Adv\UpdateAdRequest;

use App\Repositories\AdvRepository;

use App\Models\Adv;

use Carbon\Carbon;

class AdvController extends Controller
{
    use ApiResponse;
    public function __construct(
        public AdCommandService $commandService,
        public AdQueryService $queryService,
        public AdvRepository $advRepository
    ) {}
    
    public function store(StoreAdRequest $request)
    {
        $ad = $this->commandService->createAd($request->validated());
        return $this->success('تم نشر الإعلان بنجاح',$ad);
    }
    
    public function search(SearchAdRequest $request)
    {
        $results = $this->queryService->searchActiveAds($request->validated());
        return $this->success('نتائج البحث',$results);
    }

    public function update(UpdateAdRequest $request,$id)
    {
        $ad =  $this->advRepository->findAdv($id);
        $updatedAd = $this->commandService->updateAd($ad, $request->validated());
        return $this->success('تم تعديل الإعلان بنجاح',$updatedAd);
    }

    public function show($id)
    {
        $ad = Adv::with('category', 'user')->findOrFail($id);
        $createdAt = $ad->created_at ? Carbon::parse($ad->created_at) : Carbon::now();
        $published_duration = Carbon::now()->diffInDays($createdAt, false); // false: يمكن أن تكون سالبة
        $published_duration = abs((int) $published_duration); // دائماً موجبة وعدد صحيح
        $viewsCount = $ad->views_count + 1;
        $adv = $this->commandService->updateAd($ad, ['views_count' => $viewsCount]);
        return $this->success("", [$adv, $published_duration]);
    }

    public function destroy($id)
    {
        $ad =  $this->advRepository->findAdv($id);
        $this->commandService->deleteAd($ad);
        return $this->success('تم حذف الإعلان بنجاح');
    }

}
