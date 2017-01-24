<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order;
use App\Product;

class Item extends Model
{
    /**
     * item status
     */
    const STS_AVAILABLE = 'Available';
    const STS_ASSIGNED = 'Assigned';
    /**
     * item physical status
     */
    const PHY_STS_ORDER = 'To order';
    const PHY_STS_INWAREHOUSE = 'In warehouse';
    const PHY_STS_DELIVERED = 'Delivered';

    /**
     * get order for the item
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order()
    {
    	return $this->belongsTo('App\Order');
    }

    /**
     * get product for the item
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function($item){
            // 1) check if the item is attached to an order
            $orderId = $item->order_id;
            if ($orderId)
            {
                // to check all the items attached to this order
                // if their statuses are all delivered
            }
            else
            {
                // finished
            }

        });
    }
}
