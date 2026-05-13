<?php
// 1. Keamanan Session
session_start();

// Cek apakah user sudah login dan apakah perannya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php"); 
    exit();
}

// 2. Koneksi Database & Logika Pengambilan Data
require_once '../koneksi/koneksi.php'; 

$daftar_dosen = []; 
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    // Query dasar
    $query_sql = "SELECT * FROM data_dosen";

    // Jika ada input pencarian
    if (!empty($search)) {
        $query_sql .= " WHERE nama_lengkap LIKE :search OR nip LIKE :search";
    }

    $query_sql .= " ORDER BY id_dosen DESC";

    $stmt = $pdo->prepare($query_sql);

    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%');
    }

    $stmt->execute();
    $daftar_dosen = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Kesalahan Query: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Logo Web -->
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <!-- Logo web -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKM POLIJE - Data Dosen</title>
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
                    Menu / <span class="text-slate-900 font-bold">Data Dosen</span>
                </div>
            </header>

                <main class="flex-1 p-6 md:p-8 overflow-y-auto">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-md animate-fade-up w-full max-w-6xl mx-auto">
            
            <!-- Header & Search -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h2 class="text-xl font-bold text-slate-800 border-l-4 border-blue-500 pl-3">Daftar Dosen Pembimbing</h2>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <form method="GET" class="relative w-full sm:w-64">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                               placeholder="Cari nama atau NIP..." 
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-slate-600 focus:bg-white">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-sm"></i>
                    </form>
                    
                    <a href="../tambah_data/tambah_dosen.php">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2 shadow-sm shadow-blue-200 active:scale-95">
                            <i class="fa-solid fa-plus"></i> Tambah Dosen
                        </button>
                    </a>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="rounded-xl border border-slate-200 mb-6 overflow-hidden shadow-sm">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-100 text-slate-600 text-xs uppercase font-bold tracking-wide border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-12">No</th>
                            <th class="px-4 py-3.5">Profil Dosen</th>
                            <th class="px-4 py-3.5">Jurusan</th>
                            <th class="px-4 py-3.5 text-center">Riwayat Bimbingan</th>
                            <th class="px-4 py-3.5 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (!empty($daftar_dosen)): ?>
                            <?php $no = 1; foreach ($daftar_dosen as $row): ?>
                            <tr class="hover:bg-blue-50/40 transition-colors duration-200 even:bg-slate-50/60">
                                <td class="px-4 py-3 text-center text-slate-500 font-medium"><?= $no++; ?></td>
                               <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <?php 
                                            $namaFoto = $row['foto_dosen'];
                                            
                                            // 1. Path Absolut untuk pengecekan file di server
                                            // dirname(__DIR__) digunakan untuk keluar dari folder admin_panel ke root project
                                            $rootProject = dirname(__DIR__);
                                            $pathKeUpload = $rootProject . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . $namaFoto;

                                            // 2. URL untuk ditampilkan di browser
                                            // rawurlencode digunakan untuk menangani nama file yang mengandung spasi atau karakter unik
                                            $urlTampil = "../upload/" . rawurlencode($namaFoto);

                                            // 3. Logika Validasi: Cek apakah file benar-benar ada di folder 'upload'
                                            if (!empty($namaFoto) && file_exists($pathKeUpload)) {
                                                $finalFoto = $urlTampil;
                                            } else {
                                                // Jika file tidak ditemukan, gunakan avatar inisial
                                                $finalFoto = "https://ui-avatars.com/api/?name=" . urlencode($row['nama_lengkap']) . "&background=eff6ff&color=2563eb&rounded=true&bold=true";
                                            }
                                        ?>
                                        
                                        <img src="<?= $finalFoto ?>" 
                                            alt="Foto Dosen" 
                                            class="w-10 h-10 rounded-full object-cover border-2 border-slate-100 shadow-sm"
                                            onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($row['nama_lengkap']) ?>&background=fecaca&color=dc2626&rounded=true'">
                                            
                                        <div>
                                            <div class="font-bold text-slate-800"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                                            <div class="text-xs text-slate-500 font-mono mt-0.5">
                                                <i class="fa-regular fa-id-card mr-1 text-slate-400"></i> <?= htmlspecialchars($row['nip']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md bg-slate-100 border border-slate-200 text-xs font-medium">
                                        <?= htmlspecialchars($row['jurusan']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center items-center gap-1.5 text-sm text-slate-600">
                                        <div class="bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-lg border border-indigo-100 font-semibold flex items-center gap-1.5">
                                            <i class="fa-solid fa-users text-xs"></i> <?= $row['riwayat_bimbingan'] ?> Tim
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Menu Detail -->
                                        <a href="../detail_riwayat/detail_dosen.php?id=<?= $row['id_dosen'] ?>" 
                                        class="w-8 h-8 rounded bg-teal-50 text-teal-600 hover:bg-teal-500 hover:text-white flex items-center justify-center transition-all border border-teal-100" 
                                        title="Detail">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>

                                        <!-- Menu Edit -->
                                        <a href="../edit/edit_dosen.php?id=<?= $row['id_dosen'] ?>" 
                                        class="w-8 h-8 rounded bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-all border border-amber-100" 
                                        title="Edit">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>

                                        <!-- Menu Hapus -->
                                        <a href="../proses_hapus/hapus_data_dosen.php?id=<?= $row['id_dosen'] ?>" 
                                        onclick="return confirm('Yakin ingin menghapus data dosen <?= addslashes($row['nama_lengkap']) ?>?')" 
                                        class="w-8 h-8 rounded bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all border border-rose-100" 
                                        title="Hapus">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-slate-400 italic">Data dosen tidak tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500 border-t border-slate-100 pt-4">
                <div>Menampilkan <span class="font-bold text-slate-700"><?= count($daftar_dosen) ?></span> data dosen</div>
                <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-200 shadow-sm">
                    <button class="px-2.5 py-1.5 rounded hover:bg-slate-100 transition-all text-slate-400"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                    <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded font-bold shadow-sm">1</button>
                    <button class="px-2.5 py-1.5 rounded hover:bg-slate-100 transition-all text-slate-600"><i class="fa-solid fa-chevron-right text-xs"></i></button>
                </div>
            </div>
        </div>
    </main>

        </div>
    </div>

</body>
</html>