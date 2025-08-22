<?php

namespace App\Http\Controllers;


use App\Events\GenericNotificationEvent;
use App\Traits\ApiResponse;

use App\Services\Adv\AdCommandService;
use App\Services\Adv\AdQueryService;
use App\Services\Favorites\FavoriteCommandService;
use App\Services\Likes\LikeAnAdvCommandService;

use App\Http\Requests\Adv\StoreAdRequest;
use App\Http\Requests\Adv\SearchAdRequest;
use App\Http\Requests\Adv\UpdateAdRequest;
use App\Http\Requests\AddLikeToAdvRequest;

use App\Repositories\AdvRepository;

use App\Models\Adv;
use App\Models\UserActivities;

use App\Repositories\FavoriteRepository;
use App\Repositories\LikeRepository;


use Carbon\Carbon;

class AdvController extends Controller
{
    use ApiResponse;
    public function __construct(
        public AdCommandService $commandService,
        public AdQueryService $queryService,
        public AdvRepository $advRepository,
        public LikeAnAdvCommandService $likeCommandService,
        public LikeRepository $likeRepository,
        public FavoriteCommandService $favoriteCommandService,
        public FavoriteRepository $favoriteRepository
    ) {}

    public function index()
    {
        return Adv::with('user:id,name', 'category:id,name')->get();
    }

    public function userAdvs()
    {
        $user = auth()->user();
        $advs = Adv::with('category:name','user:id,name')->where('user_id',$user->id)->get();
        return $this->success('Advs for ' . $user->name . ' are:', $advs);
    }

    public function store(StoreAdRequest $request)
    {
        $ad = $this->commandService->createAd($request);
         event(new GenericNotificationEvent(
            user:auth()->user(),
            type:'create Adv',
            data:[]
        ));
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
        // Pass the full request so file uploads are handled and to avoid only() on array
        $updatedAd = $this->commandService->updateAd($ad, $request);
         event(new GenericNotificationEvent(
            user:auth()->user(),
            type:'Update Adv',
            data:[]
        ));
        return $this->success('تم تعديل الإعلان بنجاح',$updatedAd);
    }

    public function showVisitor($id)
    {
        $ad = Adv::with('category', 'user')->findOrFail($id);
        
        $createdAt = $ad->created_at ? Carbon::parse($ad->created_at) : Carbon::now();
        $published_duration = Carbon::now()->diffInDays($createdAt, false); 
        $published_duration = abs((int) $published_duration); 

        return $this->success('',[$ad,$published_duration]);
    }

    public function showUser($id)
    {
        $ad = Adv::with('category', 'user')->findOrFail($id);
        
        $createdAt = $ad->created_at ? Carbon::parse($ad->created_at) : Carbon::now();
        $published_duration = Carbon::now()->diffInDays($createdAt, false); 
        $published_duration = abs((int) $published_duration); 

        if (!UserActivities::where('user_id', auth()->id())->where('activity_type', 'view')->where('adv_id', $ad->id)->exists()) {
            $this->likeCommandService->addView($ad);

            $viewsCount = $ad->views_count + 1;

            $ad = $this->commandService->updateAd($ad, ['views_count' => $viewsCount]);
        }

        $adv = $this->queryService->is_actionUser($ad);

        $categoryId = $adv->category_id;
        $price = $adv->price;

        $group1 = Adv::query()
            ->where('id', '!=', $adv->id)
            ->where('category_id', $categoryId)
            ->latest()
            ->take(5)
            ->get();

        $group2 = Adv::query()
            ->where('id', '!=', $adv->id)
            ->where('category_id', $categoryId)
            ->whereBetween('price', [$price * 0.8, $price * 1.2])
            ->latest()
            ->take(5)
            ->get();

        $group3 = Adv::query()
            ->where('id', '!=', $adv->id)
            ->where('category_id', $categoryId)
            ->latest()
            ->take(5)
            ->get();

        $keywords = collect(explode(' ', $adv->description))
            ->filter(fn($word) => mb_strlen($word) > 2)
            ->take(5);

        $group4 = Adv::query()
            ->where('id', '!=', $adv->id)
            ->where('category_id', $categoryId)
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->orWhere('description', 'LIKE', "%{$word}%");
                }
            })
            ->select('*')
            ->selectRaw(
                '(' . collect($keywords)->map(function($word) {
                    return "CASE WHEN description LIKE '%{$word}%' THEN 1 ELSE 0 END";
                })->implode(' + ') . ') as match_score'
            )
            ->orderByDesc('match_score')
            ->latest()
            ->take(5)
            ->get();

        return $this->success("",
         [$adv, 
         $published_duration,
         'Recommends Advs'=>$group1
            ->merge($group2)
            ->merge($group3)
            ->merge($group4)
            ->unique('id')
            ->take(10)
            ->values()
            ->all()
        ]);
    }

    public function destroy($id)
    {
        $ad =  $this->advRepository->findAdv($id);
        $this->commandService->deleteAd($ad);
        event(new GenericNotificationEvent(
            user:auth()->user(),
            type:'Delete Adv',
            data:[]
        ));
        return $this->success('تم حذف الإعلان بنجاح');
    }

    public function addLike(AddLikeToAdvRequest $request)
    {
        $like = $this->likeCommandService->addLike($request->validated());

        $adv = $this->advRepository->findAdv($request['adv_id']);

        $interactionsCount = $adv->interactions_count + 1;

        $this->commandService->updateAd($adv, ['interactions_count' => $interactionsCount]);

        return $this->success('success',$like);
    }

    public function removeLike(AddLikeToAdvRequest $request)
    {

        $like = $this->likeRepository->findLike($request['adv_id']);

        $this->likeCommandService->removeLike($like);

        $adv = $this->advRepository->findAdv($request['adv_id']);

        $interactionsCount = $adv->interactions_count - 1;

        $this->commandService->updateAd($adv, ['interactions_count' => $interactionsCount]);

        return $this->success('success');
    }

    public function addToFavorite(AddLikeToAdvRequest $request)
    {
        $favorite = $this->favoriteCommandService->addToFavorite($request->validated());

        return $this->success('success',$favorite);
    }

    public function removeFromFavorite(AddLikeToAdvRequest $request)
    {

        $favorite = $this->favoriteRepository->findFavorite($request['adv_id']);

        $this->favoriteCommandService->removeFromFavorite($favorite);

        return $this->success('success');
    }

    public function getUserFavorites()
    {
        $user = auth()->user();
        $favorites = $this->favoriteRepository->findFavoritesByUserId($user->id);
        return $this->success('success',$favorites);
    }
}
