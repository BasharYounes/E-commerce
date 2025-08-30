<?php

namespace App\Repositories\Categories;

use App\Models\Adv;
use App\Models\Category;

class CategoryRepository
{
    public function findCategory($id)
    {
        return Category::findOrFail($id);
    }

     public function getAllCategory()
    {
        return Category::all();
    }

    public function createCategory($data)
    {
        return Category::create($data);
    }

     public function updateCategory(Category $category,$data)
    {
        $category->update($data);
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
    }

     public function getByCategory($id)
    {
        return Adv::where('category_id',$id)->get();
    }
}