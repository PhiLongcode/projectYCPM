<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'price', 'discount', 'status', 'slug', 'updated_at', 'created_at', 'detail', 'desc' , 'thumbnail', 'images', 'category_id'];

    function CategoryProduct(){
        return $this->belongsTo(CategoryProduct::class, 'category_id');
        //category_id la khoa ngoai
    }

}
