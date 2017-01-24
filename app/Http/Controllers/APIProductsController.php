<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Product;


class APIProductsController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Get a listing of resource
     * @return Response
     */
    public function index(Request $request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;

        $query = Product::orderBy($sortBy, $sortDirection);


        return $query->paginate($perPage);
    }
    /**
     * Store a newly created resource in storage.
     * @return Response
     */
    public function store(CreateProductRequest $request)
    {
        $sku = $request->sku;
        $product = Product::where('sku', '=', $sku)->get();
        if (count($product) > 0 )
        {
            return response('The product with the same SKU has already existed! ', Response::HTTP_FORBIDDEN);
        }
        $product = new Product();
        $this->productService->save($request, $product);

        return response(null, Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateItemRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        // if the sku is changed then need to check if the new sku has already exist.
        $product = Product::findOrFail($id);
        $sku = $request->sku;
        if ($product->sku != $sku)
        {
            $products = Product::where('sku', '=', $sku)->get();
            if (count($products) > 0 )
            {
                return response('The product with the same SKU has already existed! ', Response::HTTP_FORBIDDEN);
            }
        }

        $this->productService->save($request, $product);

        return response(null, Response::HTTP_CREATED);
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::findOrFail($id);
    }
}
