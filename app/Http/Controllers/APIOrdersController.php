<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Order;
use App\Item;
use App\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class APIOrdersController extends Controller
{
    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
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

        $search = $request->search;

        $query = $this->order->orderBy($sortBy, $sortDirection);

        if ($search) {
            $like = "%{$search}%";

            $query = $query->where('name', 'LIKE', $like)
                ->orWhere('email', 'LIKE', $like);
        }

        return $query->paginate($perPage);
    }
    /**
     * Store a newly created resource in storage.
     * This is used for third party importing through post
     * request with json data
     * @return Response
     */
    public function store(Request $request)
    {
        // 1) get json data from request
        // 2) analyze json data to create order
        // 3) search available items in items table with associated sku
        // 4) if the sku in products is not there, create a new product first
        // 5) then create a new item
        // 6) then notify admin that the new product is created
        // 7) request end

        return response(null, Response::HTTP_CREATED);
    }
    /**
     * Store a newly created resource in storage.
     * @return Response
     */
    public function import(Request $request)
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
        $order = $request->input('order');
        $customer = $request->input('order.customer');
        $address = $request->input('order.address');
        $total = $request->input('order.total');
        $orderItems = $request->input('order.items');
        //validation
        if (!$order) return response('order field is invalid', Response::HTTP_BAD_REQUEST);
        if (!$customer) return response('customer field is invalid', Response::HTTP_BAD_REQUEST);
        if (!$orderItems) return response('items field is invalid', Response::HTTP_BAD_REQUEST);
        if (!count($orderItems)) return response('no items found', Response::HTTP_BAD_REQUEST);

        try {
            DB::beginTransaction();
            //create order
            $order = new Order();
            $order->customer_name = $customer;
            $order->address = $address;
            $order->status = Order::STS_INPROGRESS;
            $order->order_date = Carbon::now();
            $order->save();

            foreach($orderItems as $orderItem)
            {
                $sku = $orderItem['sku'];
                $quantity = $orderItem['quantity'];
                if (!$sku) return response('sku field is invalid', Response::HTTP_BAD_REQUEST);
                if (!$quantity) return response('quantity field is invalid', Response::HTTP_BAD_REQUEST);
                // get Product via sku
                $products = Product::where('sku', '=', $sku)->get();
                if (count($products) > 0)
                {
                    // find product in system
                    $product = $products[0];
                }
                else
                {
                    // create a new product
                    $product = new product();
                    $product->sku = $sku;
                    $product->save();

                    // notify admin
                    $order->notify(new \App\Notifications\ProductCreated($product));
                }
                // based on quantity to create item
                for ($index =1; $index<=$quantity; $index++)
                {
                    // search product in items table
                    $items = Item::where('product_id', '=', $product->id)
                        ->wherenull('order_id')
                        ->where('status', '=', Item::STS_AVAILABLE)->get();
                    if (count($items) > 0)
                    {
                        // found

                        $item = $items[0];
                        $item->order_id = $order->id;
                        $item->status = Item::STS_ASSIGNED;
                        $item->save();
                    }
                    else
                    {
                        // create new one to order
                        $item = new Item();
                        $item->order_id = $order->id;
                        $item->product_id = $product->id;
                        $item->status = Item::STS_AVAILABLE;
                        $item->physical_status = Item::PHY_STS_ORDER;
                        $item->save();
                    }
                }

            }
        }
        catch (Exception $ex)
        {
            DB::rollBack();
            return response('Whoops, something is wrong', Response::HTTP_CREATED);
        }

        DB::commit();
        return response('Order is successfully created!', Response::HTTP_CREATED);
    }
}
