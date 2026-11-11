@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 flex flex-col lg:flex-row gap-12 relative min-h-screen">
    <main class="flex flex-col items-center w-full flex-1">
        <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl font-extrabold text-[#1A110A] mb-2 tracking-tight capitalize">Menu</h1>
        <p class="text-xs md:text-sm font-medium text-[#5C4A3D] mb-6 md:mb-8 text-center px-4">Pesan langsung dari meja kamu</p>

        <form method="GET" action="{{ route('menu.index') }}" class="w-full max-w-2xl mb-6 md:mb-8 relative px-4 md:px-0">
            @if($activeCategory && strtolower($activeCategory) !== 'all')
                <input type="hidden" name="kategori" value="{{ $activeCategory }}">
            @endif
            <input 
                id="input-pencarian" 
                name="search" 
                value="{{ $searchKeyword }}" 
                type="text" 
                class="w-full px-5 py-3 md:px-6 md:py-4 rounded-full border border-white/40 bg-white/70 text-sm md:text-base text-gray-800 outline-none transition-all shadow-md focus:bg-white focus:border-[#5C4A3D]" 
                placeholder="Cari makanan, minuman..."
            >
        </form>

        <div id="filter-container" class="flex gap-5 md:gap-8 mb-8 md:mb-12 overflow-x-auto whitespace-nowrap flex-nowrap md:justify-center text-sm md:text-base px-4 md:px-0 w-full max-w-2xl" style="scrollbar-width: none;">
            <a 
                href="{{ route('menu.index', array_filter(['search' => request('search')])) }}" 
                class="transition-all pb-1 shrink-0 {{ strtolower($activeCategory) === 'all' ? 'text-[#1A110A] font-bold border-b-2 border-[#1A110A]' : 'text-[#5C4A3D] font-medium hover:text-[#1A110A]' }}"
            >
                All
            </a>
            @foreach($categories as $category)
                @php
                    $isCategoryActive = (strtolower($activeCategory) === strtolower($category->slug) || strtolower($activeCategory) === strtolower($category->nama_kategori));
                @endphp
                <a 
                    href="{{ route('menu.index', array_filter(['kategori' => $category->slug, 'search' => request('search')])) }}" 
                    class="transition-all pb-1 shrink-0 {{ $isCategoryActive ? 'text-[#1A110A] font-bold border-b-2 border-[#1A110A]' : 'text-[#5C4A3D] font-medium hover:text-[#1A110A]' }}"
                >
                    {{ $category->nama_kategori }}
                </a>
            @endforeach
        </div>

        <div id="menu-container" class="{{ $menus->isEmpty() ? 'hidden' : 'grid' }} grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6 w-full max-w-5xl px-2 md:px-0">
            @foreach($menus as $menu)
                @php
                    $isHabis = strtolower($menu->status) === 'habis';
                    $isPromo = strtolower($menu->status) === 'promo';
                    $isBestSeller = strtolower($menu->status) === 'best seller';
                @endphp
                <div class="flex flex-col bg-white/30 border border-[#1A110A]/10 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow backdrop-blur-sm {{ $isHabis ? 'opacity-60' : '' }}" data-harga="{{ $menu->harga }}" data-nama="{{ $menu->nama }}">
                    <div class="bg-[#1A110A] p-2 md:p-4 relative">
                        @if($isBestSeller)
                            <span class="absolute top-2 left-2 text-[10px] md:text-xs font-bold bg-[#D5B99F] text-[#1A110A] px-2 py-0.5 rounded-full shadow">Best Seller</span>
                        @elseif($isPromo)
                            <span class="absolute top-2 left-2 text-[10px] md:text-xs font-bold bg-amber-400 text-[#1A110A] px-2 py-0.5 rounded-full shadow">Promo</span>
                        @elseif($isHabis)
                            <span class="absolute top-2 left-2 text-[10px] md:text-xs font-bold bg-red-500 text-white px-2 py-0.5 rounded-full shadow">Habis</span>
                        @endif
                        <div class="w-full aspect-square rounded-xl overflow-hidden shadow-inner">
                            <img 
                                src="{{ asset($menu->gambar) }}" 
                                alt="{{ $menu->nama }}" 
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                onerror="this.onerror=null; this.src='{{ asset('asset/images.jpeg') }}';"
                            >
                        </div>
                    </div>
                    <div class="flex flex-col items-center p-3 md:p-6 text-center">
                        <h3 class="font-serif text-base md:text-2xl font-bold text-[#1A110A] mb-3 md:mb-6 leading-tight">{{ $menu->nama }}</h3>
                        <button 
                            type="button"
                            class="tombol-order w-full py-1.5 px-2 md:py-3 md:px-4 text-xs md:text-base bg-transparent border md:border-2 border-[#1A110A]/30 text-[#1A110A] font-semibold rounded-lg md:rounded-xl hover:bg-[#1A110A] hover:text-[#D5B99F] transition-all duration-300 {{ $isHabis ? 'disabled cursor-not-allowed opacity-50' : '' }}" 
                            {{ $isHabis ? 'disabled' : '' }}
                            data-harga="{{ $menu->harga }}"
                            data-nama="{{ $menu->nama }}"
                        >
                            {{ $isHabis ? 'Habis' : 'Order Now (' . $menu->harga_formatted . ')' }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="empty-state" class="{{ $menus->isEmpty() ? 'flex' : 'hidden' }} flex-col items-center justify-center w-full py-12 text-center">
            <h3 class="font-serif text-xl md:text-2xl font-bold text-[#1A110A] mb-2">Yang kamu cari tidak ditemukan</h3>
            <p class="text-sm md:text-base text-[#5C4A3D]">Pastikan pencarian kamu benar ya..</p>
        </div>
    </main>

    <aside id="cart-ringkasan" class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[calc(100%-48px)] max-w-sm z-50 lg:sticky lg:top-6 lg:transform-none lg:left-auto lg:bottom-auto hidden">
        <div class="flex items-center justify-between bg-[#1A110A] rounded-full p-2 pl-6 shadow-2xl hover:scale-105 transition-transform cursor-pointer">
            <div class="flex items-center text-[#D5B99F]">
                <span id="count" class="text-sm font-semibold tracking-wide">0 Item</span>
                <span class="mx-3 text-white/30">|</span>
                <strong id="total" class="text-lg font-bold tracking-wide">Rp 0</strong>
            </div>
            <div class="flex items-center justify-center bg-[#D5B99F] w-12 h-12 rounded-full shadow-inner">
                <span class="text-[#1A110A] font-bold text-lg">➜</span>
            </div>
        </div>
    </aside>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const inputPencarian = document.getElementById("input-pencarian");
    const kartuMenu = document.querySelectorAll("#menu-container > div");
    const emptyState = document.getElementById("empty-state");
    const menuContainer = document.getElementById("menu-container");
    const cartRingkasan = document.getElementById("cart-ringkasan");
    const countDisplay = document.getElementById("count");
    const totalDisplay = document.getElementById("total");
    const tombolOrder = document.querySelectorAll(".tombol-order");

    let totalPesanan = 0;
    let totalHarga = 0;

    if (inputPencarian) {
        inputPencarian.addEventListener("input", (e) => {
            const keyword = e.target.value.toLowerCase().trim();
            let visibleCount = 0;

            kartuMenu.forEach(kartu => {
                const nama = kartu.querySelector("h3").textContent.toLowerCase();
                if (nama.includes(keyword)) {
                    kartu.classList.remove("hidden");
                    visibleCount++;
                } else {
                    kartu.classList.add("hidden");
                }
            });

            if (visibleCount === 0) {
                menuContainer.classList.add("hidden");
                emptyState.classList.remove("hidden");
                emptyState.classList.add("flex");
            } else {
                menuContainer.classList.remove("hidden");
                emptyState.classList.remove("flex");
                emptyState.classList.add("hidden");
            }
        });
    }

    tombolOrder.forEach(btn => {
        btn.addEventListener("click", () => {
            if (btn.hasAttribute("disabled")) return;
            const harga = parseInt(btn.dataset.harga, 10) || 0;
            totalPesanan++;
            totalHarga += harga;

            countDisplay.textContent = totalPesanan + " Item";
            totalDisplay.textContent = "Rp " + totalHarga.toLocaleString("id-ID");

            cartRingkasan.classList.remove("hidden");
        });
    });
});
</script>
@endsection
