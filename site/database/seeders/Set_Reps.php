<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class Set_Reps extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach(range(1,5000) as $index){

            DB::table('set_reps')->insert([
                'idTrainings_Exercise'=>$faker->numberBetween(115,1124),
                'num_of_set' => $faker->numberBetween(1,10),
                'num_of_reps' => $faker->numberBetween(5,30),
                'weight' => $faker->numberBetween(10,200),
            ]);
        }
    }
}