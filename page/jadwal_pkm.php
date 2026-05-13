<?php
// PINDAHKAN KE BARIS 1 (SANGAT PENTING)
session_start();
require_once '../koneksi/koneksi.php';

try {
    // Ambil semua data jadwal
    $sql = "SELECT * FROM jadwal_pkm ORDER BY tgl_mulai ASC";
    $stmt = $pdo->query($sql);
    $daftar_jadwal = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ambil total pesan untuk badge (pindahkan logikanya ke sini juga)
    $stmt_msg = $pdo->query("SELECT COUNT(*) FROM pesan WHERE tujuan_pesan IN ('Semua', 'Mahasiswa')");
    $total_db = $stmt_msg->fetchColumn();
    $dilihat = isset($_SESSION['last_viewed_count']) ? $_SESSION['last_viewed_count'] : 0;
    $baru = $total_db - $dilihat;

} catch (PDOException $e) {
    die("Gagal memuat data: " . $e->getMessage());
}

// Fungsi format tanggal Indonesia
function formatTglIndo($tanggal) {
    $bulan = [
        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
    ];
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
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
    <title>JADWAL PKM POLIJE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media (min-width: 1024px) {
            .group:hover .group-hover\:block { display: block; }
        }

        /* Indikator scrollbar tetap muncul namun halus */
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #1e3a8a;
        border-radius: 10px;
    }

    /* Tampilan Khusus Download / Print */
    @media print {
        /* Sembunyikan seluruh elemen halaman */
        body * { 
            visibility: hidden; 
        }
        
        /* Tampilkan hanya area jadwal dan headernya */
        #header-jadwal, 
        #header-jadwal *, 
        #printable-table, 
        #printable-table * { 
            visibility: visible; 
        }

        /* Atur posisi presisi di lembar kertas/PDF */
        #header-jadwal { 
            position: absolute; 
            left: 0; 
            top: 0; 
            width: 100%;
        }

        #printable-table { 
            position: absolute; 
            left: 0; 
            top: 180px; /* Jarak dari header */
            width: 100%; 
            border: 1px solid #e5e7eb; /* Border tipis agar rapi di kertas */
            box-shadow: none !important; 
        }

        /* Paksa background warna muncul di PDF */
        tr.bg-blue-900 {
            background-color: #1e3a8a !important;
            -webkit-print-color-adjust: exact;
        }

        /* Sembunyikan elemen yang tidak perlu dicetak */
        .no-print { 
            display: none !important; 
        }
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
        <img src="../image/bacround.jpg" class="w-full h-[500px] md:h-[600px] object-cover group-hover:scale-105 transition-transform duration-700" alt="Jadwal PKM Polije 2026">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/70 to-transparent"></div>

        <div class="absolute bottom-0 left-0 p-6 md:p-16 w-full lg:w-3/4 text-left">
            <h1 class="text-white text-3xl md:text-6xl font-bold leading-tight mb-4 drop-shadow-lg group-hover:text-blue-200 transition-colors">
                Alur Tahapan & Jadwal Penting PKM 2026
            </h1>

            <p class="text-gray-300 text-sm md:text-xl mb-6 line-clamp-2 md:line-clamp-none max-w-3xl">
                Jangan terlewat! Pantau seluruh rangkaian kegiatan Program Kreativitas Mahasiswa (PKM) 2026. Dari sosialisasi internal hingga persiapan menuju PIMNAS ke-39, Humas Polije menyajikan timeline lengkap untuk memandu langkah inovasimu.
            </p>

            <div class="flex items-center space-x-4">
                <span class="text-white text-[10px] md:text-xs font-black uppercase tracking-widest bg-orange-600 px-4 py-1.5 rounded-md">
                    Official Timeline
                </span>
                <span class="text-gray-300 text-[10px] md:text-xs font-medium uppercase tracking-wider">
                    Jadwal & Tahapan - PKM POLIJE
                </span>
            </div>
        </div>
    </div>
</section>
<!-- Konten 1 end -->




<!-- Konten 2: Timeline PKM untuk Umum -->
<section class="w-full px-4 md:px-10 py-12 bg-white">
    <div class="max-w-6xl w-full mx-auto">
        
        <!-- Header Jadwal -->
        <div id="header-jadwal" class="mb-10 text-left">
            <h2 class="text-3xl md:text-4xl font-black text-blue-900 uppercase tracking-tighter mb-4">
                Timeline Pelaksanaan PKM 2026
            </h2>
            <div class="h-1.5 w-20 bg-blue-600 mb-6 rounded-full"></div>
            <p class="text-gray-500 text-sm md:text-lg leading-relaxed max-w-3xl">
                Informasi lengkap rangkaian tahapan seleksi Program Kreativitas Mahasiswa (PKM) 2026 untuk mahasiswa Politeknik Negeri Jember.
            </p>
        </div>

        <!-- Tabel Timeline -->
        <div id="printable-table" class="rounded-[1.5rem] md:rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/40 overflow-hidden bg-white">
            
            <!-- Mobile Helper -->
            <div class="md:hidden bg-blue-50 text-blue-600 text-[10px] font-bold py-3 px-4 flex items-center justify-between border-b border-blue-100 no-print">
                <span class="flex items-center gap-2"><i class="fas fa-hand-pointer animate-bounce"></i> Geser Tabel untuk detail</span>
                <i class="fas fa-arrow-right"></i>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse bg-white min-w-[600px]">
                    <thead>
                        <tr class="bg-blue-900 text-white">
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] w-16 text-center">No</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em]">Agenda Kegiatan</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-center">Mulai</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-center">Batas Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-700">
                        <?php if (!empty($daftar_jadwal)): ?>
                            <?php $no = 1; foreach ($daftar_jadwal as $row): ?>
                                <!-- Logika Warna: Baris ganjil merah-ish, baris genap blue-ish sesuai desain Anda -->
                                <?php $isEven = ($no % 2 == 0); ?>
                                <tr class="hover:bg-<?= $isEven ? 'blue' : 'red' ?>-50/40 transition-all duration-300 group">
                                    <td class="px-6 py-6 text-center font-black text-gray-300 group-hover:text-<?= $isEven ? 'blue-900' : 'red-600' ?> text-base border-r border-gray-50">
                                        <?= sprintf("%02d", $no); ?>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-white shadow-sm border border-gray-100 rounded-xl p-2 flex-shrink-0">
                                                <img src="../image/logoPKM.png" alt="Icon" class="w-full h-full object-contain">
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-black text-gray-700 text-base group-hover:text-<?= $isEven ? 'blue-900' : 'red-600' ?> transition-colors uppercase italic tracking-tighter">
                                                    <?= htmlspecialchars($row['judul_jadwal']) ?>
                                                </span>
                                                <?php if(!empty($row['keterangan'])): ?>
                                                    <span class="text-[10px] text-gray-400 font-medium"><?= htmlspecialchars($row['keterangan']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-center font-bold text-gray-500 text-sm">
                                        <?= formatTglIndo($row['tgl_mulai']) ?>
                                    </td>
                                    <td class="px-6 py-6 text-center font-black text-<?= $isEven ? 'blue-900' : 'red-600' ?> text-base">
                                        <?= formatTglIndo($row['tgl_berakhir']) ?>
                                    </td>
                                </tr>
                            <?php $no++; endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">Belum ada agenda kegiatan yang diterbitkan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Tabel -->
        <div class="mt-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <p class="text-[10px] text-gray-400 font-medium italic max-w-sm">
                * Data jadwal ini sinkron dengan sistem informasi akademik Polije. Terakhir diperbarui pada <?= date('d/m/Y') ?>.
            </p>
            
            <!-- Tombol Cetak/Download -->
            <button onclick="window.print()" class="inline-flex items-center justify-center gap-3 px-8 py-3.5 bg-blue-900 text-white rounded-full font-bold text-sm shadow-lg shadow-blue-900/20 hover:bg-blue-800 hover:-translate-y-1 transition-all active:scale-95 group no-print">
                <i class="fas fa-print text-lg group-hover:scale-110 transition-transform"></i>
                <span>Cetak Jadwal</span>
            </button>
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
 <!-- Js Mobile -->
    <script>
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('nav-menu');
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
        });
    </script>
  <!-- Js Mobile -->

  <!-- js dowload jadwal -->
   <script>
    function downloadJadwal() {
        window.print();
    }
</script>
  <!-- js dowload jadwal -->
</body>
</html>