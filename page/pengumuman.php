<?php
require_once '../koneksi/koneksi.php';

// 1. Konfigurasi Pagination (Selalu tampilkan 6 data per halaman agar konsisten dengan request sebelumnya)
$limit = 6; 
$page = isset($_GET['halaman']) ? (int)$GET['halaman'] : 1;
$offset = ($page - 1) * $limit;

try {
    // 2. Ambil total data untuk info pagination
    $sql_total = "SELECT COUNT(*) FROM pesan WHERE tujuan_pesan IN ('Semua', 'Mahasiswa')";
    $total_data = $pdo->query($sql_total)->fetchColumn();
    $total_halaman = ceil($total_data / $limit);
    // Jika data 0, total halaman minimal tetap 1 agar pagination muncul
    if ($total_halaman < 1) $total_halaman = 1; 

    // 3. Ambil data pengumuman
    $sql_pesan = "SELECT * FROM pesan 
                  WHERE tujuan_pesan IN ('Semua', 'Mahasiswa') 
                  ORDER BY tgl_kirim DESC 
                  LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($sql_pesan);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $daftar_pengumuman = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

function tgl_indo_singkat($timestamp) {
    return date('d M Y', strtotime($timestamp));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<!-- Logo Broser -->
   <link rel="icon" type="image/png" href="../image/LogoPolije.png">
<!-- Logo Broser -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PENGUMUMAN PKM POLIJE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @media (min-width: 1024px) {
            .group:hover .group-hover\:block { display: block; }
        }
    </style>
</head>
<body class="font-sans antialiased overflow-x-hidden bg-gray-50">
<!-- Navbar -->
<header class="fixed w-full z-50 bg-white shadow-md">
    <div class="container mx-auto px-5 py-0.5 flex items-center justify-between">
        
        <div class="flex items-center shrink-0 space-x-4">
            <img src="../image/LogoPolije.png" alt="Logo Kampus" class="h-8 md:h-10 w-auto object-contain">
            
            <div class="h-8 w-[1px] bg-gray-300"></div>
            
            <img src="../image/logoPKM.png" alt="Logo PKM" class="h-12 md:h-16 w-auto object-contain">
        </div>

        <button id="mobile-menu-button" class="lg:hidden text-gray-700 text-2xl focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>

        <nav id="nav-menu" class="hidden lg:flex flex-col lg:flex-row absolute lg:relative top-full left-0 w-full lg:w-auto bg-white lg:bg-transparent shadow-lg lg:shadow-none items-center space-y-4 lg:space-y-0 lg:space-x-8 p-6 lg:p-0 text-[13px] font-bold text-gray-700 uppercase tracking-wide transition-all">
            <a href="../index.php" class="hover:text-blue-900 transition-colors py-1 w-full text-center lg:w-auto">Beranda</a>
            
            <div class="relative group w-full text-center lg:w-auto">
                <button class="hover:text-blue-900 flex items-center justify-center lg:justify-start w-full lg:w-auto focus:outline-none py-2 lg:py-3 text-[13px] font-bold uppercase tracking-wide">
                    PKM <i class="fas fa-chevron-down ml-2 text-[10px]"></i>
                </button>
                <div class="lg:absolute hidden group-hover:block bg-white lg:shadow-xl border-t-2 border-blue-900 w-full lg:w-48 py-2 lg:-left-4 text-left">
                    <a href="./informasi_pkm.php" class="block px-4 py-2 hover:bg-gray-100 hover:text-blue-900 normal-case font-semibold">Informasi PKM</a>
                    <a href="./jadwal_pkm.php" class="block px-4 py-2 hover:bg-gray-100 hover:text-blue-900 normal-case font-semibold">Jadwal PKM</a>
                </div>
            </div>

            <a href="./informasi_dosen.php" class="hover:text-blue-900 transition-colors py-1 w-full text-center lg:w-auto">Informasi Dosen</a>
            <a href="./kontak_center.php" class="hover:text-blue-900 transition-colors py-1 w-full text-center lg:w-auto">Kontak Center</a>
            
                <?php
                // Pastikan session sudah dimulai di bagian paling atas file
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // 1. Ambil total pesan dari database
                $stmt = $pdo->query("SELECT COUNT(*) FROM pesan WHERE tujuan_pesan IN ('Semua', 'Mahasiswa')");
                $total_db = $stmt->fetchColumn();

                // 2. Ambil jumlah pesan yang terakhir dilihat dari session
                $dilihat = isset($_SESSION['last_viewed_count']) ? $_SESSION['last_viewed_count'] : 0;

                // 3. Hitung selisihnya
                $baru = $total_db - $dilihat;
                ?>

                <!-- Navigasi Pengumuman -->
                <a href="./pengumuman.php" class="relative hover:text-blue-900 transition-colors py-1 inline-block group">
                    Pengumuman
                    
                    <?php if ($baru > 0) : ?>
                        <span class="absolute -top-1.5 -right-3.5 bg-red-600 text-white text-[9px] min-w-[16px] h-[16px] px-1 flex items-center justify-center rounded-full border-2 border-white font-bold leading-none animate-bounce">
                            <?= $baru > 99 ? '99+' : $baru ?>
                        </span>
                    <?php endif; ?>
                </a>

            <div class="border-t lg:border-t-0 lg:border-l w-full lg:w-auto pt-4 lg:pt-0 lg:ml-4 lg:pl-6 border-gray-300 flex justify-center">
                <a href="../auth/login.php" class="flex items-center space-x-2 px-4 py-1.5 border-2 border-blue-900 text-blue-900 rounded-full hover:bg-blue-900 hover:text-white transition-all duration-300 shadow-sm active:scale-95 text-[11px]">
                    <i class="fas fa-user-circle text-base"></i>
                    <span>Log In</span>
                </a>
            </div>
        </nav>
    </div>
</header>
<!-- Navbar -->

<!-- konten 1 -->
<!-- Konten 1 -->
<main class="w-full pt-28 pb-16 px-4 md:px-10 bg-gray-50/50 min-h-screen flex justify-start">
    <div class="max-w-3xl w-full mx-auto md:mx-0"> <!-- mx-0 untuk tetap justify-start sesuai desain -->
        
        <div class="mb-8 border-b border-gray-200 pb-4 text-left">
            <h1 class="text-xl md:text-3xl font-black text-blue-900 uppercase tracking-tighter">
                Pusat Pengumuman
            </h1>
            <p class="text-gray-400 text-[9px] font-bold uppercase tracking-[0.2em]">
                PKM Polije 2026
            </p>
        </div>

        <div class="space-y-4">
            <?php if (!empty($daftar_pengumuman)): ?>
                <?php foreach ($daftar_pengumuman as $row): ?>
                <div class="group bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all text-left">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                            <span class="flex items-center gap-1.5"><i class="fas fa-user text-blue-600"></i> Admin</span>
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <span class="flex items-center gap-1.5"><i class="fas fa-calendar text-gray-400"></i> <?= tgl_indo_singkat($row['tgl_kirim']) ?></span>
                        </div>
                        <div>
                            <h2 class="text-base md:text-lg font-black text-gray-800 group-hover:text-blue-900 transition-colors leading-tight">
                                <?= htmlspecialchars($row['judul_pesan']) ?>
                            </h2>
                            <p class="text-gray-500 text-xs mt-2 line-clamp-2 leading-relaxed text-left">
                                <?= htmlspecialchars($row['isi_pesan']) ?>
                            </p>
                        </div>
                        <a href="detail_pengumuman.php?id=<?= $row['id_pesan'] ?>" class="text-blue-900 font-black text-[9px] uppercase tracking-widest flex items-center gap-2 hover:gap-3 transition-all">
                            Selengkapnya <i class="fas fa-chevron-right text-[7px]"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Tampilan jika data kosong agar layout tidak rusak -->
                <div class="bg-white rounded-2xl p-10 border border-dashed border-slate-300 text-center text-gray-400 text-sm italic">
                    Belum ada pengumuman yang diterbitkan.
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination (Dipastikan Selalu Muncul) -->
        <div class="mt-10 flex items-center gap-2 justify-start">
            <!-- Tombol Prev -->
            <a href="?halaman=<?= max(1, $page - 1) ?>" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-blue-900 transition-all <?= ($page <= 1) ? 'pointer-events-none opacity-40' : '' ?>">
                <i class="fas fa-chevron-left text-[10px]"></i>
            </a>

            <!-- Looping Angka Halaman -->
            <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                <a href="?halaman=<?= $i ?>" class="w-8 h-8 flex items-center justify-center rounded-lg font-black text-[10px] transition-all <?= ($i == $page) ? 'bg-blue-900 text-white shadow-lg' : 'bg-white text-gray-500 border border-gray-200 hover:border-blue-900' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <!-- Tombol Next -->
            <a href="?halaman=<?= min($total_halaman, $page + 1) ?>" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-blue-900 transition-all <?= ($page >= $total_halaman) ? 'pointer-events-none opacity-40' : '' ?>">
                <i class="fas fa-chevron-right text-[10px]"></i>
            </a>
        </div>

    </div>
</main>
<!-- konten 2 -->





<!-- Footer -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<footer class="mt-16 bg-blue-900 pt-12 pb-8 px-6 border-t-4 border-blue-950">
  <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 items-start">
    
    <div class="flex flex-col gap-4">
      <div class="flex items-center gap-3 bg-white p-2 rounded-lg w-fit shadow-md">
        <img src="../image/logoPKM.png" alt="Logo PKM" class="h-10 w-auto object-contain">
        <div class="h-8 w-[1px] bg-gray-300"></div>
        <img src="../image/logoPolije.png" alt="Logo Kampus" class="h-10 w-auto object-contain">
      </div>
      <div>
        <p class="text-white font-semibold text-sm">Program Kreativitas Mahasiswa</p>
      </div>
      
      <div class="mt-2">
        <div class="flex gap-2 mb-2">
          <a href="https://facebook.com/username" target="_blank" class="w-8 h-8 rounded-full border border-blue-400/50 text-blue-100 flex items-center justify-center transition hover:bg-white hover:text-blue-900">
            <i class="fab fa-facebook-f text-sm"></i>
          </a>
          <a href="https://instagram.com/username" target="_blank" class="w-8 h-8 rounded-full border border-blue-400/50 text-blue-100 flex items-center justify-center transition hover:bg-white hover:text-blue-900">
            <i class="fab fa-instagram text-sm"></i>
          </a>
          <a href="https://yourwebsite.com" target="_blank" class="w-8 h-8 rounded-full border border-blue-400/50 text-blue-100 flex items-center justify-center transition hover:bg-white hover:text-blue-900">
            <i class="fas fa-globe text-sm"></i>
          </a>
        </div>
        <p class="font-bold text-white text-sm">Ikuti Kami</p>
      </div>
    </div>

    <div class="md:px-4">
      <nav class="flex flex-wrap gap-x-4 gap-y-1 mb-6">
        <a href="../index.php" class="text-blue-100 text-sm font-bold hover:text-white transition">Beranda</a>
        <a href="./informasi_dosen.php" class="text-blue-100 text-sm font-bold hover:text-white transition">Informasi Dosen</a>
        <a href="./kontak_center.php" class="text-blue-100 text-sm font-bold hover:text-white transition">Kontak Center</a>
        <a href="./pengumuman.php" class="text-blue-100 text-sm font-bold hover:text-white transition">Pengumuman</a>
      </nav>

      <div>
        <h3 class="text-lg font-bold text-white mb-1">Tentang kami</h3>
        <p class="text-blue-100 text-[11px] leading-relaxed max-w-xs text-justify">
          PKM POLIJE adalah platform digital untuk memfasilitasi kolaborasi tim, informasi terpadu, dan koordinasi terpusat bagi seluruh kegiatan Program Kreativitas Mahasiswa di Politeknik Negeri Jember.
        </p>
      </div>
    </div>

    <div class="border-l-0 md:border-l border-blue-800 md:pl-10 flex flex-col gap-4">
      <div>
        <p class="text-xs font-bold text-blue-300 uppercase tracking-wide">Kontak :</p>
        <p class="text-white text-sm font-medium hover:text-blue-200 transition cursor-pointer">+62 812-3456-7890</p>
      </div>
      
      <div>
        <p class="text-xs font-bold text-blue-300 uppercase tracking-wide">Email :</p>
        <p class="text-white text-sm font-medium hover:text-blue-200 transition cursor-pointer">pkmpolije@polije.ac.id</p>
      </div>
    </div>

  </div>

  <div class="max-w-6xl mx-auto mt-10 pt-6 border-t border-blue-800 text-center">
    <p class="text-blue-300/60 text-[10px]">&copy; 2026 PKM POLIJE. All rights reserved.</p>
  </div>
</footer>
<!-- Footer end -->

    <script>
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('nav-menu');
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
        });
    </script>

</body>
</html>