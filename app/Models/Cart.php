<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'checked_out',
        'checked_out_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'checked_out' => 'boolean',
            'checked_out_at' => 'datetime',
        ];
    }

    /**
     * Attributes that should be appended to this model.
     */
    protected $appends = [
        // This is the total price of all the items in cart with quantity included
        'total_price',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products for the cart.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withTimestamps();
    }

    /**
     * Get the total price of the cart.
     */
    public function getTotalPriceAttribute()
    {
        return $this->products->sum->total_price;
    }
}
