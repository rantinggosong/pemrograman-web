<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::all();

        $query = Menu::with('category');

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->whereHas('category', function ($builder) use ($request) {
                $builder->where('slug', $request->kategori);
            });
        }

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($builder) use ($keyword) {
                $builder->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        $menus = $query->orderBy('id', 'asc')->get();

        return view('menu.index', [
            'categories' => $categories,
            'menus' => $menus,
            'activeCategory' => $request->get('kategori', 'all'),
            'searchKeyword' => $request->get('search', ''),
        ]);
    }
}
