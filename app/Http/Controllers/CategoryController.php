<?php

namespace App\Http\Controllers;

use App\Repositories\Categories\CategoryRepository;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(
     public CategoryRepository $categoryRepository
    ){}
    public function index()
    {
        $categories = $this->categoryRepository->getAllCategory();
        return $this->success('Categories are :',$categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $category = $this->categoryRepository->createCategory($validated);
        return $this->success('Category created successfully',$category);
    }

  
    public function update(Request $request, $id)
    {
        $category = $this->categoryRepository->findCategory($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);
        $this->categoryRepository->updateCategory($category,$validated);
        return $this->success('Category updated successfully',$category); 
    }

    public function destroy($id)
    {
        $category = $this->categoryRepository->findCategory($id);
        $this->categoryRepository->deleteCategory($category);
        return $this->success('Category deleted successfully');
    }

    public function getAdvByCategory($id)
    {
        $advs = $this->categoryRepository->getByCategory($id);
        return $this->success('Advs :',$advs);
    }
} 