<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['status_login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// CEK APAKAH USER SUDAH MEMBUAT TIM
$query_cek = $pdo->prepare("SELECT id_tim FROM data_tim_pkm WHERE id_ketua = :id_user");
$query_cek->execute([':id_user' => $id_user]);
$sudah_ada_tim = $query_cek->fetch();

// Jika sudah ada tim, kita akan menampilkan pesan nanti di bagian body
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tim</title>
    <!-- Logo Broser -->
     <link rel="icon" type="image/png" href="../img/logoPolije.png">
    <!-- Logo Broser -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50">

    <!-- Navbar -->

            <nav class="bg-white border-b border-gray-100 px-4 py-3 sticky top-0 z-[100]">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="../img/LogoPKM.png" alt="Logo" class="h-10">
                        <div class="h-8 w-[1px] bg-gray-200"></div>
                        <div class="flex flex-col">
                            <span class="font-bold text-blue-900 leading-tight">PKM</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Politeknik Negeri Jember</span>
                        </div>
                    </div>

                    <button id="menu-toggle" class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>

                    <div id="nav-menu" class="hidden md:flex absolute md:relative top-full left-0 w-full md:w-auto bg-white md:bg-transparent px-4 md:px-0 py-6 md:py-0 flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-1 shadow-2xl md:shadow-none border-t md:border-none border-gray-50">
                        
                        <a href="./beranda.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Beranda</a>
                        
                        <a href="./buat_tim.php" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold text-sm shadow-lg shadow-blue-200 text-center">Buat Tim</a>
                        
                        <a href="./daftar_tim.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Daftar Tim</a>
                        
                        <a href="./rekrut_tim.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Rekrut</a>

                        <div class="md:hidden pt-4 border-t border-gray-100 mt-2">
                            <a href="../proses/proses_logout.php" class="flex justify-center items-center space-x-2 bg-red-600 text-white px-5 py-3 rounded-full font-bold text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Log Out</span>
                            </a>
                        </div>
                    </div>

                    <div class="hidden md:block">
                        <a href="../proses/proses_logout.php" class="flex items-center space-x-2 bg-red-600 text-white px-5 py-2 rounded-full font-bold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Log Out</span>
                        </a>
                    </div>
                </div>
            </nav>

    <!-- Navbar -->


    <main class="max-w-4xl mx-auto px-4 py-8">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Buat Tim Baru</h1>
        <p class="text-gray-500">Lengkapi formulir di bawah untuk mendaftarkan tim PKM kamu.</p>
    </div>

    <?php if ($sudah_ada_tim): ?>
        <div class="bg-amber-50 border border-amber-200 rounded-3xl p-8 md:p-12 text-center">
            <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-amber-900 mb-2">Kamu Sudah Memiliki Tim</h2>
            <p class="text-amber-700 mb-6">Sesuai aturan SIM-PKM Polije, setiap mahasiswa hanya diperbolehkan membuat 1 tim sebagai ketua. Kamu bisa mengelola tim kamu di menu Daftar Tim.</p>
            <div class="flex justify-center gap-4">
                <a href="./daftar_tim.php" class="bg-amber-600 text-white px-8 py-3 rounded-full font-bold shadow-lg shadow-amber-200 hover:bg-amber-700 transition">Kelola Tim Saya</a>
            </div>
        </div>

    <?php else: ?>
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="../proses/proses_buat_tim.php" method="POST" class="p-8 md:p-12">
                <div class="grid grid-cols-1 gap-8">
                     <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-blue-400 font-bold">Ketua Tim / Pembuat</p>
                            <p class="text-blue-900 font-bold text-lg"><?php echo $_SESSION['nama']; ?></p>
                        </div>
                        <div class="bg-white px-4 py-2 rounded-xl border border-blue-200">
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">NIM</p>
                            <p class="text-blue-700 font-mono font-bold tracking-widest"><?php echo $_SESSION['nim']; ?></p>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <label for="nama_tim" class="block text-sm font-bold text-gray-700 mb-2">Nama Tim</label>
                        <input type="text" id="nama_tim" name="nama_tim" placeholder="Contoh: Tim Inovasi Polije" class="w-full px-5 py-3 rounded-2xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none" required>
                    </div>

                    <div>
                        <label for="kategori_pkm" class="block text-sm font-bold text-gray-700 mb-2">Kategori PKM</label>
                        <select id="kategori_pkm" name="kategori_pkm" 
                                class="w-full px-5 py-3 rounded-2xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none appearance-none bg-white cursor-pointer" required>
                                <option value="" disabled selected>Pilih Kategori PKM</option>
                                <option value="PKM-RE">PKM-RE (Riset Eksakta)</option>
                                <option value="PKM-RSH">PKM-RSH (Riset Sosial Humaniora)</option>
                                <option value="PKM-K">PKM-K (Kewirausahaan)</option>
                                <option value="PKM-PM">PKM-PM (Pengabdian Kepada Masyarakat)</option>
                                <option value="PKM-PI">PKM-PI (Penerapan Iptek)</option>
                                <option value="PKM-KC">PKM-KC (Karsa Cipta)</option>
                                <option value="PKM-KI">PKM-KI (Karya Inovatif)</option>
                                <option value="PKM-VGK">PKM-VGK (Video Gagasan Konstruktif)</option>
                                <option value="PKM-GFT">PKM-GFT (Gagasan Futuristik Tertulis)</option>
                                <option value="PKM-AI">PKM-AI (Artikel Ilmiah)</option>
                            </select>
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat Konsep Projek</label>
                        <textarea id="deskripsi" name="deskripsi" rows="5" class="w-full px-5 py-4 rounded-2xl border border-gray-200 focus:border-blue-500 outline-none resize-none" required></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-6">
                        <button type="submit" class="bg-blue-600 text-white px-10 py-3 rounded-full font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transform hover:-translate-y-1 transition-all active:scale-95">
                            Buat Tim Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
</main>

    

    <script>
        // JS Toggle Navbar tetap sama
        const menuToggle = document.getElementById('menu-toggle');
        const navMenu = document.getElementById('nav-menu');
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('hidden');
            navMenu.classList.toggle('flex');
        });
    </script>
</body>
</html>