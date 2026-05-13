<?php
session_start();
require_once '../koneksi/koneksi.php';

// Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal PKM - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] min-h-screen p-4 md:p-10">

    <div class="max-w-2xl mx-auto">
        <!-- Header Card -->
        <div class="bg-white border border-slate-200 rounded-t-3xl p-6 md:p-8 border-b-0">
            <div class="flex items-center gap-4">
                <div class="bg-emerald-500 w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200">
                    <i class="fas fa-calendar-plus text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-slate-800 font-black text-2xl tracking-tighter uppercase italic">Input Jadwal Baru</h1>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-0.5">Manajemen Timeline Kegiatan PKM</p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form action="../proses_tambah/proses_tambah_timeline.php" method="POST" class="space-y-0">
            
            <div class="bg-white p-6 md:p-8 border-x border-slate-200 space-y-6">
                
                <!-- Judul Jadwal -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Judul Kegiatan / Jadwal</label>
                    <div class="relative">
                        <input type="text" name="judul_jadwal" placeholder="Contoh: Pendaftaran Internal Tahap 1" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition font-bold text-slate-700 shadow-sm" required>
                        <i class="fas fa-tasks absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                    </div>
                </div>

                <!-- Grid Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Tanggal Mulai -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-emerald-600">Tanggal Dimulai</label>
                        <div class="relative">
                            <input type="date" name="tgl_mulai" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition font-bold text-slate-700 shadow-sm" required>
                            <i class="fas fa-calendar-day absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                        </div>
                    </div>

                    <!-- Tanggal Berakhir -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-rose-600">Tanggal Berakhir</label>
                        <div class="relative">
                            <input type="date" name="tgl_berakhir" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white transition font-bold text-slate-700 shadow-sm" required>
                            <i class="fas fa-calendar-check absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                        </div>
                    </div>
                </div>

                <!-- Keterangan Tambahan (Opsional) -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan Singkat</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan rincian jika diperlukan..." 
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition text-sm text-slate-600 shadow-sm"></textarea>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="bg-slate-50 p-6 md:p-8 rounded-b-3xl border border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <a href="../admin_panel/timeline.php" class="text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:text-rose-500 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                
                <button type="submit" class="w-full md:w-auto px-10 py-3 bg-emerald-500 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-xl shadow-emerald-900/10 hover:bg-emerald-600 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>

</body>
</html>