<?php

namespace Database\Seeders;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TrainingSeeder extends Seeder
{
    public function run(): void
    {

        $faker = Faker::create();

        foreach(range(1,100) as $index){

        $date = $faker->dateTimeBetween('-1 year','-1 month');

        DB::table('trainings')->insert([
            'training'=>$faker->randomElement(['Push','Pull','Legs']),
            'idUser'=>'2',
            'created_at' => $date,
            'updated_at'=> $date,
        ]);
    }
    
}
}