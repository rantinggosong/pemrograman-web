let batasAwal = window.innerWidth >= 1024 ? 6 : 4;
let jumlahTampil = batasAwal;

let kategoriAktif = "All";
let kataKunci = "";

const menuContainer = document.getElementById("menu-container");
const tombolNavigasi = document.getElementById("navigasi");
const tombolKategori = document.querySelectorAll('#filter-container button');
const inputPencarian = document.getElementById("input-pencarian");
const inputKosong = document.getElementById("empty-state");

/**
 * filterMenuUI()
 * 
 * Menggantikan renderMenu() yang lama.
 * Tidak lagi membuat ulang HTML — semua kartu sudah dicetak oleh PHP (SSR).
 * Strateginya adalah Show/Hide: iterasi .kartu-menu yang sudah ada di DOM,
 * lalu tampilkan/sembunyikan berdasarkan filter kategori, kata kunci, dan jumlahTampil.
 */
function filterMenuUI() {
    const semuaKartu = document.querySelectorAll('.kartu-menu');
    let jumlahCocok = 0;    // kartu yang lolos filter (sebelum batas tampil)
    let jumlahTampilSudah = 0; // kartu yang sudah ditampilkan

    semuaKartu.forEach(kartu => {
        const kategoriKartu = kartu.dataset.kategori;
        const namaKartu     = kartu.dataset.nama; // sudah lowercase dari PHP

        // Cek filter kategori
        const cocokKategori = (kategoriAktif === 'All') || (kategoriKartu === kategoriAktif);

        // Cek filter kata kunci (case-insensitive, sudah lowercase dari PHP)
        const cocokKataKunci = (kataKunci === '') || namaKartu.includes(kataKunci.toLowerCase());

        if (cocokKategori && cocokKataKunci) {
            jumlahCocok++;

            // Terapkan batas tampil
            if (jumlahTampilSudah < jumlahTampil) {
                kartu.classList.remove('hidden');
                jumlahTampilSudah++;
            } else {
                kartu.classList.add('hidden');
            }
        } else {
            kartu.classList.add('hidden');
        }
    });

    // Logika empty state
    if (jumlahCocok === 0) {
        menuContainer.classList.add('hidden');
        inputKosong.classList.remove('hidden');
        inputKosong.classList.add('flex');
        tombolNavigasi.style.display = 'none';
    } else {
        menuContainer.classList.remove('hidden');
        inputKosong.classList.remove('flex');
        inputKosong.classList.add('hidden');

        // Logika tombol navigasi
        if (jumlahCocok <= batasAwal) {
            tombolNavigasi.style.display = 'none';
        } else {
            tombolNavigasi.style.display = 'block';
            if (jumlahTampil >= jumlahCocok) {
                tombolNavigasi.textContent = "Tutup Kembali";
            } else {
                tombolNavigasi.textContent = "Lihat Lebih Banyak";
            }
        }
    }
}

function updateGayaTombolAktif(tombolDiklik) {
    tombolKategori.forEach(tombol => {
        tombol.classList.remove("font-bold", "border-b-2", "border-[#1A110A]");
        tombol.classList.add("font-medium", "text-[#5C4A3D]");
    });

    tombolDiklik.classList.remove("font-medium", "text-[#5C4A3D]");
    tombolDiklik.classList.add("font-bold", "border-b-2", "border-[#1A110A]");
}

// Event: Filter Kategori
tombolKategori.forEach(tombol => {
    tombol.addEventListener('click', () => {
        kategoriAktif = tombol.dataset.kategori;
        updateGayaTombolAktif(tombol);
        jumlahTampil = batasAwal;
        filterMenuUI();
    });
});

// Event: Tombol Navigasi "Lihat Lebih Banyak / Tutup Kembali"
tombolNavigasi.addEventListener('click', () => {
    // Hitung total kartu yang lolos filter
    const semuaKartu = document.querySelectorAll('.kartu-menu');
    let jumlahCocok = 0;
    semuaKartu.forEach(kartu => {
        const cocokKategori  = (kategoriAktif === 'All') || (kartu.dataset.kategori === kategoriAktif);
        const cocokKataKunci = (kataKunci === '') || kartu.dataset.nama.includes(kataKunci.toLowerCase());
        if (cocokKategori && cocokKataKunci) jumlahCocok++;
    });

    if (jumlahTampil >= jumlahCocok) {
        jumlahTampil = batasAwal;
    } else {
        jumlahTampil += 4;
    }
    filterMenuUI();
});

// Event: Pencarian (Enter)
inputPencarian.addEventListener('keyup', (e) => {
    e.preventDefault();
    if (e.key === 'Enter') {
        kataKunci = e.target.value.trim();
        jumlahTampil = batasAwal;
        filterMenuUI();
    }
});

// Event: Pencarian (hapus input → reset)
inputPencarian.addEventListener('input', (e) => {
    if (e.target.value.trim() === "") {
        kataKunci = "";
        jumlahTampil = batasAwal;
        filterMenuUI();
    }
});

// Event: Resize → sesuaikan batas awal tampil
window.addEventListener('resize', () => {
    batasAwal = window.innerWidth >= 1024 ? 6 : 4;
    jumlahTampil = batasAwal;
    filterMenuUI();
});

// Inisialisasi awal: jalankan filter saat halaman dimuat
// Kartu sudah ada dari PHP SSR, tinggal terapkan batas tampil awal
filterMenuUI();
