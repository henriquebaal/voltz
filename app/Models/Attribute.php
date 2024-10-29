<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    // Lista de campos que podem ser preenchidos em massa
    protected $fillable = ['name'];

    // Relacionamento muitos-para-muitos com produtos
    public function products()
    {
        return $this->belongsToMany(Product::class, 'attribute_products', 'attribute_id', 'product_id');
    }
}
