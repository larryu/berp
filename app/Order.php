<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Item;

class Order extends Model
{
    use Notifiable;

    /**
     * set the mail address that you want to send
     * @return mixed
     */
    public function routeNotificationForMail()
    {
        return config('mail.admin');
    }
    /**
     * order status
     */
    const STS_CANCELLED = 'Cancelled';
    const STS_INPROGRESS = 'In progress';
    const STS_COMPLETED = 'Completed';

    /**
     * get items for the order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
    	return $this->hasMany('App\Item');
    }
}
