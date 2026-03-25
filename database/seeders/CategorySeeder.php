<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['cat_name' => 'Makanan', 'description' => 'Makanan'],
            ['cat_name' => 'Minuman', 'description' => 'Minuman'],
        ];

        DB::table('categories')->insert($categories);
    }
}
