<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }
}
