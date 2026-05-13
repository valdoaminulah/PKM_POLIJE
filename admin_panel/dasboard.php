<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Halaman
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php"); 
    exit();
}

try {
    // 2. Ambil Data Statistik secara Dinamis sesuai Nama Tabel Anda
    
    // Hitung Total Mahasiswa (Tabel: user_mahasiswa)
    $total_mhs = $pdo->query("SELECT COUNT(*) FROM user_mahasiswa")->fetchColumn();
    
    // Hitung Total Dosen (Tabel: data_dosen)
    $total_dsn = $pdo->query("SELECT COUNT(*) FROM data_dosen")->fetchColumn();
    
    // Hitung Total Broadcast (Tabel: pesan)
    $total_broadcast = $pdo->query("SELECT COUNT(*) FROM pesan")->fetchColumn();
    
    // Hitung Timeline Aktif (Tabel: jadwal_pkm)
    $total_timeline = $pdo->query("SELECT COUNT(*) FROM jadwal_pkm")->fetchColumn();

} catch (PDOException $e) {
    // Tampilkan pesan error jika tabel belum dibuat atau nama salah
    die("Kesalahan Database: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKM POLIJE - Dashboard</title>
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
        <!-- Sidebar -->
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
                    </button>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50">
            <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 flex items-center px-8 border-b border-slate-200 shadow-sm">
                <div class="text-slate-400 text-sm font-medium tracking-wide">
                    Menu / <span class="text-slate-900 font-bold">Dashboard</span>
                </div>
            </header>

            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    <!-- Statistik Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-up mb-8">
                        
                        <!-- Card Mahasiswa -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                    <i class="fa-solid fa-user-graduate"></i>
                                </div>
                                <span class="text-[10px] uppercase tracking-wider font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">Aktif</span>
                            </div>
                            <p class="text-slate-500 font-medium text-sm mb-1">Total Mahasiswa</p>
                            <h3 class="text-3xl font-bold text-slate-800"><?= number_format($total_mhs) ?></h3>
                        </div>

                        <!-- Card Dosen -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                </div>
                                <span class="text-[10px] uppercase tracking-wider font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">Aktif</span>
                            </div>
                            <p class="text-slate-500 font-medium text-sm mb-1">Total Dosen</p>
                            <h3 class="text-3xl font-bold text-slate-800"><?= number_format($total_dsn) ?></h3>
                        </div>

                        <!-- Card Broadcast -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-xl group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                                    <i class="fa-solid fa-bullhorn"></i>
                                </div>
                                <span class="text-[10px] uppercase tracking-wider font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">Aktif</span>
                            </div>
                            <p class="text-slate-500 font-medium text-sm mb-1">Total Broadcast</p>
                            <h3 class="text-3xl font-bold text-slate-800"><?= number_format($total_broadcast) ?></h3>
                        </div>

                        <!-- Card Timeline -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-xl group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300">
                                    <i class="fa-solid fa-calendar-check"></i>
                                </div>
                                <span class="text-[10px] uppercase tracking-wider font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">Aktif</span>
                            </div>
                            <p class="text-slate-500 font-medium text-sm mb-1">Timeline Aktif</p>
                            <h3 class="text-3xl font-bold text-slate-800"><?= number_format($total_timeline) ?></h3>
                        </div>
                    </div>

                    <!-- Welcome Banner -->
                    <div class="animate-fade-up bg-gradient-to-br from-blue-600 via-indigo-600 to-indigo-800 rounded-2xl p-8 relative overflow-hidden shadow-lg shadow-indigo-200/50" style="animation-delay: 0.2s;">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-2xl"></div>
                        <div class="relative z-10 md:w-2/3">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/10 border border-white/20 text-white text-xs font-medium mb-4 backdrop-blur-sm">
                                <i class="fa-solid fa-sparkles text-amber-300"></i> Update Terbaru
                            </div>
                            <h2 class="text-white text-3xl font-bold mb-3 leading-tight">Selamat Datang, <span class="text-blue-200">Admin!</span> 👋</h2>
                            <p class="text-blue-50/90 text-base font-normal leading-relaxed">
                                Sistem Informasi PKM POLIJE siap digunakan. Pantau aktivitas pendaftaran dan progres program mahasiswa secara real-time dengan mudah.
                            </p>
                            <a href="./broadcast.php" class="mt-6 inline-flex px-6 py-2.5 bg-white text-indigo-600 font-semibold rounded-xl shadow-sm hover:shadow-md transition-all active:scale-95 items-center gap-2">
                                <i class="fa-solid fa-bolt text-amber-500"></i> Kelola Pengumuman
                            </a>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

</body>
</html>