<?php

namespace App\Http\Services;

class ItemService
{
    /**
     * @param $request
     * @param $item
     */
    public function save($request, $item)
    {
        //$input = Input::json();

        $item->product_id = $request->product_id;
        $item->status = $request->status;
        $item->order_id = $request->order_id ? $request->order_id : null;
        $item->physical_status = $request->physical_status;
        $item->save();
    }

}