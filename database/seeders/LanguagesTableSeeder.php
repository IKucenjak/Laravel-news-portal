<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    public function run()
    {
        $languages = [
            'ar' => 'Arabic',
            'de' => 'German',
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
            'he' => 'Hebrew',
            'it' => 'Italian',
            'nl' => 'Dutch',
            'no' => 'Norwegian',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'sv' => 'Swedish',
            'ud' => 'Ukrainian',
            'zh' => 'Chinese',
        ];

        foreach ($languages as $code => $name) {
            DB::table('languages')->insert([
                'code' => $code,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}