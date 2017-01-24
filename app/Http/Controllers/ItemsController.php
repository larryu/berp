<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class ItemsController extends Controller
{
    /**
     * Get a listing of resource
     * @return Response
     */
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }
    /**
     * Get a listing of resource
     * @return Response
     */
    public function available()
    {
//        $items = Item::join('products','items.product_id','=','products.id')
//            ->where('status', '=', 'Available')
//            ->where('physical_status', '<>', 'Delivered')
//            ->wherenull('order_id')
//            ->orderBy('products.sku', 'asc')
//            ->with('product')
//            ->get(['items.*']);
        $items = Item::with('product')->whereRaw('order_id is null and status = ? and physical_status <> ?', ['Available', 'Delivered'])->get();
        return $items;
    }
}
