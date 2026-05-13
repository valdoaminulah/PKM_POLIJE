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
    <title>Kirim Broadcast - Admin PKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] min-h-screen p-4 md:p-10">

    <div class="max-w-3xl mx-auto">
        <!-- Header Card -->
        <div class="bg-white border border-slate-200 rounded-t-3xl p-6 md:p-8 border-b-0">
            <div class="flex items-center gap-4">
                <div class="bg-indigo-600 w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200">
                    <i class="fas fa-paper-plane text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-slate-800 font-black text-2xl tracking-tighter uppercase italic">Kirim Broadcast</h1>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-0.5">Kirim pengumuman massal ke civitas</p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form action="../proses_tambah/proses_tambah_broadcast.php" method="POST" class="space-y-0">
            
            <div class="bg-white p-6 md:p-8 border-x border-slate-200 space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Judul Pesan -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Judul Pesan</label>
                        <div class="relative">
                            <input type="text" name="judul_pesan" placeholder="Contoh: Pengingat Batas Proposal" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition font-bold text-slate-700 shadow-sm" required>
                            <i class="fas fa-bullhorn absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                        </div>
                    </div>

                    <!-- Tujuan Pesan (Dropdown) -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tujuan Penerima</label>
                        <div class="relative">
                            <select name="tujuan_pesan" 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition font-bold text-slate-700 shadow-sm appearance-none cursor-pointer" required>
                                <option value="" disabled selected>Pilih Tujuan...</option>
                                <option value="Semua">Semua Civitas</option>
                                <option value="Mahasiswa">Khusus Mahasiswa</option>
                                <option value="Dosen">Khusus Dosen</option>
                            </select>
                            <i class="fas fa-users absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                            <i class="fas fa-chevron-down absolute right-4 top-4 text-slate-300 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- Isi Pesan (Longtext/Textarea) -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Isi Pesan / Pengumuman</label>
                    <textarea name="isi_pesan" rows="8" placeholder="Tuliskan detail pengumuman di sini secara lengkap..." 
                              class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition text-slate-600 shadow-sm leading-relaxed" required></textarea>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="bg-slate-50 p-6 md:p-8 rounded-b-3xl border border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <a href="../admin_panel/broadcast.php" class="text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:text-rose-500 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
                
                <button type="submit" class="w-full md:w-auto px-10 py-3 bg-indigo-600 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-xl shadow-indigo-900/20 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-share-square"></i> Kirim Pesan SEKARANG
                </button>
            </div>
        </form>
    </div>

</body>
</html>