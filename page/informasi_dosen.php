<?php
require_once '../koneksi/koneksi.php';

// 1. Pengaturan Pagination
$limit = 8; 
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$offset = ($page - 1) * $limit;

// 2. Logika Filter Jurusan
$filter_jurusan = isset($_GET['jurusan']) ? $_GET['jurusan'] : 'semua';

try {
    // Menghitung Total Data untuk Logika Halaman
    $sql_count = "SELECT COUNT(*) FROM data_dosen";
    if ($filter_jurusan !== 'semua') {
        $sql_count .= " WHERE jurusan = :jurusan";
    }
    $stmt_count = $pdo->prepare($sql_count);
    if ($filter_jurusan !== 'semua') $stmt_count->bindValue(':jurusan', $filter_jurusan);
    $stmt_count->execute();
    $total_data = $stmt_count->fetchColumn();
    $total_halaman = ceil($total_data / $limit);

    // Ambil Data Dosen
    $sql = "SELECT * FROM data_dosen";
    if ($filter_jurusan !== 'semua') {
        $sql .= " WHERE jurusan = :jurusan";
    }
    $sql .= " ORDER BY nama_lengkap ASC LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($sql);
    if ($filter_jurusan !== 'semua') $stmt->bindValue(':jurusan', $filter_jurusan);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $daftar_dosen = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ambil list jurusan untuk dropdown
    $list_jurusan = $pdo->query("SELECT DISTINCT jurusan FROM data_dosen ORDER BY jurusan ASC")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
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
    <title>INFORMASI DOSEN PKM POLIJE</title>
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

 <!-- KONTEN 1: HERO SECTION -->
<section class="w-full pt-20 pb-10">
    <div class="relative w-full overflow-hidden shadow-2xl group">
        <!-- Pastikan file background.jpg ada di folder image -->
        <img src="../image/bacround.jpg" class="w-full h-[500px] md:h-[600px] object-cover group-hover:scale-105 transition-transform duration-700" alt="Dosen Pembimbing PKM Polije">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/70 to-transparent"></div>

        <div class="absolute bottom-0 left-0 p-6 md:p-16 w-full lg:w-3/4 text-left">
            <h1 class="text-white text-3xl md:text-6xl font-bold leading-tight mb-4 drop-shadow-lg group-hover:text-blue-200 transition-colors uppercase italic tracking-tighter">
                Kolaborasi Hebat Dosen Pendamping PKM 2026
            </h1>

            <p class="text-gray-300 text-sm md:text-xl mb-6 line-clamp-2 md:line-clamp-none max-w-3xl">
                Wujudkan ide kreatifmu menjadi proposal berkualitas dengan bimbingan pakar. Humas Polije menyajikan daftar dosen pendamping dari berbagai jurusan yang siap membantu mengarahkan inovasimu menuju sukses PIMNAS ke-39.
            </p>

            <div class="flex items-center space-x-4">
                <span class="text-white text-[10px] md:text-xs font-black uppercase tracking-widest bg-emerald-600 px-4 py-1.5 rounded-md">
                    Mentorship Panel
                </span>
                <span class="text-gray-300 text-[10px] md:text-xs font-medium uppercase tracking-wider">
                    Lintas Jurusan - Polije
                </span>
            </div>
        </div>
    </div>
</section>

<!-- KONTEN 2: DAFTAR DOSEN -->
<section class="w-full px-3 md:px-10 py-16 bg-gray-50/50">
    <div class="max-w-[1400px] mx-auto">
        
        <div class="flex flex-col md:flex-row items-end justify-between mb-12 gap-6">
            <div class="text-left">
                <h2 class="text-2xl md:text-4xl font-black text-blue-900 mb-2 tracking-tighter uppercase italic">
                    DOSEN PENGAMPU PKM 2026
                </h2>
                <div class="h-1.5 w-16 bg-blue-600 rounded-full"></div>
                <p class="text-gray-500 mt-4 font-medium text-sm md:text-base">Temukan dosen pendamping berdasarkan bidang keahlian jurusan.</p>
            </div>

            <!-- Filter Dropdown -->
            <form action="" method="GET" class="w-full md:w-80 group">
                <label for="filter-jurusan" class="block text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2 ml-1">Filter Jurusan</label>
                <div class="relative">
                    <select name="jurusan" onchange="this.form.submit()" 
                        class="w-full appearance-none bg-white border-2 border-slate-200 text-slate-700 py-3.5 px-5 pr-12 rounded-2xl font-bold text-sm focus:outline-none focus:border-blue-900 focus:ring-4 focus:ring-blue-900/10 transition-all cursor-pointer shadow-sm">
                        <option value="semua">Tampilkan Semua Jurusan</option>
                        <?php foreach($list_jurusan as $j): ?>
                            <option value="<?= htmlspecialchars($j['jurusan']) ?>" <?= $filter_jurusan == $j['jurusan'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($j['jurusan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-blue-900">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </form>
        </div>

        <!-- GRID DATA DOSEN -->
        <div id="container-dosen" class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
            <?php if (!empty($daftar_dosen)): ?>
                <?php foreach($daftar_dosen as $row): ?>
                    <?php 
                        // Jalur Foto
                        $namaFoto = $row['foto_dosen'];
                        $pathFisik = "../upload/" . $namaFoto;
                        if (!empty($namaFoto) && file_exists($pathFisik)) {
                            $urlFoto = $pathFisik;
                        } else {
                            $urlFoto = "https://ui-avatars.com/api/?name=" . urlencode($row['nama_lengkap']) . "&background=eff6ff&color=2563eb&rounded=false&bold=true&size=512";
                        }
                    ?>
                    <div class="card-dosen group bg-white rounded-[2rem] p-4 md:p-6 border border-slate-200 shadow-[0_10px_30px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.1)] hover:-translate-y-2 transition-all duration-500 flex flex-col h-full text-left">
                        
                        <!-- FRAME FOTO KOTAK (Aspect Square) -->
                        <div class="w-full aspect-square bg-slate-50 rounded-2xl flex items-center justify-center shadow-inner overflow-hidden flex-shrink-0 border border-slate-100 mb-5">
                            <img src="<?= $urlFoto ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Foto Dosen">
                        </div>

                        <div class="flex flex-col flex-grow">
                            <span class="text-[8px] md:text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1 leading-none">
                                <?= htmlspecialchars($row['jurusan']) ?>
                            </span>
                            <h3 class="text-[11px] md:text-base font-black text-slate-800 leading-tight mb-3 uppercase tracking-tighter line-clamp-2 min-h-[2.5rem] md:min-h-0">
                                <?= htmlspecialchars($row['nama_lengkap']) ?>
                            </h3>
                            <div class="space-y-1 mb-5 border-t border-slate-50 pt-3">
                                <p class="text-[7px] md:text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">NIP :</p>
                                <p class="text-[9px] md:text-[11px] font-black text-slate-700 uppercase tracking-tighter"><?= htmlspecialchars($row['nip']) ?></p>
                            </div>
                            <div class="mt-auto">
                                <a href="detail_dosen.php?id=<?= $row['id_dosen'] ?>" class="inline-flex items-center justify-center w-full py-2.5 bg-blue-900 text-white rounded-xl font-black text-[8px] md:text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-blue-900/20 hover:bg-blue-800 transition-all">PROFIL</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full py-20 text-center">
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">Tidak ada data dosen ditemukan.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- PAGINATION BAR (Persis Template Awal) -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500 border-t border-slate-100 mt-16 pt-8">
            <div>
                Menampilkan <span class="font-bold text-slate-700"><?= count($daftar_dosen) ?></span> dari <span class="font-bold text-slate-700"><?= $total_data ?></span> data dosen
            </div>
            
            <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-200 shadow-sm">
                <!-- Prev -->
                <a href="<?= ($page > 1) ? "?halaman=".($page-1)."&jurusan=$filter_jurusan" : "#" ?>" 
                   class="px-2.5 py-1.5 rounded hover:bg-slate-100 transition-all <?= ($page <= 1) ? 'opacity-30 pointer-events-none' : '' ?> text-slate-400">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>

                <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                    <a href="?halaman=<?= $i ?>&jurusan=<?= $filter_jurusan ?>" 
                       class="w-8 h-8 flex items-center justify-center rounded font-bold transition-all <?= ($i == $page) ? 'bg-blue-600 text-white shadow-sm' : 'hover:bg-slate-100 text-slate-600 font-medium' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <!-- Next -->
                <a href="<?= ($page < $total_halaman) ? "?halaman=".($page+1)."&jurusan=$filter_jurusan" : "#" ?>" 
                   class="px-2.5 py-1.5 rounded hover:bg-slate-100 transition-all <?= ($page >= $total_halaman) ? 'opacity-30 pointer-events-none' : '' ?> text-slate-600">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
            </div>
        </div>

    </div>
</section>
<script>
function filterDosen() {
    const filterValue = document.getElementById('filter-jurusan').value;
    const cards = document.querySelectorAll('.card-dosen');
    const noData = document.getElementById('no-data');
    let hasData = false;

    cards.forEach(card => {
        const jurusan = card.getAttribute('data-jurusan');
        if (filterValue === 'semua' || jurusan === filterValue) {
            card.style.display = 'flex';
            hasData = true;
        } else {
            card.style.display = 'none';
        }
    });

    // Tampilkan pesan jika tidak ada data yang cocok
    if (hasData) {
        noData.classList.add('hidden');
    } else {
        noData.classList.remove('hidden');
    }
}
</script>

<script>
    function filterDosen() {
        const selectedValue = document.getElementById('filter-jurusan').value;
        const cards = document.querySelectorAll('.card-dosen');

        cards.forEach(card => {
            const jurusan = card.getAttribute('data-jurusan');
            
            if (selectedValue === 'semua' || selectedValue === jurusan) {
                card.style.display = 'flex';
                // Animasi masuk
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0) scale(1)';
                }, 10);
            } else {
                // Animasi keluar
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px) scale(0.95)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 400);
            }
        });
    }
</script>
<!-- Konten 2 -->









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