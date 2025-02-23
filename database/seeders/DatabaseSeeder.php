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
      
      
        DB::table('users')->insert([
            'name' => 'A.B. Siddik',
            'email' => 'absiddik517@gmail.com',
            'password' => Hash::make('password'),
        ]);
        
        $this->call([
            SettingSeeder::class,
            ClassSeeder::class,
            GroupSeeder::class,
            SubjectSeeder::class,
        ]);
        
        
    }
}