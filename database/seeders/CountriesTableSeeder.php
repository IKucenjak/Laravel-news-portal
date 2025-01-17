<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    public function run()
    {
        $countries = [
            'ae' => 'United Arab Emirates',
            'ar' => 'Argentina',
            'at' => 'Austria',
            'au' => 'Australia',
            'be' => 'Belgium',
            'bg' => 'Bulgaria',
            'br' => 'Brazil',
            'ca' => 'Canada',
            'ch' => 'Switzerland',
            'cn' => 'China',
            'co' => 'Colombia',
            'cz' => 'Czech Republic',
            'de' => 'Germany',
            'eg' => 'Egypt',
            'es' => 'Spain',
            'fr' => 'France',
            'gb' => 'United Kingdom',
            'gr' => 'Greece',
            'hk' => 'Hong Kong',
            'hu' => 'Hungary',
            'id' => 'Indonesia',
            'ie' => 'Ireland',
            'il' => 'Israel',
            'in' => 'India',
            'it' => 'Italy',
            'jp' => 'Japan',
            'kr' => 'South Korea',
            'lt' => 'Lithuania',
            'lv' => 'Latvia',
            'ma' => 'Morocco',
            'mx' => 'Mexico',
            'my' => 'Malaysia',
            'ng' => 'Nigeria',
            'nl' => 'Netherlands',
            'no' => 'Norway',
            'nz' => 'New Zealand',
            'ph' => 'Philippines',
            'pl' => 'Poland',
            'pt' => 'Portugal',
            'ro' => 'Romania',
            'rs' => 'Serbia',
            'ru' => 'Russia',
            'sa' => 'Saudi Arabia',
            'se' => 'Sweden',
            'sg' => 'Singapore',
            'sk' => 'Slovakia',
            'th' => 'Thailand',
            'tr' => 'Turkey',
            'tw' => 'Taiwan',
            'ua' => 'Ukraine',
            'us' => 'United States',
            've' => 'Venezuela',
            'za' => 'South Africa',
        ];

        foreach ($countries as $code => $name) {
            DB::table('countries')->insert([
                'code' => $code,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}