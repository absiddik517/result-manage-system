<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      //\App\Models\Student::factory(20)->create();
        //\App\Models\User::factory(100)->create();
        //\App\Models\Auth\Role::factory(100)->create();
        
        DB::table('users')->insert([
            'name' => 'Siddik',
            'email' => 'absiddik517@gmail.com',
            'password' => Hash::make('password'),
        ]);
        
        $this->call([
            ClassSeeder::class,
            GroupSeeder::class,
            SubjectSeeder::class,
            // Add other seeders here as needed
        ]);
        
        
    }
}