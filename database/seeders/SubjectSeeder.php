<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            ['id' => 1, 'name' => 'বাংলা', 'short_name' => 'BAN', 'group_id' => null],
            ['id' => 2, 'name' => 'বাংলা ১ম পত্র', 'short_name' => 'BAN1', 'group_id' => null],
            ['id' => 3, 'name' => 'বাংলা ২য় পত্র', 'short_name' => 'BAN2', 'group_id' => null],
            ['id' => 4, 'name' => 'ইংরেজি', 'short_name' => 'ENG', 'group_id' => null],
            ['id' => 5, 'name' => 'ইংরেজি ১ম পত্র', 'short_name' => 'ENG1', 'group_id' => null],
            ['id' => 6, 'name' => 'ইংরেজি ২য় পত্র', 'short_name' => 'ENG2', 'group_id' => null],
            ['id' => 7, 'name' => 'গণিত', 'short_name' => 'MATH', 'group_id' => null],
            ['id' => 8, 'name' => 'সাধারণ বিজ্ঞান', 'short_name' => 'SCI', 'group_id' => null],
            ['id' => 9, 'name' => 'সাধারণ বিজ্ঞান', 'short_name' => 'SCI', 'group_id' => 2],
            ['id' => 10, 'name' => 'সাধারণ বিজ্ঞান', 'short_name' => 'SCI', 'group_id' => 3],
            ['id' => 11, 'name' => 'বাংলাদেশ ও বিশ্বপরিচয়', 'short_name' => 'SOC', 'group_id' => 1],
            ['id' => 12, 'name' => 'বাংলাদেশ ও বিশ্বপরিচয়', 'short_name' => 'SOC', 'group_id' => null],
            ['id' => 13, 'name' => 'ধর্ম ও নৈতিক শিক্ষা', 'short_name' => 'REL', 'group_id' => null],
            ['id' => 14, 'name' => 'চারু ও কারুকলা', 'short_name' => 'ART', 'group_id' => null],
            ['id' => 15, 'name' => 'শারীরিক শিক্ষা এবং স্বাস্থ্য', 'short_name' => 'PE', 'group_id' => null],
            ['id' => 16, 'name' => 'তথ্য ও যোগাযোগ প্রযুক্তি', 'short_name' => 'ICT', 'group_id' => null],
            ['id' => 17, 'name' => 'কৃষি শিক্ষা', 'short_name' => 'AGR', 'group_id' => null],
            ['id' => 18, 'name' => 'কৃষি শিক্ষা', 'short_name' => 'AGR', 'group_id' => 2],
            ['id' => 19, 'name' => 'কৃষি শিক্ষা', 'short_name' => 'AGR', 'group_id' => 3],
            ['id' => 20, 'name' => 'কাজ ও জীবনমুখী শিক্ষা', 'short_name' => 'WLE', 'group_id' => null],
            ['id' => 21, 'name' => 'পদার্থবিজ্ঞান', 'short_name' => 'PHY', 'group_id' => 1],
            ['id' => 22, 'name' => 'রসায়ন', 'short_name' => 'CHEM', 'group_id' => 1],
            ['id' => 23, 'name' => 'জীববিজ্ঞান', 'short_name' => 'BIO', 'group_id' => 1],
            ['id' => 24, 'name' => 'উচ্চতর গণিত', 'short_name' => 'H-MATH', 'group_id' => 1],
            ['id' => 25, 'name' => 'অর্থনীতি', 'short_name' => 'ECO', 'group_id' => 2],
            ['id' => 26, 'name' => 'ইতিহাস', 'short_name' => 'HIST', 'group_id' => 2],
            ['id' => 27, 'name' => 'ভূগোল ও পরিবেশ', 'short_name' => 'GEO', 'group_id' => 2],
            ['id' => 28, 'name' => 'নাগরিক ও নৈতিক শিক্ষা', 'short_name' => 'CIV', 'group_id' => 2],
            ['id' => 29, 'name' => 'হিসাববিজ্ঞান', 'short_name' => 'ACC', 'group_id' => 3],
            ['id' => 30, 'name' => 'ব্যবসায় উদ্যোগ', 'short_name' => 'BUS', 'group_id' => 3],
            ['id' => 31, 'name' => 'ফিন্যান্স ও ব্যাংকিং', 'short_name' => 'FIN', 'group_id' => 3],
            ['id' => 32, 'name' => 'গার্হস্থ্য বিজ্ঞান', 'short_name' => 'GS', 'group_id' => null],
        ];

        DB::table('subjects')->insert($subjects);
    }
}