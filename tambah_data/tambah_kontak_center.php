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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kontak Admin - PKM POLIJE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-10 flex justify-center items-start">

    <div class="max-w-2xl w-full">
        <!-- Header Card -->
        <div class="bg-white border border-slate-200 rounded-t-3xl p-6 md:p-8 border-b-0 shadow-sm">
            <div class="flex items-center gap-4 text-left">
                <div class="bg-blue-600 w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200 animate-pulse">
                    <i class="fas fa-user-plus text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-slate-800 font-black text-2xl tracking-tighter uppercase italic">Tambah Kontak</h1>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-0.5">Daftarkan admin jurusan baru ke sistem</p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form action="../proses_tambah/proses_tambah_kontak_center.php" method="POST" enctype="multipart/form-data" class="space-y-0 shadow-xl">
            
            <div class="bg-white p-6 md:p-8 border-x border-slate-200 space-y-6">
                
                <!-- Nama & Jurusan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Nama Lengkap</label>
                        <div class="relative">
                            <input type="text" name="nama_admin" placeholder="Contoh: Budi Santoso, S.Kom" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition font-bold text-slate-700" required>
                            <i class="fas fa-user absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Jurusan</label>
                        <div class="relative text-left">
                            <select name="jurusan" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition font-bold text-slate-700 appearance-none cursor-pointer" required>
                                <option value="" disabled selected>Pilih Jurusan...</option>
                                <option value="Produksi Pertanian">Produksi Pertanian</option>
                                <option value="Teknologi Pertanian">Teknologi Pertanian</option>
                                <option value="Peternakan">Peternakan</option>
                                <option value="Manajemen Agribisnis">Manajemen Agribisnis</option>
                                <option value="Teknologi Informasi">Teknologi Informasi</option>
                                <option value="Bahasa, Komunikasi dan Pariwisata">Bahasa, Komunikasi dan Pariwisata</option>
                                <option value="Kesehatan">Kesehatan</option>
                                <option value="Teknik">Teknik</option>
                                <option value="Bisnis">Bisnis</option>
                            </select>
                            <i class="fas fa-graduation-cap absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                            <i class="fas fa-chevron-down absolute right-4 top-4 text-slate-300 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- WhatsApp & Lokasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Nomor WhatsApp</label>
                        <div class="relative">
                            <input type="number" name="whatsapp" placeholder="Contoh: 081234567..." 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition font-bold text-slate-700" required>
                            <i class="fa-brands fa-whatsapp absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Lokasi Kantor</label>
                        <div class="relative">
                            <input type="text" name="lokasi" placeholder="Contoh: Gedung J, Lantai 2" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition font-bold text-slate-700" required>
                            <i class="fas fa-map-marker-alt absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                        </div>
                    </div>
                </div>

                <!-- Foto Profile -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Foto Profile Admin</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-slate-400 text-2xl mb-2"></i>
                                <p class="text-xs text-slate-500 font-medium">Klik untuk upload foto (JPG/PNG)</p>
                            </div>
                            <input type="file" name="foto_admin" class="hidden" accept="image/*" />
                        </label>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="bg-slate-50 p-6 md:p-8 rounded-b-3xl border border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <a href="../admin_panel/kontak_center.php" class="text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:text-rose-500 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                </a>
                
                <button type="submit" class="w-full md:w-auto px-10 py-3 bg-blue-600 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-xl shadow-blue-900/20 hover:bg-blue-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Simpan Kontak Baru
                </button>
            </div>
        </form>
    </div>

</body>
</html>