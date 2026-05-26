<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $table = DB::table('countries');

        $table->delete();

        $now = now();

        $table->insert([
            [
                'id' => 1,
                'title_ar' => 'البحرين',
                'title_en' => 'Bahrain',
                'currancy_code_ar' => 'دينار بحريني',
                'currancy_code_en' => 'BD',
                'currancy_value' => 1.000,
                'phone_code' => '+973',
                'country_code' => 'BH',
                'length' => 8,
                'decimals' => 3,
                'lat' => '25.93041400',
                'long' => '50.63777200',
                'status' => 1,
                'image' => '/countries/Bahrain.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'title_ar' => 'المملكة العربية السعودية',
                'title_en' => 'Saudi Arabia',
                'currancy_code_ar' => 'ريال سعودي',
                'currancy_code_en' => 'SR',
                'currancy_value' => 10.000,
                'phone_code' => '+966',
                'country_code' => 'SA',
                'length' => 9,
                'decimals' => 2,
                'lat' => '23.88594200',
                'long' => '45.07916200',
                'status' => 1,
                'image' => '/countries/SaudiArabia.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'title_ar' => 'سلطنة عمان',
                'title_en' => 'Oman',
                'currancy_code_ar' => 'ريال عماني',
                'currancy_code_en' => 'OR',
                'currancy_value' => 1.020,
                'phone_code' => '+968',
                'country_code' => 'OM',
                'length' => 8,
                'decimals' => 3,
                'lat' => '21.51258300',
                'long' => '55.92325500',
                'status' => 1,
                'image' => '/countries/Oman.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'title_ar' => 'الإمارات العربية المتحدة',
                'title_en' => 'United Arab Emirates',
                'currancy_code_ar' => 'درهم إماراتي',
                'currancy_code_en' => 'AED',
                'currancy_value' => 10.000,
                'phone_code' => '+971',
                'country_code' => 'AE',
                'length' => 9,
                'decimals' => 3,
                'lat' => '23.42407600',
                'long' => '53.84781800',
                'status' => 1,
                'image' => '/countries/UnitedArabEmirates.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'title_ar' => 'قطر',
                'title_en' => 'Qatar',
                'currancy_code_ar' => 'ريال قطري',
                'currancy_code_en' => 'QR',
                'currancy_value' => 10.000,
                'phone_code' => '+974',
                'country_code' => 'QA',
                'length' => 8,
                'decimals' => 3,
                'lat' => '25.35482600',
                'long' => '51.18388400',
                'status' => 1,
                'image' => '/countries/Qatar.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'title_ar' => 'الكويت',
                'title_en' => 'Kuwait',
                'currancy_code_ar' => 'دينار كويتي',
                'currancy_code_en' => 'KWD',
                'currancy_value' => 0.810,
                'phone_code' => '+965',
                'country_code' => 'KW',
                'length' => 8,
                'decimals' => 3,
                'lat' => '29.31166000',
                'long' => '47.48176600',
                'status' => 1,
                'image' => '/countries/Kuwait.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'title_ar' => 'الولايات المتحدة',
                'title_en' => 'United States',
                'currancy_code_ar' => 'دولار امريكي',
                'currancy_code_en' => 'USD',
                'currancy_value' => 2.650,
                'phone_code' => '+1',
                'country_code' => 'US',
                'length' => 10,
                'decimals' => 2,
                'lat' => '37.0902',
                'long' => '95.7129',
                'status' => 1,
                'image' => '/countries/US.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'title_ar' => 'مصر',
                'title_en' => 'Egypt',
                'currancy_code_ar' => 'جنيه مصري',
                'currancy_code_en' => 'EG',
                'currancy_value' => 2.650,
                'phone_code' => '+20',
                'country_code' => 'EG',
                'length' => 10,
                'decimals' => 2,
                'lat' => '37.0902',
                'long' => '95.7129',
                'status' => 0,
                'image' => '/countries/eg.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
