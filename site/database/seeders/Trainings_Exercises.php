<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class Trainings_Exercises extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();


        foreach(range(1,1000) as $index){

        $idexercise = $faker->numberBetween(1,182);

        if($idexercise!=[2,17,55,117,137,148,162,171]){
            DB::table('trainings_exercises')->insert([
                'idTraining'=>$faker->numberBetween(19,118),
                'idExercise' => $idexercise,
                'min' => null,
            ]);
        }
        else{
            DB::table('trainings_exercises')->insert([
                'idTraining'=>$faker->numberBetween(19,118),
                'idExercise' => $idexercise,
                'min' => $faker->numberBetween(20,200),
            ]);
        }

        
    }
}
} 