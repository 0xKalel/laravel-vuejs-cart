<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemovedItem extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'session_id', 'product_id', 'cart_id', 'ip_address', 'user_agent', 'last_activity', 'quantity', 'removed_at'];

    protected $dates = [
        'last_activity',
        'removed_at',
        'created_at',
        'updated_at',
    ];
    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Cart model
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Relationship with Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}