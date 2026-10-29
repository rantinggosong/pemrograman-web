<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $menu ? htmlspecialchars($menu->getNama()) . ' — ' : ''; ?>Pesan Makanan & Minuman</title>
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
    <div class="max-w-2xl mx-auto px-6 py-12">
        <a href="index.php" class="inline-flex items-center gap-2 text-sm font-semibold text-[#5C4A3D] hover:text-[#1A110A] transition-colors mb-8">
            ← Kembali ke Menu
        </a>

        <?php if ($menu === null): ?>
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <h1 class="font-serif text-3xl font-bold text-[#1A110A] mb-3">Item Tidak Ditemukan</h1>
                <p class="text-[#5C4A3D]">Item yang kamu cari tidak tersedia atau sudah dihapus.</p>
            </div>
        <?php else: ?>
            <div class="bg-white/30 border border-[#1A110A]/10 rounded-2xl overflow-hidden shadow-xl backdrop-blur-sm">
                <div class="bg-[#1A110A] p-6 relative">
                    <?php if ($menu->isBestSeller()): ?>
                        <span class="absolute top-4 left-4 text-xs font-bold bg-[#D5B99F] text-[#1A110A] px-3 py-1 rounded-full shadow"><?= htmlspecialchars($menu->getStatus()); ?></span>
                    <?php elseif ($menu->isPromo()): ?>
                        <span class="absolute top-4 left-4 text-xs font-bold bg-amber-400 text-[#1A110A] px-3 py-1 rounded-full shadow"><?= htmlspecialchars($menu->getStatus()); ?></span>
                    <?php elseif ($menu->isHabis()): ?>
                        <span class="absolute top-4 left-4 text-xs font-bold bg-red-500 text-white px-3 py-1 rounded-full shadow"><?= htmlspecialchars($menu->getStatus()); ?></span>
                    <?php endif; ?>
                    <div class="w-full aspect-square rounded-xl overflow-hidden shadow-inner">
                        <img src="<?= htmlspecialchars($menu->getGambar()); ?>" alt="<?= htmlspecialchars($menu->getNama()); ?>" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="p-8 text-center">
                    <span class="text-xs font-semibold text-[#5C4A3D] uppercase tracking-widest"><?= htmlspecialchars($menu->getKategori()); ?></span>
                    <h1 class="font-serif text-3xl md:text-4xl font-bold text-[#1A110A] mt-2 mb-6 leading-tight"><?= htmlspecialchars($menu->getNama()); ?></h1>
                    <p class="text-2xl font-bold text-[#1A110A] mb-8"><?= $menu->getHargaFormatted(); ?></p>
                    <?php if ($menu->isHabis()): ?>
                        <button disabled class="w-full py-4 text-base bg-transparent border-2 border-[#1A110A]/30 text-[#1A110A]/50 font-semibold rounded-xl cursor-not-allowed">Habis</button>
                    <?php else: ?>
                        <button class="w-full py-4 text-base bg-transparent border-2 border-[#1A110A]/30 text-[#1A110A] font-semibold rounded-xl hover:bg-[#1A110A] hover:text-[#D5B99F] transition-all duration-300">Order Now</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>