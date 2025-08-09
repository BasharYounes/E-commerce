<?php

namespace App\Http\Controllers;

use App\Models\Adv;
use App\Services\Adv\AdQueryService;
use App\Repositories\AdvRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RecommendedController extends Controller
{
    use ApiResponse;

    public function __construct(
        public AdQueryService $queryService,
        public AdvRepository $advrepository,
    ) {}

    public function index(Request $request)
    {
        $limitParam = (int) $request->get('limit', 10);
        $limit = max(1, min(50, $limitParam));

        $ads = Adv::with('user:id,name', 'category:id,name')
            ->where('is_active', 1)
            ->orderBy('interactions_count', 'desc')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();

        if (auth()->check()) {
            $ads = $ads->map(function (Adv $ad) {
                return $this->queryService->is_actionUser($ad);
            });
        }

        return $this->success('success', $ads);
    }


        public function getRecommendationsForUser($id): array
    {
        $adv = $this->advrepository->findAdv($id);

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

        return $group1
            ->merge($group2)
            ->merge($group3)
            ->merge($group4)
            ->unique('id')
            ->take(10)
            ->values()
            ->all();
    }

    public function getUserPreferredCategories(User $user): Collection
    {
        return DB::table('favorites')
            ->join('advs', 'favorites.adv_id', '=', 'advs.id')
            ->select('advs.category_id', DB::raw('COUNT(*) as total'))
            ->where('favorites.user_id', $user->id)
            ->groupBy('advs.category_id')
            ->orderByDesc('total')
            ->take(3)
            ->pluck('category_id');
    }

    public function getRecommendedForUser(User $user): Collection
    {
        $categories = $this->getUserPreferredCategories($user);

        return Adv::query()
            ->whereIn('category_id', $categories)
            ->latest()
            ->take(10)
            ->get();
    }







    // public function test(Request $request)
    // {
    //     $text = $request->input('text');

    //     $keywords = collect(explode(' ', $text))
    //     ->filter(fn($word) => mb_strlen($word) > 2)
    //     ->take(5);

    // $group4 = Adv::query()
    //     // ->where('id', '!=', $adv->id)
    //     // ->where('category_id', $categoryId)
    //     ->where(function ($query) use ($keywords) {
    //         foreach ($keywords as $word) {
    //             $query->orWhere('description', 'LIKE', "%{$word}%");
    //         }
    //     })
    //     ->select('*')
    //     ->selectRaw(
    //         '(' . collect($keywords)->map(function($word) {
    //             return "CASE WHEN description LIKE '%{$word}%' THEN 1 ELSE 0 END";
    //         })->implode(' + ') . ') as match_score'
    //     )
    //     ->orderByDesc('match_score')
    //     ->latest()
    //     ->take(5)
    //     ->get();

    //     return response()->json(["results"=>$group4,"keywords"=>$keywords]);
    // }
}


