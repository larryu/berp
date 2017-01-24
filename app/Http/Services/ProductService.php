<?php

namespace App\Http\Services;

use App\Product;

class ProductService
{
    /**
     * @param $request
     * @param $product
     */
    public function save($request, $product)
    {
        //$input = Input::json();

        $product->sku = $request->sku;
        $product->colour = $request->colour ? $request->colour : null;
        $product->save();
    }

}