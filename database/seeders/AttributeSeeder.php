<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        // Adicionar 10 adicionais/ingredientes
        $attributes = [
            ['name' => 'Queijo Cheddar'],
            ['name' => 'Bacon'],
            ['name' => 'Cebola Caramelizada'],
            ['name' => 'Alface'],
            ['name' => 'Tomate'],
            ['name' => 'Picles'],
            ['name' => 'Maionese Especial'],
            ['name' => 'Molho Barbecue'],
            ['name' => 'Pão Australiano'],
            ['name' => 'Ovo Frito'],
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }
    }
}
