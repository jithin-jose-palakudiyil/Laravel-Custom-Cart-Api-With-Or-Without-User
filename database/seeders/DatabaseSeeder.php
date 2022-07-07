<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** 
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
    
        //User
        \App\Models\User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@test.com',
             'password'  =>  bcrypt('password'),
        ]);

        //Categories
        \App\Models\Categories::factory(500)->create()->each(function($Category) {
            $randomCategories = \App\Models\Categories::all()->random(rand(1,1))->pluck('id')->first();
            $array  = ['0']; // main Category,  
            array_push($array, $randomCategories);
            shuffle($array);
            $Category->ParentID =$array[0] ;
            $Category->save();
        });
        
        //Products
        \App\Models\Products::factory(5000)->create()
        ->each(function($product) {
            $randomCategories = \App\Models\Categories::all()->random(rand(1,1))->pluck('id')->first();
            $product->category_id =$randomCategories ;
            $product->save();
        });
    }
}
