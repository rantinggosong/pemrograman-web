<?php

require_once __DIR__ . '/data/data_menu.php';
require_once __DIR__ . '/helpers/functions.php';

$namaToko = 'Menu';
$sloganToko = 'Pesan langsung dari meja kamu';
$kategoriAktif = $_GET['kategori'] ?? 'All';
$kataKunci = trim($_GET['q'] ?? '');

$menuTampil = saringMenu($daftarMenu, $kategoriAktif, $kataKunci);
$totalItemTampil = hitungTotalMenu($menuTampil);
$daftarKategori = dapatkanDaftarKategori($daftarMenu);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Makanan & Minuman</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'primary-light': '#D5B99F',
              'dark': '#1A110A',
              'gray-500': '#5C4A3D',
              'gray-800': '#2C1E16',
            },
            fontFamily: {
              'sans': ['"Plus Jakarta Sans"', 'sans-serif'],
              'serif': ['"Playfair Display"', 'serif'],
            }
          }
        }
      }
    </script>
</head>
<body class="font-sans text-gray-800 bg-[#D5B99F] antialiased min-h-screen bg-[radial-gradient(circle_at_10%_20%,rgba(255,255,255,0.15)_0%,transparent_40%),radial-gradient(circle_at_90%_80%,rgba(255,255,255,0.15)_0%,transparent_40%)]">
    <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col lg:flex-row gap-12 relative min-h-screen">
        <main class="flex flex-col items-center w-full flex-1">
            <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl font-extrabold text-[#1A110A] mb-2 tracking-tight capitalize"><?= htmlspecialchars($namaToko); ?></h1>
            <p class="text-xs md:text-sm font-medium text-[#5C4A3D] mb-6 md:mb-8 text-center px-4"><?= htmlspecialchars($sloganToko); ?></p>
            
            <form method="GET" action="index.php" class="w-full max-w-2xl mb-6 md:mb-8 relative px-4 md:px-0">
                <?php if ($kategoriAktif !== 'All'): ?>
                    <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategoriAktif); ?>">
                <?php endif; ?>
                <input id="input-pencarian" name="q" value="<?= htmlspecialchars($kataKunci); ?>" type="text" class="w-full px-5 py-3 md:px-6 md:py-4 rounded-full border border-white/40 bg-white/70 text-sm md:text-base text-gray-800 outline-none transition-all shadow-md focus:bg-white focus:border-[#5C4A3D]" placeholder="Cari makanan, minuman...">
            </form>
            
            <div id="filter-container" class="flex gap-5 md:gap-8 mb-8 md:mb-12 overflow-x-auto whitespace-nowrap flex-nowrap md:justify-center text-sm md:text-base px-4 md:px-0 w-full max-w-2xl" style="scrollbar-width: none;">
                <?php foreach ($daftarKategori as $kategori): ?>
                    <?php $isActive = (strtolower($kategori) === strtolower($kategoriAktif)); ?>
                    <a href="?kategori=<?= urlencode($kategori); ?><?= !empty($kataKunci) ? '&q=' . urlencode($kataKunci) : ''; ?>" 
                       class="transition-all pb-1 shrink-0 <?= $isActive ? 'text-[#1A110A] font-bold border-b-2 border-[#1A110A]' : 'text-[#5C4A3D] font-medium hover:text-[#1A110A]'; ?>">
                        <?= htmlspecialchars($kategori); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div id="menu-container" class="<?= $totalItemTampil === 0 ? 'hidden' : 'grid'; ?> grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6 w-full max-w-5xl px-2 md:px-0">
                <?php foreach ($menuTampil as $item): ?>
                    <div class="flex flex-col bg-white/30 border border-[#1A110A]/10 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow backdrop-blur-sm">
                        <div class="bg-[#1A110A] p-2 md:p-4">
                            <div class="w-full aspect-square rounded-xl overflow-hidden shadow-inner">
                                <img src="<?= htmlspecialchars($item['gambar']); ?>" 
                                     alt="<?= htmlspecialchars($item['nama']); ?>" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                            </div>
                        </div>
                        <div class="flex flex-col items-center p-3 md:p-6 text-center">
                            <h3 class="font-serif text-base md:text-2xl font-bold text-[#1A110A] mb-3 md:mb-6 leading-tight"><?= htmlspecialchars($item['nama']); ?></h3>
                            <button class="w-full py-1.5 px-2 md:py-3 md:px-4 text-xs md:text-base bg-transparent border md:border-2 border-[#1A110A]/30 text-[#1A110A] font-semibold rounded-lg md:rounded-xl hover:bg-[#1A110A] hover:text-[#D5B99F] transition-all duration-300">
                                Order Now (<?= formatRupiah($item['harga']); ?>)
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="empty-state" class="<?= $totalItemTampil === 0 ? 'flex' : 'hidden'; ?> flex-col items-center justify-center w-full py-12 text-center">
                <h3 class="font-serif text-xl md:text-2xl font-bold text-[#1A110A] mb-2">Yang kamu cari tidak ditemukan</h3>
                <p class="text-sm md:text-base text-[#5C4A3D]">Pastikan pencarian kamu benar ya..</p>
            </div>
        </main>

        <aside id="cart-ringkasan" class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[calc(100%-48px)] max-w-sm z-50 lg:sticky lg:top-6 lg:transform-none lg:left-auto lg:bottom-auto hidden">
            <div class="flex items-center justify-between bg-[#1A110A] rounded-full p-2 pl-6 shadow-2xl hover:scale-105 transition-transform cursor-pointer">
                <div class="flex items-center text-[#D5B99F]">
                    <span id="count" class="text-sm font-semibold tracking-wide">1 Item</span>
                    <span class="mx-3 text-white/30">|</span>
                    <strong id="total" class="text-lg font-bold tracking-wide">Rp 18.000</strong>
                </div>
                <div class="flex items-center justify-center bg-[#D5B99F] w-12 h-12 rounded-full shadow-inner">
                    <span class="text-[#1A110A] font-bold text-lg">➜</span>
                </div>
            </div>
        </aside>
    </div>

    <script>
    const inputPencarian = document.getElementById("input-pencarian");
    const kartuMenu = document.querySelectorAll("#menu-container > div");
    const emptyState = document.getElementById("empty-state");
    const menuContainer = document.getElementById("menu-container");

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
    </script>
</body>
</html>
