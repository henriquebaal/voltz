<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word . ' Burger', // Nome fictício do hambúrguer
            'description' => $this->faker->sentence, // Descrição fictícia
            'price' => $this->faker->randomFloat(2, 5, 30), // Preço entre 5 e 30 reais
            'image' => 'https://loremflickr.com/640/480/burger', // Imagem fictícia de hambúrguer
        ];
    }
}
