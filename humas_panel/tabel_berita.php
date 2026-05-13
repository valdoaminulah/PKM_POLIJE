<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'humas') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// --- LOGIKA PENCARIAN ---
$search = isset($_GET['search']) ? $_GET['search'] : '';

// --- LOGIKA PAGINASI ---
$limit = 10; 
$page = isset($_GET['page']) ? (int)$GET['page'] : 1;
$offset = ($page - 1) * $limit;

// --- AMBIL TOTAL DATA UNTUK PAGINASI & CARD ---
$sql_count = "SELECT COUNT(*) FROM data_berita WHERE judul_berita LIKE :search";
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute(['search' => "%$search%"]);
$total_data = $stmt_count->fetchColumn();
$total_pages = ceil($total_data / $limit);

// --- AMBIL SEMUA DATA BERITA ---
$sql = "SELECT * FROM data_berita 
        WHERE judul_berita LIKE :search 
        ORDER BY tanggal_publikasi DESC 
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$data_berita = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Logo Web -->
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <!-- Logo web -->
  <title>Input Berita</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">

 <!-- SIDEBAR -->
<!-- Tambahan: sticky top-0 h-screen agar tetap di tempat saat di-scroll -->
<aside class="w-64 bg-white shadow-lg p-6 sticky top-0 h-screen flex flex-col">
  <h1 class="text-xl font-bold text-gray-800">Dashboard Humas</h1>
  <p class="text-sm text-gray-500 mb-8">Manajemen Berita</p>

  <nav class="space-y-4 flex-1">
    <!-- Dashboard (Active) -->
    <a href="./dasboard.php" class="flex items-center gap-3 text-gray-600 hover:text-blue-500 px-4 py-2 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 3h7v4h-7v-4z" />
      </svg>
      <span>Dashboard</span>
    </a>

    <!-- Input Berita -->
    <a href="./tambah_berita.php" class="flex items-center gap-3 text-gray-600 hover:text-blue-500 px-4 py-2 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      <span>Input Berita</span>
    </a>

    <!-- Tabel Berita -->
    <a href="./tabel_berita.php" class="flex items-center gap-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-3 rounded-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18M3 7v10M9 7v10M15 7v10M21 7v10" />
      </svg>
      <span>Tabel Berita</span>
    </a>
  </nav>

  <!-- Logout diletakkan di bawah (opsional) -->
  <div class="mt-auto">
    <a href="../login_user/proses_logout_user.php" class="flex items-center gap-3 text-red-500 hover:text-red-600 px-4 py-2 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V4" />
      </svg>
      <span>Log out</span>
    </a>
  </div>
</aside>

    
<div class="flex-1 p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-start mb-6">
        <div class="flex items-start gap-4">
            <button onclick="window.location.href='dashboard.php'" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border hover:bg-gray-50">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Manajemen Data Berita</h2>
                <p class="text-sm text-gray-500">Daftar publikasi berita Humas Polije</p>
            </div>
        </div>
        <a href="./tambah_berita.php">
            <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg shadow transition">
                + Tambah Berita
            </button>
        </a>
    </div>

    <!-- CARD TOTAL -->
    <div class="bg-white border rounded-xl p-6 mb-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h4m-4 4h8m-8 4h8" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Berita Terupload</p>
                <p class="text-3xl font-bold text-gray-800"><?= number_format($total_data); ?></p>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH -->
    <div class="bg-white border rounded-xl p-4 mb-6">
        <form method="GET" class="relative">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                placeholder="Cari berdasarkan judul berita..." 
                class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none border-gray-200">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </form>
    </div>

    <!-- TABLE AREA -->
    <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-xs tracking-wider">
                <tr>
                    <!-- Tambah kolom NO -->
                    <th class="p-4 w-12 text-center">No</th>
                    <th class="p-4 w-24 text-center">Gambar</th>
                    <th class="p-4 w-64">Informasi Berita</th>
                    <th class="p-4">Ringkasan</th>
                    <th class="p-4 w-40">Link & Tanggal</th>
                    <th class="p-4 w-24 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (count($data_berita) > 0): ?>
                    <?php 
                    // Inisialisasi nomor
                    $no = 1; 
                    foreach ($data_berita as $row): 
                    ?>
                        <tr class="hover:bg-gray-50/80 transition">
                            <!-- Tampilkan nomor -->
                            <td class="p-4 text-center font-medium text-gray-600"><?= $no++; ?></td>
                            
                            <td class="p-4 text-center">
                                <img src="../berita/<?= $row['gambar_utama'] ?>" 
                                     class="w-20 h-14 object-cover rounded-lg border shadow-sm mx-auto" 
                                     onerror="this.src='https://placehold.co/200x150?text=No+Image'">
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-gray-900 leading-tight mb-1">
                                    <?= htmlspecialchars($row['judul_berita']) ?>
                                </div>
                            </td>
                            <td class="p-4 text-gray-500 leading-relaxed">
                                <p class="line-clamp-2 italic text-xs">
                                    <?= htmlspecialchars($row['ringkasan']) ?: '<span class="text-gray-300">Tidak ada ringkasan</span>' ?>
                                </p>
                            </td>
                            <td class="p-4">
                                <div class="text-blue-600 font-medium truncate w-40 text-xs mb-1">
                                    <a href="<?= $row['link_website'] ?>" target="_blank" class="hover:underline italic">
                                        <?= $row['link_website'] ?>
                                    </a>
                                </div>
                                <div class="text-gray-400 text-[10px] font-bold uppercase">
                                    📅 <?= date('d M Y', strtotime($row['tanggal_publikasi'])) ?>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="./edit_berita.php?id=<?= $row['id_berita'] ?>" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <a href="../proses_hapus/hapus_data_berita.php?id=<?= $row['id_berita'] ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')" 
                                       class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <!-- Colspan ditambah menjadi 6 karena ada kolom No -->
                        <td colspan="6" class="p-12 text-center text-gray-400 italic">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Data berita tidak ditemukan...
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

        <!-- PAGINATION INFO -->
        <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-xs text-gray-500 font-medium">
                Menampilkan <span class="text-gray-800"><?= count($data_berita) ?></span> dari <span class="text-gray-800"><?= $total_data ?></span> data berita
            </p>

            <div class="flex items-center gap-1">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>" class="px-3 py-1.5 rounded-lg border bg-white hover:bg-gray-100 text-gray-600 transition">Prev</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" 
                       class="px-3.5 py-1.5 rounded-lg border transition <?= $page == $i ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-100' : 'bg-white hover:bg-gray-100 text-gray-600' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>" class="px-3 py-1.5 rounded-lg border bg-white hover:bg-gray-100 text-gray-600 transition">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


</body>
</html>