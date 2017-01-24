<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Order;

class OrdersController extends Controller
{
    /**
     * Get a listing of resource
     * @return Response
     */
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
        //return Order::paginate();
        //return Order::all();
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     * @return Response
     */
    public function store()
    {
        // 1. analyze the json file and validate all fields
        // 2. create a new order
        // 3. check whether the item (sku) is in the existing product table
        //    if yes then go to 4
        //    if no then create new product in product table then go to 4
        // 4. check whether the status of the item (sku) is Available in item table
        //    if yes then assign it to this order and change the status to Assigned
        //    if no then create a new item and set the physical status to To order
        //

        $input = Input::json();
        $order = new Order;
        $order->customer_name = $input->get('customer_name');
        $order->address = $input->get('address');
        $order->status = '';
        $order->order_date = Carbon::now();
        $order->save();
        return response($order, 201);
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Order::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
    }

}
