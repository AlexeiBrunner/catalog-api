<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::rootCategories()->get();

        return response()->json($this->createTree($categories), 200);
    }

    /**
     * @param Category $category
     * @return Category
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Category $category)
    {
        $category->update($request->all());

        return response()->json($category, 200);
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Category $category)
    {
        $this->updateTree($category);
        $category->products()->delete();
        $category->delete();

        return response()->json(null, 204);
    }

    /**
     * @param $rootCategories
     * @param array $tree
     * @return array
     */
    private function createTree($rootCategories, $tree = [])
    {
        foreach ($rootCategories as $category) {
            $tree[$category->id] = $category;
            if ($category->categories->count()) {
                $tree[$category->id]['child'] = $this->createTree($category->categories);
            }
        }
        return $tree;
    }

    /**
     * @param Category $category
     */
    private function updateTree($category)
    {
        if ($childCategories = $category->categories) {
            foreach ($childCategories as $childCategory) {
                $childCategory->parent_id = $category->parent_id;
                $childCategory->save();
            }
        }
    }
}
