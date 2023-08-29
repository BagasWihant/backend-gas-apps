<?php

namespace Database\Factories\Produk;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProdukMaster>
 */
class ProdukMasterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kata = $this->faker->word();
        $kata1 = $this->faker->word();
        $kata2 = $this->faker->word();
        $kata3 = $this->faker->word();
        $a = "$kata, $kata1, $kata2, $kata3";
        return [
            'produk_id'=>$this->faker->uuid(),
            'name'=>$this->faker->word(),
            'rating'=>$this->faker->numberBetween(1,5),
            'img'=>$this->faker->image(),
            'harga'=>$this->faker->numberBetween(1000,100000),
            'diskon_harga'=>$this->faker->numberBetween(0,30000),
            'terjual'=>$this->faker->numberBetween(0,3000),
            'key_filter'=>$a,
        ];
    }
}
