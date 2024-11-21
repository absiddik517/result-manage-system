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
            'name' => 'Siddik',
            'email' => 'absiddik517@gmail.com',
            'password' => Hash::make('password'),
        ]);
        
        DB::table('institutes')->insert([
            'name' => 'সেন্ট ক্যাথারিনা প্রথমিক ও নিম্ন মাধ্যমিক বিদ্যালয়',
            'established_at' => 'স্থাপিত: ১৯৮৫',
            'address' => 'ঝলঝলিয়া, বাঘাইতল, হালুয়াঘাট, ময়মনসিংহ',
            'pass_mark' => 33,
            'logo' => null,
        ]);
        
        $this->call([
            ClassSeeder::class,
            GroupSeeder::class,
            SubjectSeeder::class,
            // Add other seeders here as needed
        ]);
        
        
    }
}