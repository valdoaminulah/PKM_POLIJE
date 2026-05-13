<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) { 
    header("Location: ../auth/login.php"); 
    exit; 
}

$id_ketua = $_SESSION['id_user'];

// 1. Cari tim yang diketuai user ini
$stmt_tim = $pdo->prepare("SELECT id_tim, nama_tim FROM data_tim_pkm WHERE id_ketua = :id_k LIMIT 1");
$stmt_tim->execute([':id_k' => $id_ketua]);
$my_team = $stmt_tim->fetch();

$list_pelamar = [];

if ($my_team) {
    // 2. Perbaikan Query: Tambahkan u.khs_image agar data gambar bisa diambil
    $stmt_pelamar = $pdo->prepare("SELECT 
                                        p.*, 
                                        u.nama_lengkap, 
                                        u.nim, 
                                        u.angkatan, 
                                        u.program_studi, 
                                        u.no_whatsapp,
                                        u.khs_image -- PASTIKAN KOLOM INI ADA
                                   FROM pendaftaran_tim p 
                                   JOIN user_mahasiswa u ON p.id_mahasiswa = u.id 
                                   WHERE p.id_tim = :id_t AND p.status_pendaftaran = 'Pending'
                                   ORDER BY p.created_at ASC");
    $stmt_pelamar->execute([':id_t' => $my_team['id_tim']]);
    $list_pelamar = $stmt_pelamar->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekrut Anggota</title>
    <!-- Logo Broser -->
     <link rel="icon" type="image/png" href="../img/logoPolije.png">
    <!-- Logo Broser -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

           #modalKHS.show {
                display: flex !important;
                opacity: 1 !important;
            }
            
            .animate-zoom {
                animation: zoomIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            }
            
            @keyframes zoomIn {
                from { opacity: 0; transform: scale(0.9); }
                to { opacity: 1; transform: scale(1); }
            }

            /* Menghilangkan seleksi teks pada tombol x */
            button:focus { outline: none; }
    </style>
</head>
<body class="bg-slate-50">

    <!-- Navbar -->

            <nav class="bg-white border-b border-gray-100 px-4 py-3 sticky top-0 z-[100]">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="../img/LogoPKM.png" alt="Logo" class="h-10">
                        <div class="h-8 w-[1px] bg-gray-200"></div>
                        <div class="flex flex-col">
                            <span class="font-bold text-blue-900 leading-tight">PKM</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Politeknik Negeri Jember</span>
                        </div>
                    </div>

                    <button id="menu-toggle" class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>

                    <div id="nav-menu" class="hidden md:flex absolute md:relative top-full left-0 w-full md:w-auto bg-white md:bg-transparent px-4 md:px-0 py-6 md:py-0 flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-1 shadow-2xl md:shadow-none border-t md:border-none border-gray-50">
                        
                        <a href="./beranda.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Beranda</a>
                        
                        <a href="./buat_tim.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Buat Tim</a>
                        
                        <a href="./daftar_tim.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Daftar Tim</a>
                        
                        <a href="./rekrut_tim.php" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold text-sm shadow-lg shadow-blue-200 text-center">Rekrut</a>

                        <div class="md:hidden pt-4 border-t border-gray-100 mt-2">
                            <a href="../proses/proses_logout.php" class="flex justify-center items-center space-x-2 bg-red-600 text-white px-5 py-3 rounded-full font-bold text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Log Out</span>
                            </a>
                        </div>
                    </div>

                    <div class="hidden md:block">
                        <a href="../proses/proses_logout.php" class="flex items-center space-x-2 bg-red-600 text-white px-5 py-2 rounded-full font-bold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Log Out</span>
                        </a>
                    </div>
                </div>
            </nav>

<main class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Pendaftar (Rekrutmen)</h1>
        <?php if($my_team): ?>
            <p class="text-gray-500">Seleksi calon anggota tim <span class="text-blue-600 font-semibold"><?= htmlspecialchars($my_team['nama_tim']) ?></span>.</p>
        <?php else: ?>
            <p class="text-red-500 font-medium">Anda belum memiliki tim yang terdaftar.</p>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400">No</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400">Mahasiswa</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400">Detail Akademik</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400">Berkas</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400">Kontak</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 text-center">Keputusan</th>
                    </tr>
                </thead>
                <tbody id="pelamar-tbody" class="divide-y divide-gray-50">
                    <?php if($my_team && count($list_pelamar) > 0): $no=1; foreach($list_pelamar as $row): ?>
                    <tr class="row-pelamar hover:bg-slate-50/50 transition">
                        <td class="px-6 py-5 text-sm font-bold text-gray-800"><?= $no++; ?></td>
                        <td class="px-6 py-5 text-sm font-bold text-gray-800"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td class="px-6 py-5">
                            <p class="text-sm text-gray-600"><?= htmlspecialchars($row['nim']) ?></p>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-bold"><?= htmlspecialchars($row['angkatan']) ?></span>
                                <span class="text-[10px] text-gray-400 font-medium"><?= htmlspecialchars($row['program_studi']) ?></span>
                            </div>
                        </td>
                        
                        <td class="px-6 py-5">
    <?php if (!empty($row['khs_image'])): ?>
        <button type="button" 
                onclick="openModalKHS('../../KHS_image/<?= htmlspecialchars($row['khs_image']) ?>')" 
                class="inline-flex items-center text-blue-600 bg-blue-50 px-3 py-1 rounded-lg text-xs font-bold hover:bg-blue-600 hover:text-white transition cursor-pointer border border-blue-100 shadow-sm active:scale-95">
            <i class="fa-solid fa-file-image mr-1.5"></i> Lihat KHS
        </button>
    <?php else: ?>
        <div class="flex items-center gap-1.5 text-gray-400 italic">
            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
            <span class="text-[10px] font-medium uppercase tracking-tighter">Belum Upload</span>
        </div>
    <?php endif; ?>
</td>

                        <td class="px-6 py-5">
                            <a href="https://wa.me/<?= $row['no_whatsapp'] ?>" target="_blank" class="inline-flex items-center text-green-600 bg-green-50 px-3 py-1 rounded-lg text-xs font-bold hover:bg-green-600 hover:text-white transition">
                                WhatsApp
                            </a>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="../proses/proses_rekrut.php?id=<?= $row['id_pendaftaran'] ?>&aksi=terima" 
                                   onclick="return confirm('Terima anggota ini?')"
                                   class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-700 transition">Terima</a>
                                
                                <a href="../proses/proses_rekrut.php?id=<?= $row['id_pendaftaran'] ?>&aksi=tolak" 
                                   onclick="return confirm('Tolak pendaftar ini?')"
                                   class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-red-600 hover:text-white transition">Tolak</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr id="empty-row">
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400 font-medium">
                            Belum ada pelamar baru.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- MODAL ZOOM KHS FULL SCREEN -->
<div id="modalKHS" class="fixed inset-0 z-[999] bg-black items-center justify-center transition-all duration-300 hidden opacity-0 overflow-hidden" style="display: none;">
    <div class="relative w-screen h-screen flex items-center justify-center p-0">
        
        <!-- TOMBOL CLOSE (Tanpa background bundar, murni karakter silang) -->
        <button type="button" onclick="closeModalKHS()" 
                class="absolute top-5 right-5 text-white/40 hover:text-white text-6xl transition-all z-[1000] cursor-pointer drop-shadow-2xl font-light">
            &times;
        </button>
        
        <!-- Wadah Gambar (Full Screen) -->
        <img id="imgKHS" src="" 
             alt="KHS Mahasiswa" 
             class="w-full h-full object-contain animate-zoom shadow-2xl">

        <!-- Info Bawah (Opsional, dihapus jika ingin benar-benar bersih) -->
        <div class="absolute bottom-6 w-full text-center pointer-events-none">
            <p class="text-white/20 text-[10px] uppercase tracking-[0.5em] font-bold italic">Gunakan tombol X untuk kembali</p>
        </div>
    </div>
</div>

<script>
    function openModalKHS(imgSrc) {
        const modal = document.getElementById('modalKHS');
        const img = document.getElementById('imgKHS');
        
        // Pasang sumber gambar
        img.src = imgSrc;
        
        // Tampilkan modal
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.remove('hidden');
            modal.classList.add('show');
        }, 10);
        
        // Kunci scroll body
        document.body.style.overflow = 'hidden';
    }

    function closeModalKHS() {
        const modal = document.getElementById('modalKHS');
        
        modal.classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            // Kembalikan scroll body
            document.body.style.overflow = 'auto';
        }, 300);
    }

    // Tetap sediakan tombol ESC sebagai standar kenyamanan user (Opsional)
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") {
            closeModalKHS();
        }
    });
</script>

    
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rowsPerPage = 5; // Jumlah pelamar per halaman
    const tableRows = document.querySelectorAll('.row-pelamar');
    const paginationNav = document.getElementById('pagination-nav');
    
    // Hitung total halaman, minimal 1 agar navigasi tetap muncul
    const totalPages = Math.ceil(tableRows.length / rowsPerPage) || 1;
    let currentPage = 1;

    function showPage(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        tableRows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });

        renderPagination();
    }

    function renderPagination() {
        if (!paginationNav) return;
        paginationNav.innerHTML = '';

        // Tombol Prev
        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = 'Prev';
        const isPrevDisabled = currentPage === 1;
        prevBtn.className = `px-4 py-2 rounded-xl font-bold text-sm transition border ${
            isPrevDisabled 
            ? 'text-gray-300 bg-white border-gray-100 cursor-not-allowed' 
            : 'text-blue-600 bg-white border-blue-100 hover:bg-blue-50'
        }`;
        prevBtn.disabled = isPrevDisabled;
        prevBtn.onclick = () => { if(!isPrevDisabled) { currentPage--; showPage(currentPage); } };
        paginationNav.appendChild(prevBtn);

        // Angka Halaman
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            const isActive = i === currentPage;
            btn.className = `w-10 h-10 rounded-xl font-bold text-sm transition border ${
                isActive 
                ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-200' 
                : 'text-gray-400 bg-white border-gray-100 hover:bg-gray-100'
            }`;
            btn.onclick = () => { currentPage = i; showPage(currentPage); };
            paginationNav.appendChild(btn);
        }

        // Tombol Next
        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = 'Next';
        const isNextDisabled = currentPage === totalPages;
        nextBtn.className = `px-4 py-2 rounded-xl font-bold text-sm transition border ${
            isNextDisabled 
            ? 'text-gray-300 bg-white border-gray-100 cursor-not-allowed' 
            : 'text-blue-600 bg-white border-blue-100 hover:bg-blue-50'
        }`;
        nextBtn.disabled = isNextDisabled;
        nextBtn.onclick = () => { if(!isNextDisabled) { currentPage++; showPage(currentPage); } };
        paginationNav.appendChild(nextBtn);
    }

    // Inisialisasi
    showPage(1);
});

// Toggle Navbar
const menuToggle = document.getElementById('menu-toggle');
const navMenu = document.getElementById('nav-menu');
if(menuToggle) {
    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('hidden');
        navMenu.classList.toggle('flex');
    });
}
</script>
    <script>
        // JS Toggle Navbar tetap sama
        const menuToggle = document.getElementById('menu-toggle');
        const navMenu = document.getElementById('nav-menu');
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('hidden');
            navMenu.classList.toggle('flex');
        });
    </script>
</body>
</html>