<?php
session_start();
require_once "../config/koneksi.php";

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Query yang sudah dibersihkan dari karakter ilegal
$query_str = "SELECT p.*, t.nama_tim, t.kategori_pkm, t.deskripsi_projek, u.nama_lengkap as owner_name
              FROM pendaftaran_tim p
              JOIN data_tim_pkm t ON p.id_tim = t.id_tim
              JOIN user_mahasiswa u ON t.id_ketua = u.id
              WHERE p.id_mahasiswa = :id_user
              AND (
                  p.status_pendaftaran != 'Ditolak' 
                  OR (p.status_pendaftaran = 'Ditolak' AND p.created_at >= NOW() - INTERVAL 30 SECOND)
              )
              ORDER BY p.created_at DESC";

try {
    $stmt = $pdo->prepare($query_str);
    $stmt->execute([':id_user' => $id_user]);
    $daftar_status = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error pada query: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status</title>
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
                        
                        <a href="./status.php" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold text-sm shadow-lg shadow-blue-200 text-center">Status</a>
                        
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
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Status Permintaan Bergabung</h1>
        <p class="text-gray-500">Pantau progres pengajuan bergabung kamu ke tim PKM lain.</p>
    </div>

    <div class="space-y-4">
        <?php if (count($daftar_status) > 0): ?>
            <?php foreach ($daftar_status as $status): ?>
                <?php 
                    // Logika Styling berdasarkan Status
                    $status_text = $status['status_pendaftaran'];
                    $color = "amber"; // Default Pending
                    $icon = '<span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2 animate-pulse"></span>';
                    
                    if ($status_text == 'Diterima') {
                        $color = "green";
                        $icon = '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>';
                    } elseif ($status_text == 'Ditolak') {
                        $color = "red";
                        $icon = '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"></path></svg>';
                    }

                    // Hitung jumlah anggota tim saat ini (Opsional)
                    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM pendaftaran_tim WHERE id_tim = :id_t AND status_pendaftaran = 'Diterima'");
                    $stmt_count->execute([':id_t' => $status['id_tim']]);
                    $total_anggota = 1 + $stmt_count->fetchColumn();
                ?>

                <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden <?php echo ($status_text == 'Ditolak') ? 'opacity-80' : ''; ?>">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-<?php echo $color; ?>-500"></div>
                    
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <h3 class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($status['nama_tim']); ?></h3>
                            <span class="bg-blue-50 text-blue-600 text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-wider">
                                <?php echo htmlspecialchars($status['kategori_pkm']); ?>
                            </span>
                            <span class="bg-<?php echo $color; ?>-50 text-<?php echo $color; ?>-600 text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-widest flex items-center">
                                <?php echo $icon; ?>
                                <?php echo $status_text; ?>
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 line-clamp-1 mb-3"><?php echo htmlspecialchars($status['deskripsi_projek']); ?></p>
                        <div class="flex flex-wrap gap-4 text-xs text-gray-400">
                            <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Owner: <?php echo htmlspecialchars($status['owner_name']); ?></span>
                            <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Anggota: <?php echo $total_anggota; ?> / 5</span>
                        </div>
                    </div>
                    
                    <div class="shrink-0 flex items-center">
                        <?php if ($status_text == 'Pending'): ?>
                            <form action="../proses/proses_batal_request.php" method="POST">
                                <input type="hidden" name="id_pendaftaran" value="<?php echo $status['id_pendaftaran']; ?>">
                                <button type="submit" onclick="return confirm('Batalkan permintaan ini?')" class="text-gray-400 hover:text-red-500 text-sm font-bold px-4 py-2 transition">
                                    Batalkan Permintaan
                                </button>
                            </form>
                        <?php elseif ($status_text == 'Diterima'): ?>
                            <a href="../detail/detail_anggota_tim.php?id=<?php echo $status['id_tim']; ?>" class="bg-green-600 text-white px-6 py-2.5 rounded-2xl text-sm font-bold shadow-lg shadow-green-100 hover:bg-green-700 transition block text-center">
                                Lihat Tim
                            </a>
                        <?php else: ?>
                            <button disabled class="border border-gray-200 text-gray-400 px-6 py-2.5 rounded-2xl text-sm font-bold cursor-not-allowed">
                                Permintaan Ditolak
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white rounded-3xl p-12 text-center border border-dashed border-gray-200">
                <p class="text-gray-400">Kamu belum mengirim permintaan bergabung ke tim manapun.</p>
                <a href="./lowongan.php" class="text-blue-600 font-bold hover:underline mt-2 inline-block">Cari Lowongan Sekarang</a>
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