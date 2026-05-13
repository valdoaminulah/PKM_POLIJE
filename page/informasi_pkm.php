<?php
require_once '../koneksi/koneksi.php';

// 1. Pengaturan Pagination (Kembali ke 6 data per halaman)
$limit = 6; 
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$offset = ($page - 1) * $limit;

// 2. Logika Pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    // Hitung Total Data
    $sql_count = "SELECT COUNT(*) FROM data_pkm";
    if (!empty($search)) {
        $sql_count .= " WHERE nama_pkm LIKE :search OR singkatan LIKE :search";
    }
    $stmt_count = $pdo->prepare($sql_count);
    if (!empty($search)) $stmt_count->bindValue(':search', '%' . $search . '%');
    $stmt_count->execute();
    $total_data = $stmt_count->fetchColumn();
    
    // Logika agar nomor halaman minimal tetap muncul angka 1 (Permanen)
    $total_halaman = ceil($total_data / $limit);
    $total_halaman = ($total_halaman < 1) ? 1 : $total_halaman;

    // Ambil Data PKM (Limit 6)
    $sql = "SELECT * FROM data_pkm";
    if (!empty($search)) {
        $sql .= " WHERE nama_pkm LIKE :search OR singkatan LIKE :search";
    }
    $sql .= " ORDER BY singkatan ASC LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($sql);
    if (!empty($search)) $stmt->bindValue(':search', '%' . $search . '%');
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $daftar_pkm = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
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
    <title>INFORMASI PKM POLIJE</title>
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
        <img src="../image/bacround.jpg" class="w-full h-[500px] md:h-[600px] object-cover group-hover:scale-105 transition-transform duration-700" alt="Editor's Pick PKM">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/70 to-transparent"></div>

        <div class="absolute bottom-0 left-0 p-6 md:p-16 w-full lg:w-3/4 text-left">
            <h1 class="text-white text-3xl md:text-6xl font-bold leading-tight mb-4 drop-shadow-lg group-hover:text-blue-200 transition-colors">
                Segala hal tentang PKM 2026, persiapan menuju PIMNAS ke-39
            </h1>

            <p class="text-gray-300 text-sm md:text-xl mb-6 line-clamp-2 md:line-clamp-none max-w-3xl">
                Tahun 2026 menjadi tahun inovasi bagi mahasiswa Politeknik Negeri Jember. Kami telah mendengar rumor tentang skema baru PKM sejak awal semester sebelum pendaftaran resmi dibuka.
            </p>

            <div class="flex items-center space-x-4">
                <span class="text-white text-[10px] md:text-xs font-black uppercase tracking-widest bg-blue-600 px-4 py-1.5 rounded-md">
                    Information
                </span>
                <span class="text-gray-300 text-[10px] md:text-xs font-medium uppercase tracking-wider">
                    Informasi Terkait PKM - PKM POLIJE
                </span>
            </div>
        </div>

    </div>
</section>
<!-- Konten 1 end -->
<section class="container mx-auto px-5 py-16">
    <div class="text-center mb-8">
        <h2 class="text-3xl md:text-4xl font-bold text-blue-900 mb-4 tracking-tighter uppercase italic">
            Skema Program Kreativitas Mahasiswa
        </h2>
        <div class="h-1.5 w-20 bg-blue-600 mx-auto rounded-full"></div>
    </div>

    <!-- Opsi Search (Pencarian) -->
    <div class="max-w-4xl mx-auto mb-12">
        <form action="" method="GET" class="relative group">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors"></i>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Cari skema PKM (contoh: PKM-KC)..." 
                   class="w-full pl-12 pr-4 py-4 rounded-2xl border-2 border-gray-100 focus:border-blue-600 focus:outline-none shadow-sm transition-all text-gray-700 font-medium">
            <?php if(!empty($search)): ?>
                <a href="?" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 text-xs font-bold uppercase">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Grid Card PKM (6 Card per Halaman) -->
    <div id="pkm-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 min-h-[350px]">
    <?php if (!empty($daftar_pkm)): ?>
        <?php foreach($daftar_pkm as $row): ?>
            <div class="pkm-card transition-all duration-300">
                <!-- Tambahkan 'flex flex-col h-full' pada tag <a> -->
                <a href="detail_pkm.php?id=<?= $row['id_pkm'] ?>" class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden flex flex-col h-full transition-all">
                    
                    <div class="relative h-48 overflow-hidden shrink-0">
                        <img src="../upload/<?= htmlspecialchars($row['foto_pkm']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" onerror="this.src='https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=600'">
                        <div class="absolute top-4 left-4 bg-blue-600 text-white text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest shadow-lg">
                            <?= htmlspecialchars($row['singkatan']) ?>
                        </div>
                    </div>

                    <!-- Tambahkan 'flex flex-col flex-grow' pada padding container -->
                    <div class="p-6 text-left flex flex-col flex-grow">
                        <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors uppercase italic tracking-tighter">
                            <?= htmlspecialchars($row['nama_pkm']) ?>
                        </h3>
                        
                        <!-- Tambahkan 'flex-grow' pada deskripsi agar mendorong elemen di bawahnya -->
                        <p class="text-gray-500 text-xs leading-relaxed mb-6 line-clamp-3 flex-grow">
                            <?= htmlspecialchars($row['deskripsi_singkat']) ?>
                        </p>

                        <!-- Bagian ini otomatis akan sejajar di paling bawah -->
                        <div class="text-blue-600 font-black text-[10px] uppercase tracking-widest flex items-center gap-2 pt-4 border-t border-gray-50">
                            Lihat Panduan <i class="fas fa-chevron-right text-[8px] group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-dashed border-slate-200">
            <i class="fas fa-search text-4xl text-slate-200 mb-4"></i>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Skema tidak ditemukan</p>
        </div>
    <?php endif; ?>
</div>

    <!-- PAGINATION BAR (Selalu Muncul/Permanen) -->
    <div class="mt-16 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500 border-t border-slate-100 pt-8">
        <div>
            Menampilkan <span class="font-bold text-slate-700"><?= count($daftar_pkm) ?></span> dari <span class="font-bold text-slate-700"><?= $total_data ?></span> skema
        </div>
        
        <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-200 shadow-sm">
            <!-- Tombol Prev -->
            <a href="?halaman=<?= max(1, $page - 1) ?>&search=<?= urlencode($search) ?>" 
               class="px-2.5 py-1.5 rounded hover:bg-slate-100 transition-all <?= ($page <= 1) ? 'opacity-30 pointer-events-none' : '' ?> text-slate-400">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>

            <!-- Nomor Halaman -->
            <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                <a href="?halaman=<?= $i ?>&search=<?= urlencode($search) ?>" 
                   class="w-8 h-8 flex items-center justify-center rounded font-bold transition-all <?= ($i == $page) ? 'bg-blue-600 text-white shadow-sm' : 'hover:bg-slate-100 text-slate-600 font-medium' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <!-- Tombol Next -->
            <a href="?halaman=<?= min($total_halaman, $page + 1) ?>&search=<?= urlencode($search) ?>" 
               class="px-2.5 py-1.5 rounded hover:bg-slate-100 transition-all <?= ($page >= $total_halaman) ? 'opacity-30 pointer-events-none' : '' ?> text-slate-600">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        </div>
    </div>
</section>









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

           // Fungsi untuk Filter Tombol
    function filterPKM(category) {
        const cards = document.querySelectorAll('.pkm-card');
        const buttons = document.querySelectorAll('.filter-btn');

        buttons.forEach(btn => {
            if(btn.getAttribute('data-category') === category) {
                btn.classList.add('active', 'border-blue-900', 'bg-blue-50', 'text-blue-900');
                btn.classList.remove('border-gray-100', 'text-gray-500');
            } else {
                btn.classList.remove('active', 'border-blue-900', 'bg-blue-50', 'text-blue-900');
                btn.classList.add('border-gray-100', 'text-gray-500');
            }
        });

        cards.forEach(card => {
            if (category === 'all' || card.getAttribute('data-type') === category) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }

    // Fungsi untuk Search Bar
    function searchPKM() {
        const input = document.getElementById('pkm-search').value.toUpperCase();
        const cards = document.querySelectorAll('.pkm-card');
        
        cards.forEach(card => {
            const type = card.getAttribute('data-type').toUpperCase();
            const title = card.querySelector('h3').innerText.toUpperCase();
            
            if (type.includes(input) || title.includes(input)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }
    </script>
</body>
</html>