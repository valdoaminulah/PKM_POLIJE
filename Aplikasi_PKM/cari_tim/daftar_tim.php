<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['status_login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Query untuk mengambil tim dimana user adalah KETUA atau ANGGOTA yang sudah DITERIMA
$query_str = "
    SELECT t.*, 'Ketua' as posisi 
    FROM data_tim_pkm t 
    WHERE t.id_ketua = :id_u
    UNION
    SELECT t.*, 'Anggota' as posisi 
    FROM data_tim_pkm t
    JOIN pendaftaran_tim p ON t.id_tim = p.id_tim
    WHERE p.id_mahasiswa = :id_u AND p.status_pendaftaran = 'Diterima'
";

$stmt = $pdo->prepare($query_str);
$stmt->execute([':id_u' => $id_user]);
$my_teams = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tim</title>
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
                        
                        <a href="./lowongan.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Lowongan</a>
                        
                        <a href="./status.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Status</a>
                        
                        <a href="./daftar_tim.php" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold text-sm shadow-lg shadow-blue-200 text-center">Daftar Tim</a>

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
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Tim PKM</h1>
            <p class="text-gray-500">Kelola daftar tim yang kamu ikuti atau ketuai.</p>
        </div>
    </div>

    <div class="space-y-4">
        <?php if (count($my_teams) > 0): ?>
            <?php foreach ($my_teams as $tim): ?>
                <?php 
                    // Hitung jumlah anggota yang sudah diterima di tim ini
                    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM pendaftaran_tim WHERE id_tim = :id_t AND status_pendaftaran = 'Diterima'");
                    $stmt_count->execute([':id_t' => $tim['id_tim']]);
                    $jml_anggota = 1 + $stmt_count->fetchColumn(); // +1 untuk ketua
                ?>
                
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:border-blue-300 transition-all flex flex-col md:flex-row items-center gap-6 relative">
                    
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex flex-col items-center justify-center shrink-0 border border-blue-100">
                        <span class="text-blue-600 font-bold text-xs">PKM</span>
                        <span class="text-blue-600 font-extrabold text-lg"><?php echo str_replace('PKM-', '', $tim['kategori_pkm']); ?></span>
                    </div>

                    <div class="flex-1 min-w-0 text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 mb-1">
                            <h3 class="text-lg font-bold text-gray-800 truncate"><?php echo htmlspecialchars($tim['nama_tim']); ?></h3>
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase <?php echo $tim['posisi'] == 'Ketua' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600'; ?>">
                                <?php echo $tim['posisi']; ?>
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 line-clamp-1"><?php echo htmlspecialchars($tim['deskripsi_projek']); ?></p>
                    </div>

                    <div class="shrink-0 px-6 border-x border-gray-100 hidden lg:block text-center">
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Total Anggota</p>
                        <p class="text-gray-700 font-bold"><?php echo $jml_anggota; ?> / 5 <span class="text-[10px] text-gray-400 font-normal">Mahasiswa</span></p>
                    </div>

                    <div class="shrink-0 hidden sm:block">
                        <span class="flex items-center bg-green-50 text-green-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            <?php echo $tim['status_tim']; ?>
                        </span>
                    </div>

                    <div class="flex items-center space-x-2 shrink-0 border-t md:border-none pt-4 md:pt-0 w-full md:w-auto justify-center">
                        <a href="../detail/detail_anggota_tim.php?id=<?php echo $tim['id_tim']; ?>" class="w-full md:w-auto">
                            <button class="w-full md:w-auto bg-slate-50 text-blue-600 px-6 py-2 rounded-xl text-sm font-bold hover:bg-blue-600 hover:text-white transition border border-slate-100">
                                Detail
                            </button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white rounded-3xl p-12 text-center border border-dashed border-gray-200">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-gray-500 font-bold">Belum Ada Tim</h3>
                <p class="text-gray-400 text-sm mb-6">Kamu belum bergabung atau membuat tim PKM manapun.</p>
                <a href="./lowongan.php" class="bg-blue-600 text-white px-6 py-2 rounded-full font-bold text-sm">Cari Lowongan</a>
            </div>
        <?php endif; ?>
    </div>
</main>
    

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