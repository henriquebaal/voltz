<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'item_name', 'quantity', 'price'
    ];

    // Relação muitos-para-muitos entre itens de pedido e atributos (ingredientes)
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_order_item', 'order_item_id', 'attribute_id');
    }
}
