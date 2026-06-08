<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    protected $table = 'category';


    protected function casts(): array
    {
        return [
            'slug' => 'string',
            'name' => 'string',
            'url'  => 'string',
        ];
    }

    // A category has many products
    public function products()
    {
        return $this->hasMany(Product::class, 'category', 'id');
    }
}