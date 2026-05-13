<?php
// PINDAHKAN KE BARIS 1 (SANGAT PENTING)
session_start();
require_once '../koneksi/koneksi.php';
// 1. Konfigurasi Pagination & Search
$limit = 4; // Tampilkan 4 data per halaman sesuai permintaan
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    // 2. Hitung Total Data untuk Pagination
    $total_stmt = $pdo->prepare("SELECT COUNT(*) FROM data_kontak_center WHERE nama_admin LIKE :search OR jurusan LIKE :search");
    $total_stmt->execute([':search' => '%' . $search . '%']);
    $total_data = $total_stmt->fetchColumn();
    $total_halaman = ceil($total_data / $limit);
    if ($total_halaman < 1) $total_halaman = 1;

    // 3. Ambil Data dari Database
    $query_sql = "SELECT * FROM data_kontak_center 
                  WHERE nama_admin LIKE :search 
                  OR jurusan LIKE :search 
                  ORDER BY jurusan ASC LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($query_sql);
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $daftar_kontak = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Gagal memuat data: " . $e->getMessage());
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
    <title>KONTAK CENTER PKM POLIJE</title>
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
            
           
            <!-- Pemngumuman  -->
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
            <!-- Pemngumuman  -->




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

    <!-- Konten 1 -->
  <section class="w-full pt-20 pb-10">
    <div class="relative w-full overflow-hidden shadow-2xl group">
        <img src="../image/bacround.jpg" class="w-full h-[500px] md:h-[600px] object-cover group-hover:scale-105 transition-transform duration-700" alt="Contact Center PKM Polije">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/70 to-transparent"></div>

        <div class="absolute bottom-0 left-0 p-6 md:p-16 w-full lg:w-3/4 text-left">
            <h1 class="text-white text-3xl md:text-6xl font-bold leading-tight mb-4 drop-shadow-lg group-hover:text-blue-200 transition-colors">
                Layanan Informasi & Contact Center PKM 2026
            </h1>

            <p class="text-gray-300 text-sm md:text-xl mb-6 line-clamp-2 md:line-clamp-none max-w-3xl">
                Butuh bantuan terkait pendaftaran atau teknis PKM? Kami hadir lebih dekat di setiap jurusan. Silakan hubungi pusat informasi di gedung jurusan Anda untuk mendapatkan solusi dan panduan langsung dari tim kerja PKM Polije.
            </p>

            <div class="flex items-center space-x-4">
                <span class="text-white text-[10px] md:text-xs font-black uppercase tracking-widest bg-red-600 px-4 py-1.5 rounded-md">
                    Help Desk
                </span>
                <span class="text-gray-300 text-[10px] md:text-xs font-medium uppercase tracking-wider">
                    Pusat Bantuan Jurusan - PKM POLIJE
                </span>
            </div>
        </div>
    </div>
</section>
<!-- Konten 1 end -->

<!-- Konten 2 -->
<section class="w-full px-4 md:px-10 py-16 bg-white min-h-screen">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex flex-col md:flex-row items-center md:items-end justify-between mb-12 gap-6 border-b-2 border-slate-50 pb-8">
            <div class="text-center md:text-left">
                <h2 class="text-3xl md:text-5xl font-black text-blue-900 uppercase tracking-tighter mb-2">
                    Kontak Center Jurusan
                </h2>
                <div class="h-1.5 w-20 bg-blue-600 rounded-full mx-auto md:mx-0 mb-4"></div>
                <p class="text-slate-500 font-medium text-sm md:text-base">Gunakan fitur cari untuk menemukan admin jurusan Anda.</p>
            </div>

            <!-- Fitur Search (Menggantikan Filter) -->
            <div class="w-full md:w-80 flex flex-col gap-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-2">Cari Admin / Jurusan :</label>
                <form method="GET" class="relative">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Ketik di sini..." 
                        class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:outline-none focus:border-blue-600 focus:bg-white transition-all font-bold text-slate-700 shadow-sm uppercase tracking-wider text-[11px]">
                    <button type="submit" class="absolute right-5 top-1/2 -translate-y-1/2 text-blue-600 hover:scale-110 transition-transform">
                        <i class="fas fa-search text-xs"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Grid Kontak -->
<div id="gridKontak" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
    <?php if (!empty($daftar_kontak)): ?>
        <?php foreach ($daftar_kontak as $row): ?>
        <div class="item-jurusan group p-6 bg-white rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex items-center gap-6">
            
            <!-- Ukuran Foto yang Proporsional (w-20) -->
            <div class="w-20 h-20 bg-slate-50 rounded-[1.2rem] flex-shrink-0 overflow-hidden border border-slate-100 shadow-inner">
                <img src="../image_center/<?= $row['foto_admin'] ?>" 
                     alt="Foto Admin" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                     onerror="this.src='./image_center/default.jpg'">
            </div>

            <!-- Konten dengan Spasi yang Pas -->
            <div class="flex-grow">
                <div class="mb-2">
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-black uppercase rounded-md tracking-wider">
                        <?= htmlspecialchars($row['jurusan']) ?>
                    </span>
                </div>
                
                <h3 class="text-base font-black text-slate-800 leading-tight mb-3 italic tracking-tight">
                    <?= htmlspecialchars($row['nama_admin']) ?>
                </h3>
                
                <div class="flex flex-col gap-2">
                    <!-- WhatsApp Link -->
                    <a href="https://wa.me/<?= $row['whatsapp'] ?>" target="_blank" class="flex items-center gap-2 text-green-600 hover:text-green-700 transition-colors">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        <span class="text-sm font-black tracking-tight">+<?= htmlspecialchars($row['whatsapp']) ?></span>
                    </a>
                    
                    <!-- Lokasi -->
                    <p class="text-[11px] text-slate-400 font-bold flex items-center italic">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-2 opacity-70"></i>
                        <?= htmlspecialchars($row['lokasi']) ?>
                    </p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-span-full py-20 text-center bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200">
            <p class="text-slate-400 font-black uppercase tracking-widest text-xs">Data tidak ditemukan</p>
        </div>
    <?php endif; ?>
</div>

        <!-- Pagination -->
        <div class="flex flex-col items-center mt-16 gap-6">
            <div class="flex items-center gap-3">
                <!-- Tombol Prev -->
                <a href="?halaman=<?= max(1, $page - 1) ?>&search=<?= $search ?>" 
                   class="w-12 h-12 flex items-center justify-center rounded-2xl border-2 border-slate-100 text-slate-400 hover:border-blue-600 hover:text-blue-600 transition-all shadow-sm <?= ($page <= 1) ? 'opacity-30 pointer-events-none' : '' ?>">
                    <i class="fas fa-chevron-left text-xs"></i>
                </a>
                
                <!-- Nomor Halaman -->
                <?php for($i = 1; $i <= $total_halaman; $i++): ?>
                    <a href="?halaman=<?= $i ?>&search=<?= $search ?>" 
                       class="w-12 h-12 flex items-center justify-center rounded-2xl font-black text-xs transition-all shadow-sm <?= ($i == $page) ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-slate-50 text-slate-500 border-2 border-transparent hover:border-slate-200' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <!-- Tombol Next -->
                <a href="?halaman=<?= min($total_halaman, $page + 1) ?>&search=<?= $search ?>" 
                   class="w-12 h-12 flex items-center justify-center rounded-2xl border-2 border-slate-100 text-slate-400 hover:border-blue-600 hover:text-blue-600 transition-all shadow-sm <?= ($page >= $total_halaman) ? 'opacity-30 pointer-events-none' : '' ?>">
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">Halaman <?= $page ?> dari <?= $total_halaman ?></p>
        </div>

    </div>
</section>









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


      <!-- filter -->
       <script>
    function filterJurusan() {
        let select = document.getElementById('selectJurusan');
        let filter = select.value.toUpperCase();
        let items = document.getElementsByClassName('item-jurusan');

        for (let i = 0; i < items.length; i++) {
            let category = items[i].getAttribute('data-category');
            if (filter === 'SEMUA' || filter === category) {
                items[i].style.display = "flex";
            } else {
                items[i].style.display = "none";
            }
        }
    }
</script>
      <!-- filter -->


</body>
</html>