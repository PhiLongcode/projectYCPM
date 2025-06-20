<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'parent', 'status', 'slug', 'updated_at', 'created_at'];

    function products(){
        return $this->hasMany(Product::class);
    }

}
