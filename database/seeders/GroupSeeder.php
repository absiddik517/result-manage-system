<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    public function run()
    {
        $groups = [
            ['name' => 'বিজ্ঞান'],
            ['name' => 'মানবিক'],
            ['name' => 'ব্যবসায় শিক্ষা'],
        ];

        DB::table('groups')->insert($groups);
    }
}