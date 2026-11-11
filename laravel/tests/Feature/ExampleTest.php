<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $category = Category::create([
            'nama_kategori' => 'Kopi',
            'slug' => 'kopi',
        ]);

        Menu::create([
            'category_id' => $category->id,
            'nama' => 'Espresso Test',
            'harga' => 15000,
            'status' => 'Tersedia',
            'deskripsi' => 'Test Deskripsi',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Espresso Test');
        $response->assertSee('Kopi');
    }
}
