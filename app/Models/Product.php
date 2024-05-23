<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'discount_price',
        'description'
    ];

    public function colors()
    {
        return $this->hasMany(Color::class, 'product_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'product_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
