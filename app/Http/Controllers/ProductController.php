<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index(Request $request)
    {
        $products = Product::with('categories');
        $this->filter($request, $products);
        return $products->get();
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function show(Product $product)
    {
        return $product->load('categories');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $params = $request->all();
        $params['attributes'] = json_encode($params['attributes']);
        $product = Product::create($params);

        if ($request->categories) {
            $product->categories()->attach($request->categories);
        }

        return response()->json($product, 201);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $product)
    {
        $params = $request->all();
        $params['attributes'] = json_encode($params['attributes']);

        $product->update($params);

        if ($request->categories) {
            $product->categories()->sync($request->categories);
        }

        return response()->json($product, 201);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(Product $product)
    {
        $product->delete();

        return response('Product has been deleted', 204);
    }

    /**
     * @param Request $request
     * @param $product
     */
    private function filter(Request $request, &$product)
    {
        if ($request->new) {
            $product->new();
        }

        if ($request->popular) {
            $product->popular();
        }

        if ($request->article && strlen($request->article) >= 3) {
            $product->article($request->article);
        }

        if($request->category) {
            $product->whereHas('categories', function ($query) use ($request) {
                $query->where('categories.id', $request->category);
            });
        }
    }
}
