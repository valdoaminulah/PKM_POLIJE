<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user_login = $_SESSION['id_user'];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// LOGIKA PEMBATASAN: Cek pendaftaran aktif
// Sekarang HANYA mengunci jika status 'Pending' atau 'Diterima'.
// Status 'Ditolak' tidak akan masuk hitungan, jadi tombol otomatis terbuka lagi.
$stmt_check_global = $pdo->prepare("SELECT p.id_pendaftaran, t.nama_tim 
                                    FROM pendaftaran_tim p 
                                    JOIN data_tim_pkm t ON p.id_tim = t.id_tim 
                                    WHERE p.id_mahasiswa = :id_u 
                                    AND p.status_pendaftaran IN ('Pending', 'Diterima') 
                                    LIMIT 1");
$stmt_check_global->execute([':id_u' => $id_user_login]);
$pendaftaran_aktif = $stmt_check_global->fetch();

// Tombol Join akan menyala jika tidak ada pendaftaran 'Pending' atau 'Diterima'
$boleh_daftar = !$pendaftaran_aktif; 

// Query utama daftar tim (Dibersihkan dari spasi ilegal)
$query_str = "SELECT t.*, u.nama_lengkap 
              FROM data_tim_pkm t 
              JOIN user_mahasiswa u ON t.id_ketua = u.id 
              WHERE (t.nama_tim LIKE :search OR t.deskripsi_projek LIKE :search)";

if ($kategori != '') {
    $query_str .= " AND t.kategori_pkm = :kategori";
}
$query_str .= " ORDER BY t.created_at DESC";

try {
    $stmt = $pdo->prepare($query_str);
    $stmt->bindValue(':search', '%' . $search . '%');
    if ($kategori != '') {
        $stmt->bindValue(':kategori', $kategori);
    }
    $stmt->execute();
    $daftar_tim = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lowongan Tim</title>
    <!-- Logo Broser -->
     <link rel="icon" type="image/png" href="../img/logoPolije.png">
    <!-- Logo Broser -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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
                        
                        <a href="./lowongan.php" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold text-sm shadow-lg shadow-blue-200 text-center">Lowongan</a>
                        
                        <a href="./status.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Status</a>
                        
                        <a href="./daftar_tim.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Daftar Tim</a>

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

    <!-- Navbar -->





    <main class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header Halaman -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Cari Lowongan Tim PKM</h1>
        <p class="text-gray-500">Temukan tim yang sesuai dengan minatmu dan berkolaborasi bersama.</p>
    </div>

    <!-- Form Pencarian & Filter Kategori -->
    <form method="GET" action="" class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm mb-8 flex flex-col lg:flex-row gap-4 items-center justify-between">
        <div class="relative w-full lg:w-1/2">
            <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Cari Judul Projek atau Nama Tim..." 
                class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all text-sm">
        </div>
        
        <div class="flex items-center space-x-3 w-full lg:w-auto">
            <select name="kategori" class="w-full lg:w-auto pl-4 pr-10 py-3 bg-slate-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-50 outline-none transition-all text-sm appearance-none cursor-pointer font-medium text-gray-600">
                <option value="">Semua Kategori PKM</option>
                <option value="PKM-RE" <?= $kategori == 'PKM-RE' ? 'selected' : ''; ?>>PKM-RE (Riset Eksakta)</option>
                <option value="PKM-RSH" <?= $kategori == 'PKM-RSH' ? 'selected' : ''; ?>>PKM-RSH (Riset Sosial Humaniora)</option>
                <option value="PKM-K" <?= $kategori == 'PKM-K' ? 'selected' : ''; ?>>PKM-K (Kewirausahaan)</option>
                <option value="PKM-PM" <?= $kategori == 'PKM-PM' ? 'selected' : ''; ?>>PKM-PM (Pengabdian Masyarakat)</option>
                <option value="PKM-PI" <?= $kategori == 'PKM-PI' ? 'selected' : ''; ?>>PKM-PI (Penerapan Iptek)</option>
                <option value="PKM-KC" <?= $kategori == 'PKM-KC' ? 'selected' : ''; ?>>PKM-KC (Karsa Cipta)</option>
                <option value="PKM-KI" <?= $kategori == 'PKM-KI' ? 'selected' : ''; ?>>PKM-KI (Karya Inovatif)</option>
                <option value="PKM-VGK" <?= $kategori == 'PKM-VGK' ? 'selected' : ''; ?>>PKM-VGK (Video Gagasan)</option>
                <option value="PKM-GFT" <?= $kategori == 'PKM-GFT' ? 'selected' : ''; ?>>PKM-GFT (Gagasan Futuristik)</option>
                <option value="PKM-AI" <?= $kategori == 'PKM-AI' ? 'selected' : ''; ?>>PKM-AI (Artikel Ilmiah)</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                Cari
            </button>
        </div>
    </form>

    <!-- Daftar Kartu Lowongan -->
    <div id="team-list-container" class="space-y-4 mb-8">
        <?php if (count($daftar_tim) > 0): ?>
            <?php foreach ($daftar_tim as $tim): ?>
                <?php 
                    // 1. Hitung total anggota (Ketua + Anggota Diterima)
                    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM pendaftaran_tim WHERE id_tim = :id_t AND status_pendaftaran = 'Diterima'");
                    $stmt_count->execute([':id_t' => $tim['id_tim']]);
                    $total_saat_ini = 1 + $stmt_count->fetchColumn();
                    $isFull = ($total_saat_ini >= 5 || $tim['status_tim'] == 'Closed');

                    // 2. Cek hubungan USER AKTIF dengan tim ini
                    $stmt_cek_user = $pdo->prepare("SELECT status_pendaftaran FROM pendaftaran_tim WHERE id_tim = :id_t AND id_mahasiswa = :id_u AND status_pendaftaran IN ('Pending', 'Diterima')");
                    $stmt_cek_user->execute([':id_t' => $tim['id_tim'], ':id_u' => $id_user_login]);
                    $relasi_user = $stmt_cek_user->fetch();
                ?>
                
                <!-- Card Tim -->
                <div class="team-card bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:border-blue-200 transition-all flex flex-col md:flex-row gap-6 <?= $isFull ? 'opacity-75' : ''; ?>">
                    
                    <!-- Kiri: Kategori & Kapasitas -->
                    <div class="flex flex-row md:flex-col justify-between md:justify-center items-center md:w-32 shrink-0 border-b md:border-b-0 md:border-r border-gray-50 pb-4 md:pb-0 md:pr-6 gap-2">
                        <div class="bg-blue-50 text-blue-600 px-4 py-2 rounded-2xl text-center">
                            <p class="text-[10px] font-black uppercase tracking-tighter">PKM</p>
                            <p class="text-xl font-extrabold"><?= str_replace('PKM-', '', $tim['kategori_pkm']); ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Kuota</p>
                            <p class="text-sm font-bold <?= $isFull ? 'text-red-500' : 'text-gray-700'; ?>">
                                <?= $total_saat_ini; ?> / 5
                            </p>
                        </div>
                    </div>

                    <!-- Tengah: Konten Projek -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($tim['nama_tim']); ?></h3>
                            <?php if ($isFull): ?>
                                <span class="bg-red-50 text-red-500 text-[10px] px-2 py-0.5 rounded-md font-bold uppercase">Penuh</span>
                            <?php else: ?>
                                <span class="bg-green-50 text-green-600 text-[10px] px-2 py-0.5 rounded-md font-bold uppercase tracking-tighter">Tersedia</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-500 line-clamp-2 mb-4 leading-relaxed">
                            "<?= htmlspecialchars($tim['deskripsi_projek']); ?>"
                        </p>
                        <div class="flex items-center text-xs text-gray-400">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Ketua: <span class="text-gray-600 font-bold ml-1"><?= htmlspecialchars($tim['nama_lengkap']); ?></span>
                        </div>
                    </div>

                    <!-- Kanan: Tombol Aksi -->
                    <div class="flex items-center justify-center shrink-0">
                        <?php if ($tim['id_ketua'] == $id_user_login): ?>
                            <button disabled class="bg-slate-100 text-slate-400 px-8 py-3 rounded-2xl font-bold text-sm">Tim Anda</button>
                        <?php elseif ($relasi_user): ?>
                            <button disabled class="bg-amber-100 text-amber-600 px-8 py-3 rounded-2xl font-bold text-sm">
                                <?= $relasi_user['status_pendaftaran'] == 'Pending' ? 'Menunggu Konfirmasi' : 'Sudah Terdaftar' ?>
                            </button>
                        <?php elseif (!$boleh_daftar): ?>
                            <div class="text-center">
                                <span class="block text-gray-400 text-[10px] mb-1 italic">Terkunci (Sudah ada pengajuan aktif)</span>
                                <button disabled class="bg-gray-200 text-gray-400 px-8 py-3 rounded-2xl font-bold text-sm cursor-not-allowed">Kunci Lowongan</button>
                            </div>
                        <?php elseif ($isFull): ?>
                            <button disabled class="bg-gray-100 text-gray-300 px-8 py-3 rounded-2xl font-bold text-sm uppercase">Full</button>
                        <?php else: ?>
                            <form action="../proses/proses_join_tim.php" method="POST">
                                <input type="hidden" name="id_tim" value="<?= $tim['id_tim']; ?>">
                                <button type="submit" onclick="return confirm('Kirim lamaran bergabung ke tim ini?')" class="bg-blue-600 text-white px-8 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-blue-100 hover:bg-blue-700 transform hover:-translate-y-1 transition-all active:scale-95">
                                    Request Join
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div id="empty-state" class="bg-white rounded-3xl p-16 text-center border border-dashed border-gray-200">
                <p class="text-gray-400 font-medium italic">Tidak ditemukan tim yang sesuai dengan kriteria.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Navigasi Pagination (Akan diisi oleh JS) -->
    <div id="pagination-nav" class="flex justify-center items-center space-x-2 mt-10 mb-10"></div>
</main>



<!-- JAVASCRIPT PAGINATION -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cardsPerPage = 5;
    const teamCards = document.querySelectorAll('.team-card');
    const paginationNav = document.getElementById('pagination-nav');
    
    // Hitung total halaman, minimal 1 agar tetap muncul navigasi
    const totalPages = Math.ceil(teamCards.length / cardsPerPage) || 1;
    let currentPage = 1;

    function showPage(page) {
        const start = (page - 1) * cardsPerPage;
        const end = start + cardsPerPage;

        teamCards.forEach((card, index) => {
            if (index >= start && index < end) {
                card.style.display = 'flex';
                card.style.opacity = '1';
            } else {
                card.style.display = 'none';
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
        prevBtn.onclick = () => { if(!isPrevDisabled) { currentPage--; showPage(currentPage); scrollUp(); } };
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
            btn.onclick = () => { currentPage = i; showPage(currentPage); scrollUp(); };
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
        nextBtn.onclick = () => { if(!isNextDisabled) { currentPage++; showPage(currentPage); scrollUp(); } };
        paginationNav.appendChild(nextBtn);
    }

    function scrollUp() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Jalankan Inisialisasi
    showPage(1);
});

// Toggle Navbar Mobile
const menuToggle = document.getElementById('menu-toggle');
const navMenu = document.getElementById('nav-menu');
if (menuToggle && navMenu) {
    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('hidden');
        navMenu.classList.toggle('flex');
    });
}
</script>


<script>
    function confirmJoin(timName) {
        if (confirm(`Apakah Anda yakin ingin mengirim permintaan bergabung ke tim "${timName}"?`)) {
            alert('Permintaan berhasil dikirim. Tunggu konfirmasi dari Ketua Tim.');
        }
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