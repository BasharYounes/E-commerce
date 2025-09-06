<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'سيارات'],
            ['name' => 'عقارات'],
            ['name' => 'أجهزة إلكترونية'],
            ['name' => 'ملابس'],
            ['name' => 'أثاث'],
            ['name' => 'وظائف'],
            ['name' => 'خدمات'],
            ['name' => 'حيوانات'],
            ['name' => 'رياضة'],
            ['name' => 'كتب'],
        ];

        DB::table('categories')->insert($categories);
    }
}
