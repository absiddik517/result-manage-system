<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    public function run()
    {
        $classes = [
            ['name' => 'প্রথম শ্রেণি', 'short_name' => '১ম', 'has_group' => 0],
            ['name' => 'দ্বিতীয় শ্রেণি', 'short_name' => '২য়', 'has_group' => 0],
            ['name' => 'তৃতীয় শ্রেণি', 'short_name' => '৩য়', 'has_group' => 0],
            ['name' => 'চতুর্থ শ্রেণি', 'short_name' => '৪র্থ', 'has_group' => 0],
            ['name' => 'পঞ্চম শ্রেণি', 'short_name' => '৫ম', 'has_group' => 0],
            ['name' => 'ষষ্ঠ শ্রেণি', 'short_name' => '৬ষ্ঠ', 'has_group' => 0],
            ['name' => 'সপ্তম শ্রেণি', 'short_name' => '৭ম', 'has_group' => 0],
            ['name' => 'অষ্টম শ্রেণি', 'short_name' => '৮ম', 'has_group' => 0],
            ['name' => 'নবম শ্রেণি', 'short_name' => '৯ম', 'has_group' => 1],
            ['name' => 'দশম শ্রেণি', 'short_name' => '১০ম', 'has_group' => 1],
        ];

        DB::table('classes')->insert($classes);
    }
}