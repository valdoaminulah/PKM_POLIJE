<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 2. Pengaturan Pagination & Search
$limit = 6; 
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    // Hitung Total untuk Pagination
    $sql_count = "SELECT COUNT(*) FROM jadwal_pkm";
    if (!empty($search)) {
        $sql_count .= " WHERE judul_jadwal LIKE :search";
    }
    $stmt_count = $pdo->prepare($sql_count);
    if (!empty($search)) $stmt_count->bindValue(':search', '%' . $search . '%');
    $stmt_count->execute();
    $total_data = $stmt_count->fetchColumn();
    $total_halaman = ceil($total_data / $limit);
    $total_halaman = ($total_halaman < 1) ? 1 : $total_halaman;

    // Ambil Data Agenda
    $sql_data = "SELECT * FROM jadwal_pkm";
    if (!empty($search)) {
        $sql_data .= " WHERE judul_jadwal LIKE :search";
    }
    $sql_data .= " ORDER BY tgl_mulai ASC LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($sql_data);
    if (!empty($search)) $stmt->bindValue(':search', '%' . $search . '%');
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $daftar_agenda = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fungsi bantu untuk format tanggal Indonesia
function tgl_indo($tanggal) {
    return date('d M Y', strtotime($tanggal));
}
?>
<!-- end -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKM POLIJE - Timeline Minimalis</title>
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
                    Menu / <span class="text-slate-900 font-bold">Timeline PKM</span>
                </div>
            </header>

            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
    <div class="max-w-5xl mx-auto animate-fade-up">
        
        <!-- Header & Search -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 border-l-4 border-blue-500 pl-4">Agenda Kegiatan</h2>
                <p class="text-slate-500 text-sm ml-4 mt-1">Daftar jadwal penting PKM.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <form method="GET" class="relative w-full sm:w-64">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari agenda..." class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-slate-400 text-sm"></i>
                </form>
                <a href="../tambah_data/tambah_timeline.php">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-100 active:scale-95 w-full">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </button>
                </a>
            </div>
        </div>

        <!-- Timeline List -->
        <div class="relative">
            <!-- Line Timeline Vertikal -->
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500/10 rounded-full"></div>

            <div class="space-y-4">
                <?php if (!empty($daftar_agenda)): ?>
                    <?php $no = $offset + 1; foreach ($daftar_agenda as $row): ?>
                    <div class="relative pl-6 group">
                        <!-- Dot Timeline -->
                        <div class="absolute left-[-2px] top-1/2 -translate-y-1/2 w-2.5 h-2.5 rounded-full bg-blue-500 ring-4 ring-slate-50 group-hover:scale-125 transition-transform"></div>
                        
                        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow-md hover:border-blue-200 transition-all">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                
                                <div class="flex items-center gap-4">
                                    <span class="text-[10px] font-black text-blue-600 uppercase bg-blue-50 px-2 py-1 rounded border border-blue-100 shrink-0">
                                        <?= sprintf("%02d", $no++); ?>
                                    </span>
                                    <div>
                                        <h3 class="font-bold text-slate-800"><?= htmlspecialchars($row['judul_jadwal']) ?></h3>
                                        <?php if(!empty($row['keterangan'])): ?>
                                            <p class="text-[10px] text-slate-400 mt-0.5"><?= htmlspecialchars($row['keterangan']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-6">
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-calendar-check text-blue-500 text-[10px]"></i>
                                            <span class="text-xs text-slate-400">Mulai: <b class="text-slate-600 font-semibold"><?= tgl_indo($row['tgl_mulai']) ?></b></span>
                                        </div>
                                        <div class="flex items-center gap-2 border-l border-slate-200 pl-4">
                                            <i class="fa-solid fa-calendar-xmark text-rose-500 text-[10px]"></i>
                                            <span class="text-xs text-slate-400">Tenggat: <b class="text-slate-600 font-semibold"><?= tgl_indo($row['tgl_berakhir']) ?></b></span>
                                        </div>
                                    </div>

                                    <!-- Tombol Aksi -->
                                    <div class="flex gap-1">
                                        <a href="../edit/edit_timeline.php?id=<?= $row['id_jadwal'] ?>" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white border border-amber-100 transition-all flex items-center justify-center" title="Edit">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <a href="../proses_hapus/hapus_data_timeline.php?id=<?= $row['id_jadwal'] ?>" onclick="return confirm('Hapus agenda ini?')" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white border border-rose-100 transition-all flex items-center justify-center" title="Hapus">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="pl-6 text-slate-400 italic text-sm">Tidak ada agenda ditemukan.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pagination Bar (Permanen) -->
        <div class="flex flex-col md:flex-row justify-between items-center mt-8 gap-4 text-sm text-slate-400 border-t border-slate-200 pt-6">
            <p>Total <span class="font-bold text-slate-700"><?= $total_data ?></span> Agenda</p>
            
            <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-200 shadow-sm">
                <!-- Prev -->
                <a href="?halaman=<?= max(1, $page - 1) ?>&search=<?= urlencode($search) ?>" 
                   class="w-8 h-8 flex items-center justify-center rounded hover:bg-slate-50 transition-all text-slate-400 <?= ($page <= 1) ? 'pointer-events-none opacity-40' : '' ?>">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </a>

                <!-- Numbers -->
                <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                    <a href="?halaman=<?= $i ?>&search=<?= urlencode($search) ?>" 
                       class="w-8 h-8 flex items-center justify-center rounded font-bold transition-all <?= ($i == $page) ? 'bg-blue-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <!-- Next -->
                <a href="?halaman=<?= min($total_halaman, $page + 1) ?>&search=<?= urlencode($search) ?>" 
                   class="w-8 h-8 flex items-center justify-center rounded hover:bg-slate-50 transition-all text-slate-600 <?= ($page >= $total_halaman) ? 'pointer-events-none opacity-40' : '' ?>">
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