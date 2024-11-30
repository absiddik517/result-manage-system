<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
        [
          "key" => "institute",
          "value" => json_encode([
            "name" => "সেন্ট ক্যাথারিনা প্রথমিক ও নিম্ন মাধ্যমিক বিদ্যালয়",
            "short_name" => "SCRLSS",
            "line_1" => "স্থাপিত: ১৯৮৫",
            "line_2" => "ঝলঝলিয়া, বাঘাইতল, হালুয়াঘাট, ময়মনসিংহ",
            "line_3" => "",
            "line_4" => "",
          ]),
        ],
        ["key" => "logo", "value" => "uploads/default.png"],
        ["key" => "pass_mark", "value" => "33"],
        ["key" => "admit_template", "value" => "simple"],
      ];
      \DB::table('settings')->insert($settings);
    }
}
