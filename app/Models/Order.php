<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'fullname',
        'address',
        'email',
        'note',
        'phone',
        'total',
        'payment_method'
    ];
    public function orderDetail()
    {
        return $this->hasMany(Order_detail::class);
    }
}
