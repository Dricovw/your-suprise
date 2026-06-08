<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{

    protected $table = 'product';
    public $timestamps = false;

 
    protected function casts(): array
    {
        return [
            'title'                => 'string',
            'description'          => 'string',
            'price'                => 'decimal:2',
            'discount_percentage'  => 'decimal:2',
            'rating'               => 'decimal:2',
            'stock'                => 'integer',
            'brand'                => 'string',
            'sku'                  => 'string',
            'weight'               => 'integer',
            'warrantyInformation'  => 'string',
            'shippingInformation'  => 'string',
            'returnPolicy'         => 'string',
            'minimumOrderQuantity' => 'integer',
            'images'               => 'string',
            'active'               => 'boolean',
        ];    
    }

    public function categoryInfo()
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }

     public function products()
    {
        return $this->hasMany(CartItem::class, 'product_id', 'id');
    }

}