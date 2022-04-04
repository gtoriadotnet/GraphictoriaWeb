<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    use HasFactory;

    protected $table = 'items';

    function sellingPrices()
    {
        return $this->morphMany('App\Models\Selling', 'item');
    }

    function prices()
    {
        return $this->morphMany('App\Models\Price', 'item');
    }

}
