<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_name',
        'qty',
        'thumbnail',
        'price',
        'sub_total'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
