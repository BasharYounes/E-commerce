<?php

namespace App\Services\Adv;

use App\Models\AdvRead;
use App\Models\Adv;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdQueryService
{

    
public function searchActiveAds(array $filters): LengthAwarePaginator
{
    $query = AdvRead::where('is_active', 1);
    
    if (!empty($filters['description'])) {
        $query->where('description', 'LIKE', '%' . $filters['description'] . '%');
    }
    
    if (!empty($filters['location'])) {
        $location = preg_quote(trim($filters['location']));
        $query->where('location', 'REGEXP', '[[:<:]]' . $location . '[[:>:]]');
    }
    
    if (!empty($filters['category_id'])) {
        $query->where('category_id', $filters['category_id']);
    }
    
    // معالجة الأسعار
    $min_price = $filters['min_price'] ?? null;
    $max_price = $filters['max_price'] ?? null;
    
    if ($min_price && $max_price) {
        $query->whereBetween('price', [min($min_price, $max_price), max($min_price, $max_price)]);
    } elseif ($min_price) {
        $query->where('price', '>=', $min_price);
    } elseif ($max_price) {
        $query->where('price', '<=', $max_price);
    }
    
    return $query->orderBy('views_count', 'desc')
                 ->paginate(15);
}

}