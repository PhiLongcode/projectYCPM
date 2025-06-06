<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat_post extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'cat_post_name',
        'slug',
        'post_cat_id',
    ];
    public function page()
    {
        return $this->belongsTo(Page::class, 'id');
    }
}
