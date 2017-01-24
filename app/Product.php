<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;

class Product extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
