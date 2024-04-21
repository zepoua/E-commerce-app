<?php

namespace Database\Seeders;

use App\Models\Produit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Créer 30 produits avec des données aléatoires
        for ($i = 0; $i < 10; $i++) {
            Produit::create([
                'titre' => $faker->word,
                'description' => $faker->text,
                'prix' => $faker->numberBetween(500, 10000),
                'quantite' => $faker->numberBetween(10, 100),
                'image1' => 'fgttt',
            ]);
        }
    }
}
