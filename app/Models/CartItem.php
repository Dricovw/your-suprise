<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    

    protected $table = 'cart_items';
    public $timestamps = false;

    protected $appends = ['final_price'];


 
    protected function casts(): array
    {
        return [
            'cart_id'              => 'string',
            'quantity'             => 'integer',
            'unit_price'          => 'decimal:2',
            'discount_percentage'  => 'decimal:2',
        ];    
    }
    
    public function getFinalPriceAttribute()
    {
        if ($this->discount_percentage) {
            return $this->unit_price - round(($this->unit_price * $this->discount_percentage) / 100, 2);
        } else {
            return $this->unit_price;
        }
    }

    public function ProductInfo()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
