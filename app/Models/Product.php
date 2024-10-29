<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Lista de campos que podem ser preenchidos em massa
    protected $fillable = [
        'name', 'description', 'price', 'image', 'stock',
    ];

    // Relacionamento muitos-para-muitos com atributos
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_products', 'product_id', 'attribute_id');
    }
}
