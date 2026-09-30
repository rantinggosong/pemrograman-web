const dataMenu = [
    { id: 1, nama: "Cappuccino", gambar: "https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=500&q=80", harga: 25000, kategori: "Minuman" },
    { id: 2, nama: "Americano", gambar: "https://images.unsplash.com/photo-1551030173-122aabc4489c?w=500&q=80", harga: 20000, kategori: "Minuman" },
    { id: 3, nama: "Espresso", gambar: "https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?w=500&q=80", harga: 18000, kategori: "Minuman" },
    { id: 4, nama: "Latte Art", gambar: "https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=500&q=80", harga: 28000, kategori: "Minuman" },
    { id: 5, nama: "Mocha", gambar: "https://images.unsplash.com/photo-1578314675249-a6948ff17628?w=500&q=80", harga: 30000, kategori: "Minuman" },
    { id: 6, nama: "Macchiato", gambar: "https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=500&q=80", harga: 26000, kategori: "Minuman" },
    { id: 7, nama: "Cold Brew", gambar: "https://images.unsplash.com/photo-1461023058943-0708e52e4604?w=500&q=80", harga: 22000, kategori: "Minuman" },
    { id: 8, nama: "Affogato", gambar: "https://images.unsplash.com/photo-1594631252845-29fc4fac8c76?w=500&q=80", harga: 35000, kategori: "Minuman" }
];

let lacakUser = false;
let kategoriAktif = "All";

const menuContainer = document.getElementById("menu-container");
const tombolNavigasi = document.getElementById("navigasi");
const tombolKategori = document.querySelectorAll('#filter-container button');

function renderMenu() {
    let batasAwal = window.innerWidth >= 1024 ? 6 : 4;

    let dataSaringan;
    if (kategoriAktif === "All") {
        dataSaringan = dataMenu;
    } else {
        dataSaringan = dataMenu.filter(item => item.kategori === kategoriAktif);
    }

    let dataTampil;
    if (lacakUser === true) {
        dataTampil = dataSaringan;
    } else {
        dataTampil = dataSaringan.slice(0, batasAwal);
    }


    menuContainer.innerHTML = '';

    dataTampil.forEach(item => {
        const kartuMenu = `
            <div class="flex flex-col bg-white/30 border border-[#1A110A]/10 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow backdrop-blur-sm">
                <div class="bg-[#1A110A] p-2 md:p-4">
                    <div class="w-full aspect-square rounded-xl overflow-hidden shadow-inner">
                        <img src="${item.gambar}" alt="${item.nama}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
                <div class="flex flex-col items-center p-3 md:p-6 text-center">
                    <h3 class="font-serif text-base md:text-2xl font-bold text-[#1A110A] mb-3 md:mb-6 leading-tight">${item.nama}</h3>
                    <button class="w-full py-1.5 px-2 md:py-3 md:px-4 text-xs md:text-base bg-transparent border md:border-2 border-[#1A110A]/30 text-[#1A110A] font-semibold rounded-lg md:rounded-xl hover:bg-[#1A110A] hover:text-[#D5B99F] transition-all duration-300">
                        Order Now (Rp ${item.harga})
                    </button>
                </div>
            </div>
        `;
        menuContainer.insertAdjacentHTML('beforeend', kartuMenu);
    });

    if (dataSaringan.length <= batasAwal) {
        tombolNavigasi.style.display = 'none';
    } else {
        tombolNavigasi.style.display = 'block';
        if (lacakUser === true) {
            tombolNavigasi.textContent = "Tutup Kembali";
        } else {
            tombolNavigasi.textContent = "Lihat Lebih Banyak";
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

tombolKategori.forEach(tombol => {
    tombol.addEventListener('click', () => {
        kategoriAktif = tombol.dataset.kategori;
        updateGayaTombolAktif(tombol);
        lacakUser = false;
        renderMenu();
    });
});

tombolNavigasi.addEventListener('click', () => {
    lacakUser = !lacakUser;
    renderMenu();
});

window.addEventListener('resize', () => {
    renderMenu();
});

renderMenu();
