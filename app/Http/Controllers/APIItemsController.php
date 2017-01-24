<?php

namespace App\Http\Controllers;

use App\Http\Services\ItemService;
use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\UpdateItemRequest;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Item;

class APIItemsController extends Controller
{
    private $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }
    /**
     * Get a listing of resource
     * @return Response
     */
    public function available(Request $request)
    {
        $sort = $request->sort;

        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];
        $perPage = $request->per_page;

        $items = Item::with('product')
            ->whereRaw('order_id is null and status = ? and physical_status <> ?', ['Available', 'Delivered'])
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage);
//        $items = Item::join('products','items.product_id','=','products.id')
//            ->where('status', '=', 'Available')
//            ->where('physical_status', '<>', 'Delivered')
//            ->wherenull('order_id')
//            //->orderBy($sortBy, $sortDirection)
//            ->with('product')
//            ->get(['items.*']);

        return $items;
    }

    /**
     * Get a listing of resource
     * @return Response
     */
    public function index($orderId, Request $request)
    {
        $sort = $request->sort;

        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];
        $perPage = $request->per_page;

        $order = Order::find($orderId);
        if (!$order instanceof Order)
        {
            // handle error
            $item = new Item();
            $query = $item->orderBy($sortBy, $sortDirection);
        }
        else
        {
            $query = $order->items()->getQuery()->orderBy($sortBy, $sortDirection);
        }

        return $query->with('product')->paginate($perPage);
    }
    /**
     * Store a newly created resource in storage.
     * @return Response
     */
    public function store(CreateItemRequest $request)
    {
        $item = new Item();

        $this->itemService->save($request, $item);

        return response(null, Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateItemRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(UpdateItemRequest $request, $id)
    {
        $item = Item::findOrFail($id);

        $this->itemService->save($request, $item);

        return response(null, Response::HTTP_CREATED);
    }
    /**
     * attach the item to the order
     * then change its status to Assigned
     *
     * @param UpdateItemRequest $request
     * @param  int $id
     * @return Response
     */
    public function add(Request $request)
    {
        /**
         * find the item then update
         */
        $orderId = $request->order_id;
        $itemId = $request->id;
        $item = Item::findOrFail($itemId);
        $item->order_id = $orderId;
        $item->status = Item::STS_ASSIGNED;
        $item->save();
        return response(null, Response::HTTP_CREATED);
    }
    /**
     * de-attach the item from the order
     * then change its status to Available
     * if the physical status is delivered
     * then cannot de-attach it
     *
     * @param UpdateItemRequest $request
     * @param  int $id
     * @return Response
     */
    public function delete(Request $request)
    {
        /**
         * find the item then update
         */
        $orderId = $request->order_id;
        $itemId = $request->id;
        $item = Item::findOrFail($itemId);
        if ($item->physical_status == Item::PHY_STS_DELIVERED)
        {
            return response('Cannot delete the item which has already been delivered! ', Response::HTTP_FORBIDDEN);
        }
        $item->order_id = null;
        $item->status = Item::STS_AVAILABLE;
        $item->save();
        return response(null, Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Item::with('product')->findOrFail($id);
    }



}
