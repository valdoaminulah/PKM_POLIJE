<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php"); 
    exit();
}

// 2. Pengaturan Pagination
$limit = 5; // Dibatasi 5 data per halaman
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$offset = ($page - 1) * $limit;

// 3. Logika Pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    // Menghitung Total Data (untuk pagination)
    $sql_count = "SELECT COUNT(*) FROM data_pkm";
    if (!empty($search)) {
        $sql_count .= " WHERE nama_pkm LIKE :search OR singkatan LIKE :search";
    }
    $stmt_count = $pdo->prepare($sql_count);
    if (!empty($search)) {
        $stmt_count->bindValue(':search', '%' . $search . '%');
    }
    $stmt_count->execute();
    $total_data = $stmt_count->fetchColumn();
    $total_halaman = ceil($total_data / $limit);

    // Mengambil Data dengan Limit & Offset
    $sql_data = "SELECT * FROM data_pkm";
    if (!empty($search)) {
        $sql_data .= " WHERE nama_pkm LIKE :search OR singkatan LIKE :search";
    }
    $sql_data .= " ORDER BY id_pkm DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql_data);
    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%');
    }
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $daftar_pkm = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
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

        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50">
            
            <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 flex items-center px-8 border-b border-slate-200 shadow-sm">
                <div class="text-slate-400 text-sm font-medium tracking-wide">
                    Menu / <span class="text-slate-900 font-bold">Informasi PKM</span>
                </div>
            </header>

            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-md animate-fade-up w-full max-w-6xl mx-auto">
            
            <!-- Header & Search -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h2 class="text-xl font-bold text-slate-800 border-l-4 border-blue-500 pl-3">Daftar Kategori & Panduan PKM</h2>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <form method="GET" class="relative w-full sm:w-64">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                               placeholder="Cari kategori..." 
                               class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400"></i>
                    </form>
                    <a href="../tambah_data/tambah_pkm.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 transition-all">
                        <i class="fa-solid fa-plus"></i> Tambah PKM
                    </a>
                </div>
            </div>

            <!-- Tabel -->
            <div class="rounded-xl border border-slate-200 mb-6 overflow-hidden shadow-sm overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-600 text-xs uppercase font-bold border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 text-center w-12">No</th>
                            <th class="px-4 py-4 w-28">Cover</th> 
                            <th class="px-4 py-4 w-32">Kategori</th> 
                            <th class="px-4 py-4">Ringkasan Panduan</th>
                            <th class="px-4 py-4 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <?php if (!empty($daftar_pkm)): ?>
                            <?php $no = $offset + 1; foreach ($daftar_pkm as $row): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 text-center text-slate-500"><?= $no++; ?></td>
                                <td class="px-4 py-4">
                                    <div class="w-20 h-14 rounded-lg overflow-hidden border bg-slate-100">
                                        <img src="../upload/<?= htmlspecialchars($row['foto_pkm']) ?>" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($row['singkatan']) ?>&background=random'">
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg font-bold border border-blue-100"><?= htmlspecialchars($row['singkatan']) ?></span>
                                </td>
                                <td class="px-4 py-4">
                                    <h4 class="font-bold text-slate-700 mb-0.5"><?= htmlspecialchars($row['nama_pkm']) ?></h4>
                                    <p class="text-xs text-slate-500 line-clamp-2"><?= htmlspecialchars($row['deskripsi_singkat']) ?></p>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="../detail_riwayat/detail_pkm.php?id=<?= $row['id_pkm'] ?>" class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100 hover:bg-teal-500 hover:text-white transition-all"><i class="fa-solid fa-eye"></i></a>
                                        <a href="../edit/edit_pkm.php?id=<?= $row['id_pkm'] ?>" class="w-8 h-8 rounded bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 hover:bg-amber-500 hover:text-white transition-all"><i class="fa-solid fa-pen text-xs"></i></a>
                                        <a href="../proses_hapus/hapus_data_pkm.php ?id=<?= $row['id_pkm'] ?>" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')" class="w-8 h-8 rounded bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 hover:bg-rose-500 hover:text-white transition-all"><i class="fa-solid fa-trash text-xs"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="px-4 py-10 text-center text-slate-400">Tidak ada data ditemukan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination (Limit 5) -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500 border-t border-slate-100 pt-6">
                <div>
                    Menampilkan <span class="font-bold text-slate-800"><?= count($daftar_pkm) ?></span> dari <span class="font-bold text-slate-800"><?= $total_data ?></span> Kategori PKM
                </div>
                
                <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-200">
                    <!-- Tombol Prev -->
                    <a href="?halaman=<?= max(1, $page - 1) ?>&search=<?= urlencode($search) ?>" 
                       class="px-2 py-1.5 rounded hover:bg-slate-100 <?= ($page <= 1) ? 'pointer-events-none opacity-40' : '' ?>">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </a>

                    <!-- Nomor Halaman -->
                    <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                        <a href="?halaman=<?= $i ?>&search=<?= urlencode($search) ?>" 
                           class="w-8 h-8 flex items-center justify-center rounded font-bold transition-all <?= ($i == $page) ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-100 text-slate-500' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <!-- Tombol Next -->
                    <a href="?halaman=<?= min($total_halaman, $page + 1) ?>&search=<?= urlencode($search) ?>" 
                       class="px-2 py-1.5 rounded hover:bg-slate-100 <?= ($page >= $total_halaman) ? 'pointer-events-none opacity-40' : '' ?>">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>
    </main>

        </div>
    </div>

</body>
</html>