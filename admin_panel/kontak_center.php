<?php
session_start();
require_once '../koneksi/koneksi.php';

// Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 1. Konfigurasi Pagination
$limit = 6; // Dibatasi 6 data per halaman
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    // 2. Hitung Total Data (untuk menentukan jumlah halaman)
    $total_stmt = $pdo->prepare("SELECT COUNT(*) FROM data_kontak_center WHERE nama_admin LIKE :search OR jurusan LIKE :search");
    $total_stmt->execute([':search' => '%' . $search . '%']);
    $total_data = $total_stmt->fetchColumn();
    
    // Pastikan total_halaman minimal 1 agar pagination tidak hilang saat data kosong
    $total_halaman = ceil($total_data / $limit);
    if ($total_halaman < 1) $total_halaman = 1;

    // 3. Ambil Data dengan Limit 6
    $query_sql = "SELECT * FROM data_kontak_center 
                  WHERE nama_admin LIKE :search 
                  OR jurusan LIKE :search 
                  ORDER BY id_kontak DESC LIMIT :limit OFFSET :offset";
    
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKM POLIJE - Informasi PKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeInUp 0.6s ease-out forwards; }
        
        /* Utility untuk memotong teks panjang menjadi 2 baris */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-700 overflow-hidden">

    <div class="flex h-screen w-full">
        
        <aside class="w-72 bg-white border-r border-slate-200 flex flex-col flex-shrink-0 z-40 shadow-sm">
            <div class="p-8">
                <div class="flex items-center gap-3 group cursor-pointer">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100 overflow-hidden transition-all duration-500 group-hover:rotate-[360deg] group-hover:scale-110">
                        <img src="./image/logoPKM.png" alt="Logo" class="w-8 h-8 object-contain brightness-0 invert" onerror="this.style.display='none'; this.insertAdjacentHTML('afterend', '<i class=\'fa-solid fa-graduation-cap text-white text-xl\'></i>');"> 
                    </div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">PKM POLIJE</h1>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                <a href="./dasboard.php" class="flex items-center gap-4 px-4 py-3.5 text-slate-400 hover:bg-slate-50 hover:text-blue-600 rounded-2xl transition-all duration-300 hover:pl-7">
                    <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
                </a>
                
                <a href="./data_mahasiswa.php" class="flex items-center gap-4 px-4 py-3.5 text-slate-400 hover:bg-slate-50 hover:text-blue-600 rounded-2xl transition-all duration-300 hover:pl-7">
                    <i class="fa-solid fa-user-graduate w-5"></i> Data Mahasiswa
                </a>

                <a href="./data_dosen.php" class="flex items-center gap-4 px-4 py-3.5 text-slate-400 hover:bg-slate-50 hover:text-blue-600 rounded-2xl transition-all duration-300 hover:pl-7">
                    <i class="fa-solid fa-chalkboard-user w-5"></i> Data Dosen
                </a>

                <a href="./informasi_pkm.php" class="flex items-center gap-4 px-4 py-3.5 text-slate-400 hover:bg-slate-50 hover:text-blue-600 rounded-2xl transition-all duration-300 hover:pl-7">
                    <i class="fa-solid fa-circle-info w-5"></i> Informasi PKM
                </a>

                <a href="./kontak_center.php" class="flex items-center gap-4 px-4 py-3.5 text-slate-400 hover:bg-slate-50 hover:text-blue-600 rounded-2xl transition-all duration-300 hover:pl-7">
                    <i class="fa-solid fa-phone w-5"></i> Kontak Center
                </a>

                <a href="./broadcast.php" class="flex items-center gap-4 px-4 py-3.5 text-slate-400 hover:bg-slate-50 hover:text-blue-600 rounded-2xl transition-all duration-300 hover:pl-7">
                    <i class="fa-solid fa-bullhorn w-5"></i> Broadcast
                </a>

                <a href="./timeline.php" class="flex items-center gap-4 px-4 py-3.5 text-slate-400 hover:bg-slate-50 hover:text-blue-600 rounded-2xl transition-all duration-300 hover:pl-7">
                    <i class="fa-solid fa-calendar-days w-5"></i> Timeline
                </a>
            </nav>


            <div class="p-6 border-t border-slate-100">
                <a href="../login_user/proses_logout_user.php">
                <button class="flex items-center gap-4 w-full px-4 py-4 text-slate-400 hover:text-white hover:bg-red-500 rounded-2xl transition-all duration-300 group shadow-sm active:scale-95">
                    <i class="fa-solid fa-arrow-right-from-bracket group-hover:-translate-x-1 transition-transform font-bold"></i> 
                    <span class="font-bold">Logout</span>
                </button></a>
            </div>
        </aside>

        <!-- Main -->
   <div class="flex-1 flex flex-col">

    <!-- Header -->
    <header class="h-20 bg-white flex items-center px-8 border-b sticky top-0 z-10">
        <div class="text-slate-500 text-sm">
            Menu / <span class="text-slate-900 font-bold">Kontak Center</span>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-8 overflow-auto bg-slate-50">
    <div class="max-w-6xl mx-auto animate-fade-up">
        <div class="bg-white p-6 rounded-2xl border shadow-sm">

            <!-- Title & Action Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 border-l-4 border-blue-500 pl-3 uppercase italic tracking-tighter">
                        Daftar Kontak Admin Jurusan
                    </h2>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1 ml-4">Total: <?= $total_data ?> Kontak Terdaftar</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <form method="GET" class="relative">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari admin..." 
                               class="w-full sm:w-64 pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-slate-400"></i>
                    </form>

                    <a href="../tambah_data/tambah_kontak_center.php" class="block">
                        <button class="w-full flex items-center justify-center gap-2 px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 active:scale-95">
                            <i class="fa-solid fa-plus"></i> Tambah
                        </button>
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="rounded-2xl border border-slate-100 overflow-hidden shadow-sm min-h-[400px]">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase font-black tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-center w-16">No</th>
                                <th class="px-6 py-4">Admin</th>
                                <th class="px-6 py-4">Jurusan</th>
                                <th class="px-6 py-4">WhatsApp</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-50 bg-white">
                            <?php if (!empty($daftar_kontak)): ?>
                                <?php $no = $offset + 1; foreach ($daftar_kontak as $row): ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-6 py-4 text-center text-slate-400 font-bold"><?= sprintf("%02d", $no++) ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="../image_center/<?= $row['foto_admin'] ?>" 
                                                 class="w-10 h-10 rounded-full border-2 border-white shadow-sm object-cover"
                                                 onerror="this.src='../image_center/default.jpg'">
                                            <span class="font-bold text-slate-700"><?= htmlspecialchars($row['nama_admin']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase"><?= htmlspecialchars($row['jurusan']) ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="https://wa.me/<?= $row['whatsapp'] ?>" target="_blank" class="flex items-center gap-2 text-green-600 font-bold hover:underline italic">
                                            <i class="fa-brands fa-whatsapp text-lg"></i> <?= htmlspecialchars($row['whatsapp']) ?>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2">
                                            <a href="../edit/edit_kontak_center.php?id=<?= $row['id_kontak'] ?>" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all flex items-center justify-center border border-amber-100">
                                                <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                            </a>
                                            <a href="../proses_hapus/hapus_data_kontak_center.php?id=<?= $row['id_kontak'] ?>" 
                                               onclick="return confirm('Hapus kontak ini?')"
                                               class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all flex items-center justify-center border border-rose-100">
                                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center text-slate-400 font-bold uppercase tracking-widest text-[10px]">Data Belum Tersedia</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination (Selalu Muncul) -->
            <div class="flex flex-col sm:flex-row justify-between items-center mt-8 gap-4 border-t border-slate-50 pt-6">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    Halaman <?= $page ?> dari <?= $total_halaman ?>
                </p>
                
                <div class="flex items-center gap-2">
                    <!-- Tombol Previous -->
                    <a href="?halaman=<?= max(1, $page - 1) ?>&search=<?= $search ?>" 
                       class="w-8 h-8 flex items-center justify-center rounded-xl border border-slate-200 text-slate-400 hover:border-blue-600 hover:text-blue-600 transition-all <?= ($page <= 1) ? 'opacity-30 pointer-events-none' : '' ?>">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </a>
                    
                    <!-- Angka Halaman -->
                    <?php for($i = 1; $i <= $total_halaman; $i++): ?>
                        <a href="?halaman=<?= $i ?>&search=<?= $search ?>" 
                           class="w-8 h-8 flex items-center justify-center rounded-xl font-bold text-[10px] transition-all <?= ($i == $page) ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-500 border border-slate-200 hover:border-blue-600 hover:text-blue-600' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <!-- Tombol Next -->
                    <a href="?halaman=<?= min($total_halaman, $page + 1) ?>&search=<?= $search ?>" 
                       class="w-8 h-8 flex items-center justify-center rounded-xl border border-slate-200 text-slate-400 hover:border-blue-600 hover:text-blue-600 transition-all <?= ($page >= $total_halaman) ? 'opacity-30 pointer-events-none' : '' ?>">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</main>
</div>
    </div>

</body>
</html>