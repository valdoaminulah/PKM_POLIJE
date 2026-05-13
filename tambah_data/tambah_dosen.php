<!-- Secition Start -->
<?php
session_start();

// 1. Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, tendang ke halaman login
    header("Location: ../login_user/login_user.php"); 
    exit();
}

// 2. Cek apakah yang login benar-benar Admin
// Ini mencegah Mahasiswa atau Humas "iseng" masuk ke dashboard admin
if ($_SESSION['role'] !== 'admin') {
    echo "<script>
            alert('Akses Ditolak! Halaman ini hanya untuk Admin.');
            window.location.href = '../login_user/login_user.php';
          </script>";
    exit();
}

?>
<!-- end -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Tambah Dosen - Polije</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-4 md:p-10">

    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Header -->
        <div class="bg-blue-700 px-8 py-5 flex justify-between items-center">
            <h2 class="text-white text-xl font-bold uppercase tracking-wider italic">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Data Dosen
            </h2>
            <span class="text-blue-200 text-xs">Data Dosen PKM - Polije</span>
        </div>

        <form action="../proses_tambah/proses_tambah_dosen.php" method="POST" enctype="multipart/form-data" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                
                <!-- 1. Foto Dosen (Baris Penuh) -->
                <div class="md:col-span-2 flex items-center space-x-6 bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300 mb-2">
                    <div class="w-20 h-20 rounded-full border-2 border-white shadow-md overflow-hidden bg-gray-200 flex items-center justify-center">
                        <img id="preview-img" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                        <i id="placeholder-icon" class="fas fa-user-tie text-gray-400 text-3xl"></i>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Foto Profil Dosen</label>
                        <input type="file" name="foto_dosen" id="foto_input" accept="image/*" 
                               class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                    </div>
                </div>

                <!-- 2. Nama & NIP (Sejajar) -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase">Nama Lengkap Dosen</label>
                    <input type="text" name="nama" placeholder="Nama dan Gelar..." class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase">NIP Dosen</label>
                    <input type="number" name="nip" placeholder="Masukkan NIP..." class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm" required>
                </div>

                <!-- 3. Jurusan & No WA (Sejajar) -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase">Jurusan</label>
                    <select name="jurusan" class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm bg-white" required>
                        <option value="">Pilih Jurusan</option>
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
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase">Nomor WhatsApp</label>
                    <input type="tel" name="wa" placeholder="08xxxxxxxxxx" class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm" required>
                </div>

                <!-- 4. Email & LinkedIn Name (Sejajar) -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase">Email Instansi</label>
                    <input type="email" name="email" placeholder="email@polije.ac.id" class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase italic">Nama Akun LinkedIn</label>
                    <input type="text" name="linkedin_name" placeholder="Nama Profil LinkedIn..." class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm">
                </div>

                <!-- 5. Instagram & Facebook Name (Sejajar) -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase italic">Username Instagram</label>
                    <input type="text" name="instagram" placeholder="@username" class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase italic">Nama Akun Facebook</label>
                    <input type="text" name="facebook_name" placeholder="Nama Profil Facebook..." class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm">
                </div>

                <!-- 6. Riwayat Bimbingan (Baris Penuh) -->
                <div class="md:col-span-2 space-y-2 bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <label class="block text-sm font-bold text-blue-800 uppercase">Total Riwayat Bimbingan PKM</label>
                    <div class="flex items-center space-x-4">
                        <input type="number" name="riwayat_bimbingan" value="0" min="0" class="w-32 rounded-lg border-blue-200 border p-3 text-center text-xl font-bold outline-none focus:ring-2 focus:ring-blue-500 shadow-inner">
                        <span class="text-blue-600 font-semibold italic uppercase text-xs">Total Bimbingan</span>
                    </div>
                </div>
            </div>

            <!-- Bagian Tombol Aksi -->
            <div class="mt-10 pt-6 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <!-- Tombol Kembali (Pengganti Reset) -->
                <a href="../admin_panel/data_dosen.php" class="w-full md:w-auto px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition text-center uppercase tracking-widest text-xs flex items-center justify-center shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>

                <!-- Tombol Simpan -->
                <button type="submit" class="w-full md:w-auto px-12 py-3 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 shadow-md transform hover:scale-[1.02] transition uppercase tracking-widest text-sm flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i> Simpan Data Dosen
                </button>
            </div>
        </form>
    </div>

    <!-- Script Preview Foto -->
    <script>
        const fotoInput = document.getElementById('foto_input');
        const previewImg = document.getElementById('preview-img');
        const placeholderIcon = document.getElementById('placeholder-icon');

        fotoInput.onchange = evt => {
            const [file] = fotoInput.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file);
                previewImg.classList.remove('hidden');
                placeholderIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>