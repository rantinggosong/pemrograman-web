<?php

namespace Tests\Unit;

use App\Models\Menu;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    public function test_menu_harga_formatted_attribute(): void
    {
        $menu = new Menu([
            'harga' => 25000,
        ]);

        $this->assertEquals('Rp 25.000', $menu->harga_formatted);
    }
}

