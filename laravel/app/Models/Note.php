<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['title', 'text', 'user_id', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

