let dataMenu = [];

let batasAwal = window.innerWidth >= 1024 ? 6 : 4;
let jumlahTampil = batasAwal;

let kategoriAktif = "All";
let kataKunci = "";

const menuContainer = document.getElementById("menu-container");
const tombolNavigasi = document.getElementById("navigasi");
const tombolKategori = document.querySelectorAll('#filter-container button');
const inputPencarian = document.getElementById("input-pencarian");
const inputKosong = document.getElementById("empty-state");
const loadingState = document.getElementById("loading-state");
const errorState = document.getElementById("error-state");

function renderMenu() {
    let dataSaringan;
    
    if (kategoriAktif === "All") {
        dataSaringan = dataMenu;
    } else {
        dataSaringan = dataMenu.filter(item => item.kategori === kategoriAktif);
    }

    if (kataKunci !== "") {
        dataSaringan = dataSaringan.filter(item => item.nama.toLowerCase().includes(kataKunci.toLowerCase()));
    }

    let dataTampil = dataSaringan.slice(0, jumlahTampil);

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

    if (dataSaringan.length === 0) {
        menuContainer.classList.add('hidden');
        inputKosong.classList.remove('hidden');
        inputKosong.classList.add('flex');
        tombolNavigasi.style.display = 'none';
    } else {
        menuContainer.classList.remove('hidden');
        inputKosong.classList.remove('flex');
        inputKosong.classList.add('hidden');

        if (dataSaringan.length <= batasAwal) {
            tombolNavigasi.style.display = 'none';
        } else {
            tombolNavigasi.style.display = 'block';
            if (jumlahTampil >= dataSaringan.length) {
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

tombolKategori.forEach(tombol => {
    tombol.addEventListener('click', () => {
        kategoriAktif = tombol.dataset.kategori;
        updateGayaTombolAktif(tombol);
        jumlahTampil = batasAwal;
        renderMenu();
    });
});

tombolNavigasi.addEventListener('click', () => {
    let dataSaringan = kategoriAktif === "All" ? dataMenu : dataMenu.filter(item => item.kategori === kategoriAktif);
    if (kataKunci !== "") {
        dataSaringan = dataSaringan.filter(item => item.nama.toLowerCase().includes(kataKunci.toLowerCase()));
    }

    if (jumlahTampil >= dataSaringan.length) {
        jumlahTampil = batasAwal;
    } else {
        jumlahTampil += 4;
    }
    renderMenu();
});

inputPencarian.addEventListener('keyup', (e) => {
    e.preventDefault();
    if (e.key === 'Enter') {
        kataKunci = e.target.value.trim();
        jumlahTampil = batasAwal;
        renderMenu();
    }
});

inputPencarian.addEventListener('input', (e) => {
    if (e.target.value.trim() === "") {
        kataKunci = "";
        jumlahTampil = batasAwal;
        renderMenu();
    }
});

window.addEventListener('resize', () => {
    batasAwal = window.innerWidth >= 1024 ? 6 : 4;
    jumlahTampil = batasAwal;
    renderMenu();
});

async function ambilDataMenu() {
    loadingState.classList.remove('hidden');
    loadingState.classList.add('flex');
    errorState.classList.add('hidden');
    errorState.classList.remove('flex');
    menuContainer.classList.add('hidden');
    tombolNavigasi.style.display = 'none';
    
    try {
        const response = await fetch('../backend/salah.json');
        if (!response.ok) {
            throw new Error("Gagal mengambil data");
        }
        
        dataMenu = await response.json();

        loadingState.classList.add('hidden');
        loadingState.classList.remove('flex');
        renderMenu();
    } catch (error) {
        console.error("Terjadi kesalahan:", error);
        loadingState.classList.add('hidden');
        loadingState.classList.remove('flex');
        errorState.classList.remove('hidden');
        errorState.classList.add('flex');
    }
}

ambilDataMenu();
