<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'category_id', 'due_date', 'completed', 'style_class'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

