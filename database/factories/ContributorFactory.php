<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contributor>
 */
class ContributorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $identification="";
    
        for ($i = 0; $i < 13; $i++) {
            $identification.=fake()->randomDigit();
        }

        return [
            'name'=>fake()->name,
            'identification'=>$identification,
            'commercial_name'=>fake()->company,
            'regimen'=>"GENERAL",
            'phone'=>fake()->phoneNumber,
            /**
             * -1: Ilimitado
             * 0: Ningún usuario además del contribuyente
             * >1: Más de un usuario
             */
            'user_limit'=>Arr::random(['-1','0','5']),
            'doc_limit'=>Arr::random(['-1','25','50','100']),
            'estab_limit'=>Arr::random(['-1','0','5'])
        ];
    }
}
