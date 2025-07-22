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
        $query->where('location', 'LIKE', '%' . $filters['location'] . '%');
    }
    
    if (!empty($filters['category'])) {
        // إذا كان لديك حقل اسم التصنيف في adv_reads
        $query->where('category_name', 'LIKE', '%' . $filters['category'] . '%');
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