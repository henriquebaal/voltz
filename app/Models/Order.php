<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Definir os campos que podem ser preenchidos via formulário
    protected $fillable = ['user_id', 'status', 'total', 'order_number', 'payment_method', 'delivery_address', 'rating'];

    // Definir a relação com a tabela de usuários (um pedido pertence a um usuário)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Definir a relação com os itens do pedido (um pedido pode ter vários itens)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
