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
        
        $class_subjects = [
          ['class_id' => 1, 'subject_id' => 1, 'group_id' => 0 ],
          ['class_id' => 1, 'subject_id' => 4, 'group_id' => 0 ],
          ['class_id' => 1, 'subject_id' => 7, 'group_id' => 0 ],
          
          ['class_id' => 2, 'subject_id' => 1, 'group_id' => 0 ],
          ['class_id' => 2, 'subject_id' => 4, 'group_id' => 0 ],
          ['class_id' => 2, 'subject_id' => 7, 'group_id' => 0 ],
          
          ['class_id' => 3, 'subject_id' => 1, 'group_id' => 0 ],
          ['class_id' => 3, 'subject_id' => 4, 'group_id' => 0 ],
          ['class_id' => 3, 'subject_id' => 7, 'group_id' => 0 ],
          ['class_id' => 3, 'subject_id' => 8, 'group_id' => 0 ],
          ['class_id' => 3, 'subject_id' => 12, 'group_id' => 0 ],
          ['class_id' => 3, 'subject_id' => 13, 'group_id' => 0 ],
          
          ['class_id' => 4, 'subject_id' => 1, 'group_id' => 0 ],
          ['class_id' => 4, 'subject_id' => 4, 'group_id' => 0 ],
          ['class_id' => 4, 'subject_id' => 7, 'group_id' => 0 ],
          ['class_id' => 4, 'subject_id' => 8, 'group_id' => 0 ],
          ['class_id' => 4, 'subject_id' => 12, 'group_id' => 0 ],
          ['class_id' => 4, 'subject_id' => 13, 'group_id' => 0 ],
          
          ['class_id' => 5, 'subject_id' => 1, 'group_id' => 0 ],
          ['class_id' => 5, 'subject_id' => 4, 'group_id' => 0 ],
          ['class_id' => 5, 'subject_id' => 7, 'group_id' => 0 ],
          ['class_id' => 5, 'subject_id' => 8, 'group_id' => 0 ],
          ['class_id' => 5, 'subject_id' => 12, 'group_id' => 0 ],
          ['class_id' => 5, 'subject_id' => 13, 'group_id' => 0 ],
          
          ['class_id' => 6, 'subject_id' => 2, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 3, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 5, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 6, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 7, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 8, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 12, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 13, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 16, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 17, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 15, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 14, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 20, 'group_id' => 0 ],
          ['class_id' => 6, 'subject_id' => 32, 'group_id' => 0 ],
          
          ['class_id' => 7, 'subject_id' => 2, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 3, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 5, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 6, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 7, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 8, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 12, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 13, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 16, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 17, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 15, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 14, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 20, 'group_id' => 0 ],
          ['class_id' => 7, 'subject_id' => 32, 'group_id' => 0 ],
          
          ['class_id' => 8, 'subject_id' => 2, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 3, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 5, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 6, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 7, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 8, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 12, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 13, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 16, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 17, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 15, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 14, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 20, 'group_id' => 0 ],
          ['class_id' => 8, 'subject_id' => 32, 'group_id' => 0 ],
          
          ['class_id' => 9, 'subject_id' => 2, 'group_id' => 0 ],
          ['class_id' => 9, 'subject_id' => 3, 'group_id' => 0 ],
          ['class_id' => 9, 'subject_id' => 5, 'group_id' => 0 ],
          ['class_id' => 9, 'subject_id' => 6, 'group_id' => 0 ],
          ['class_id' => 9, 'subject_id' => 7, 'group_id' => 0 ],
          ['class_id' => 9, 'subject_id' => 13, 'group_id' => 0 ],
          ['class_id' => 9, 'subject_id' => 16, 'group_id' => 0 ],
          //Science
          ['class_id' => 9, 'subject_id' => 11, 'group_id' => 1 ],
          ['class_id' => 9, 'subject_id' => 21, 'group_id' => 1 ],
          ['class_id' => 9, 'subject_id' => 22, 'group_id' => 1 ],
          ['class_id' => 9, 'subject_id' => 23, 'group_id' => 1 ],
          ['class_id' => 9, 'subject_id' => 24, 'group_id' => 1 ],
          // Arts
          ['class_id' => 9, 'subject_id' => 9, 'group_id' => 2 ],
          ['class_id' => 9, 'subject_id' => 26, 'group_id' => 2 ],
          ['class_id' => 9, 'subject_id' => 27, 'group_id' => 2 ],
          ['class_id' => 9, 'subject_id' => 28, 'group_id' => 2 ],
          ['class_id' => 9, 'subject_id' => 25, 'group_id' => 2 ],
          // Commerce
          ['class_id' => 9, 'subject_id' => 10, 'group_id' => 3 ],
          ['class_id' => 9, 'subject_id' => 31, 'group_id' => 3 ],
          ['class_id' => 9, 'subject_id' => 29, 'group_id' => 3 ],
          ['class_id' => 9, 'subject_id' => 30, 'group_id' => 3 ],
          ['class_id' => 9, 'subject_id' => 19, 'group_id' => 3 ],
          
          ['class_id' => 10, 'subject_id' => 2, 'group_id' => 0 ],
          ['class_id' => 10, 'subject_id' => 3, 'group_id' => 0 ],
          ['class_id' => 10, 'subject_id' => 5, 'group_id' => 0 ],
          ['class_id' => 10, 'subject_id' => 6, 'group_id' => 0 ],
          ['class_id' => 10, 'subject_id' => 7, 'group_id' => 0 ],
          ['class_id' => 10, 'subject_id' => 13, 'group_id' => 0 ],
          ['class_id' => 10, 'subject_id' => 16, 'group_id' => 0 ],
          //Science
          ['class_id' => 10, 'subject_id' => 11, 'group_id' => 1 ],
          ['class_id' => 10, 'subject_id' => 21, 'group_id' => 1 ],
          ['class_id' => 10, 'subject_id' => 22, 'group_id' => 1 ],
          ['class_id' => 10, 'subject_id' => 23, 'group_id' => 1 ],
          ['class_id' => 10, 'subject_id' => 24, 'group_id' => 1 ],
          // Arts
          ['class_id' => 10, 'subject_id' => 9, 'group_id' => 2 ],
          ['class_id' => 10, 'subject_id' => 26, 'group_id' => 2 ],
          ['class_id' => 10, 'subject_id' => 27, 'group_id' => 2 ],
          ['class_id' => 10, 'subject_id' => 28, 'group_id' => 2 ],
          ['class_id' => 10, 'subject_id' => 25, 'group_id' => 2 ],
          // Commerce
          ['class_id' => 10, 'subject_id' => 10, 'group_id' => 3 ],
          ['class_id' => 10, 'subject_id' => 31, 'group_id' => 3 ],
          ['class_id' => 10, 'subject_id' => 29, 'group_id' => 3 ],
          ['class_id' => 10, 'subject_id' => 30, 'group_id' => 3 ],
          ['class_id' => 10, 'subject_id' => 19, 'group_id' => 3 ],
        ];
        
        

        DB::table('classes')->insert($classes);
        
        DB::table('class_subjects')->insert($class_subjects);
    }
}