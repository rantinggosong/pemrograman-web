<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'nama_kategori' => 'Minuman',
                'slug' => 'minuman',
            ],
            [
                'id' => 2,
                'nama_kategori' => 'Makanan',
                'slug' => 'makanan',
            ],
            [
                'id' => 3,
                'nama_kategori' => 'Snack',
                'slug' => 'snack',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['id' => $category['id']], $category);
        }
    }
}
