<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 2. Konfigurasi Pagination & Search
$limit = 6;
$page = isset($_GET['halaman']) ? (int)$GET['halaman'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    // Hitung total data untuk pagination
    $query_count = "SELECT COUNT(*) FROM pesan";
    if (!empty($search)) {
        $query_count .= " WHERE judul_pesan LIKE :search OR isi_pesan LIKE :search";
    }
    $stmt_count = $pdo->prepare($query_count);
    if (!empty($search)) $stmt_count->bindValue(':search', '%' . $search . '%');
    $stmt_count->execute();
    $total_data = $stmt_count->fetchColumn();
    $total_halaman = ceil($total_data / $limit);
    $total_halaman = ($total_halaman < 1) ? 1 : $total_halaman;

    // Ambil data pesan
    $query_pesan = "SELECT * FROM pesan";
    if (!empty($search)) {
        $query_pesan .= " WHERE judul_pesan LIKE :search OR isi_pesan LIKE :search";
    }
    $query_pesan .= " ORDER BY tgl_kirim DESC LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($query_pesan);
    if (!empty($search)) $stmt->bindValue(':search', '%' . $search . '%');
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $daftar_pesan = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fungsi format tanggal
function formatTgl($timestamp) {
    return date('d M Y, H:i', strtotime($timestamp));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKM POLIJE - Broadcast</title>
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
                    Menu / <span class="text-slate-900 font-bold">Broadcast</span>
                </div>
            </header>

            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-md animate-fade-up w-full max-w-6xl mx-auto">
        
        <!-- Header & Search -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-xl font-bold text-slate-800 border-l-4 border-blue-500 pl-3">Riwayat Pesan Broadcast</h2>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <form method="GET" class="relative w-full sm:w-64">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari judul pesan..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-slate-600 focus:bg-white">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                </form>
                
                <a href="../tambah_data/tambah_broadcast.php">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2 shadow-sm shadow-blue-200 active:scale-95 w-full">
                        <i class="fa-solid fa-paper-plane"></i> Buat Pesan
                    </button>
                </a>
            </div>
        </div>

        <!-- Tabel Riwayat -->
        <div class="rounded-xl border border-slate-200 mb-6 overflow-hidden shadow-sm overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-100 text-slate-600 text-xs uppercase font-bold tracking-wide border-b border-slate-200 whitespace-nowrap">
                    <tr>
                        <th class="px-4 py-3.5 text-center w-12">No</th>
                        <th class="px-4 py-3.5 w-64">Judul Pesan</th>
                        <th class="px-4 py-3.5 w-40">Tujuan</th>
                        <th class="px-4 py-3.5">Isi Pesan Singkat</th>
                        <th class="px-4 py-3.5 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (!empty($daftar_pesan)): ?>
                        <?php $no = $offset + 1; foreach ($daftar_pesan as $row): ?>
                        <tr class="hover:bg-blue-50/40 transition-colors duration-200 even:bg-slate-50/60">
                            <td class="px-4 py-4 text-center text-slate-500 font-medium align-top"><?= $no++ ?></td>
                            <td class="px-4 py-4 align-top">
                                <div class="font-bold text-slate-800"><?= htmlspecialchars($row['judul_pesan']) ?></div>
                                <div class="text-xs text-slate-400 mt-1"><i class="fa-regular fa-clock mr-1"></i> <?= formatTgl($row['tgl_kirim']) ?></div>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <?php 
                                    $badgeClass = "bg-blue-50 text-blue-700 border-blue-100";
                                    $icon = "fa-users";
                                    if($row['tujuan_pesan'] == 'Dosen') { $badgeClass = "bg-purple-50 text-purple-700 border-purple-100"; $icon = "fa-chalkboard-user"; }
                                    if($row['tujuan_pesan'] == 'Semua') { $badgeClass = "bg-orange-50 text-orange-700 border-orange-100"; $icon = "fa-globe"; }
                                ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold border <?= $badgeClass ?>">
                                    <i class="fa-solid <?= $icon ?> text-[10px]"></i> <?= $row['tujuan_pesan'] ?>
                                </span>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <p class="text-slate-600 leading-relaxed line-clamp-2">
                                    <?= htmlspecialchars($row['isi_pesan']) ?>
                                </p>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Tombol Detail (Menggunakan tag <a>) -->
                                    <a href="../detail_riwayat/detail_broadcast.php?id=<?= $row['id_pesan'] ?>" 
                                    class="w-8 h-8 rounded bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all border border-blue-100" title="Lihat Detail">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>

                                    <!-- Tombol Edit -->
                                    <a href="../edit/edit_broadcast.php?id=<?= $row['id_pesan'] ?>" 
                                    class="w-8 h-8 rounded bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-all border border-amber-100" title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <a href="../proses_hapus/hapus_data_broadcast.php?id=<?= $row['id_pesan'] ?>" onclick="return confirm('Hapus pesan ini?')" 
                                    class="w-8 h-8 rounded bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all border border-rose-100" title="Hapus">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-slate-400 italic">Tidak ada riwayat pesan ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500 border-t border-slate-100 pt-4">
            <div>Menampilkan <span class="font-bold text-slate-700"><?= count($daftar_pesan) ?></span> dari <span class="font-bold text-slate-700"><?= $total_data ?></span> pesan</div>
            
            <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-200 shadow-sm">
                <a href="?halaman=<?= max(1, $page - 1) ?>&search=<?= urlencode($search) ?>" class="px-2.5 py-1.5 rounded hover:bg-slate-100 transition-all text-slate-400 <?= ($page <= 1) ? 'pointer-events-none opacity-50' : '' ?>">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>
                
                <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                    <a href="?halaman=<?= $i ?>&search=<?= urlencode($search) ?>" class="w-8 h-8 flex items-center justify-center rounded font-bold transition-all <?= ($i == $page) ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <a href="?halaman=<?= min($total_halaman, $page + 1) ?>&search=<?= urlencode($search) ?>" class="px-2.5 py-1.5 rounded hover:bg-slate-100 transition-all text-slate-600 <?= ($page >= $total_halaman) ? 'pointer-events-none opacity-50' : '' ?>">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
            </div>
        </div>

    </div>
</main>

        </div>
    </div>

</body>
</html>