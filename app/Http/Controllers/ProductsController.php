<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductsController extends Controller
{
    /**
     * Get a listing of resource
     * @return Response
     */
    public function index()
    {
        $products = Product::all();
        return $products;
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Product::findOrFail($id);
    }

}
