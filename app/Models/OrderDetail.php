<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'detail_line',
        'product_id',
        'price_buy',
        'quantity',
        'shop_id',
        'receiver_id',
        'created_at',
        'updated_at'
    ];
}
