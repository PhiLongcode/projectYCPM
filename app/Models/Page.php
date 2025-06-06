<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'status',
        'name_page',
        'slug',
        'user_add',
        'content',
    ];
    public function cat_post()
    {
        return $this->hasMany(Cat_post::class, 'post_cat_id');
    }
}
