<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function index()
    {

        $products = Product::all();
        return $this->successResponse($products);
    }
  

    /**
     * It takes a product id, finds the product in the database, and returns the product as a JSON
     * response
     * 
     * @param product The name of the parameter that will be passed to the method.
     * 
     * @return The product with the id of 
     */
    public function show($product)
    {
        $product = Product::findOrFail($product);
        return $this->successResponse($product);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:1|max:200'
        ];

        $this->validate($request, $rules);
        $product = Product::create($request->all());
        return $this->successResponse($product);
    }

    public function update(Request $request, $product)
    {
        $rules = [
            'name' => 'max:255',
            'price' => 'numeric|min:1|max:200'
        ];

        $this->validate($request, $rules);
        $product = Product::findOrFail($product);
        $product = $product->fill($request->all());

        if ($product->isClean()) {
            return $this->errorResponse(
                'at least one value must be change',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $product->save();
        return $this->successResponse($product);
    }


    public function destroy($product)
    {

        $product = Product::findOrFail($product);
        $product->delete();
        return $this->successResponse($product);
    }
}
