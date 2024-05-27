<?php

namespace App\Models;

use App\Casts\MoneyCast;
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
        return $this->hasMany(Color::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Product::class)->withPivot('paid_price', 'quantity')->withTimestamps();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => MoneyCast::class,
            'discount_price' => MoneyCast::class,
        ];
    }
}
