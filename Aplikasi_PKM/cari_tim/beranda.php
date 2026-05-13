<?php
session_start();
require_once "../config/koneksi.php"; // Pastikan path koneksi benar

// Proteksi halaman: Jika belum login, tendang balik ke login.php
if (!isset($_SESSION['status_login'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 1. Ambil data statis dari session
$nama     = $_SESSION['nama'];
$nim      = $_SESSION['nim'];
$email    = $_SESSION['email'];
$wa       = $_SESSION['wa'];
$gender   = $_SESSION['gender'];
$jurusan  = $_SESSION['jurusan'];
$prodi    = $_SESSION['prodi'];
$angkatan = $_SESSION['angkatan'];
$id_user  = $_SESSION['id_user']; // Pastikan ID user tersimpan di session saat login

// 2. AMBIL DATA DINAMIS (KHS) DARI DATABASE
// Hal ini dilakukan agar foto yang baru di-upload langsung muncul tanpa harus logout
try {
    $stmt = $pdo->prepare("SELECT khs_image FROM user_mahasiswa WHERE id = ?");
    $stmt->execute([$id_user]);
    $user_data = $stmt->fetch();
    
    // Variabel inilah yang akan digunakan di bagian HTML Anda
    $khs_image = $user_data['khs_image']; 
} catch (PDOException $e) {
    $khs_image = ""; // Fallback jika query gagal
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
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
                        
                        <a href="./beranda.php" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold text-sm shadow-lg shadow-blue-200 text-center">Beranda</a>
                        
                        <a href="./lowongan.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Lowongan</a>
                        
                        <a href="./status.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Status</a>
                        
                        <a href="./daftar_tim.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Daftar Tim</a>

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


    <main class="max-w-6xl mx-auto px-4 py-8">
    
    <!-- Header Welcome -->
    <div class="bg-blue-600 rounded-2xl p-4 mb-8 shadow-lg shadow-blue-200">
        <p class="text-white text-center font-medium">
            Hai, <span class="font-bold uppercase"><?php echo htmlspecialchars($nama); ?></span> (Angkatan <?php echo htmlspecialchars($angkatan); ?>). Selamat Datang!
        </p>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        
        <!-- Banner Profile -->
        <div class="bg-gradient-to-br from-blue-800 to-indigo-900 p-8 md:p-12 flex flex-col md:flex-row items-center gap-8">
            
            <!-- Avatar / Foto Profil Berdasarkan Gender -->
            <div class="w-32 h-32 md:w-40 md:h-40 bg-white/10 rounded-3xl backdrop-blur-xl border border-white/20 flex items-center justify-center shrink-0 shadow-2xl">
                <?php if($gender == 'Laki-laki' || $gender == 'L'): ?>
                    <svg class="w-20 h-20 text-blue-200" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                <?php else: ?>
                    <svg class="w-20 h-20 text-pink-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                <?php endif; ?>
            </div>

            <!-- Info Nama & Prodi -->
            <div class="text-center md:text-left text-white flex-1">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-2 tracking-tight"><?php echo htmlspecialchars($nama); ?></h2>
                <p class="text-blue-200 text-lg font-medium opacity-90"><?php echo htmlspecialchars($prodi); ?></p>
                <p class="text-blue-300/80 text-sm mt-1"><?php echo htmlspecialchars($jurusan); ?> - Politeknik Negeri Jember</p>
            </div>

            <!-- Tampilan Foto KHS Sebelumnya (Thumbnail) -->
            <div class="shrink-0">
                <div class="group relative">
                    <p class="text-[10px] text-blue-300 font-bold uppercase tracking-widest mb-2 text-center md:text-right">KHS Saat Ini</p>
                    <div class="w-24 h-32 md:w-28 md:h-36 bg-black/20 rounded-2xl overflow-hidden border-2 border-white/30 shadow-2xl relative">
                        <?php if (!empty($khs_image)): ?>
                            <img src="../../KHS_image/<?php echo $khs_image; ?>" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-all">
                            <!-- Overlay Tombol Zoom -->
                            <button onclick="openModalKHS('../../KHS_image/<?php echo $khs_image; ?>')" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-all text-white">
                                <i class="fa-solid fa-magnifying-glass-plus text-xl"></i>
                            </button>
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center p-4 text-center">
                                <i class="fa-solid fa-image-slash text-white/20 text-2xl mb-1"></i>
                                <span class="text-[8px] text-white/40 font-bold uppercase">Kosong</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <!-- Grid Data Diri (Lengkap) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1">Nama Lengkap</p>
                    <p class="text-slate-700 font-bold"><?php echo htmlspecialchars($nama); ?></p>
                </div>
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1">Nomor Induk Mahasiswa</p>
                    <p class="text-slate-700 font-bold tracking-widest"><?php echo htmlspecialchars($nim); ?></p>
                </div>
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1">Email Kampus</p>
                    <p class="text-slate-700 font-bold truncate"><?php echo htmlspecialchars($email); ?></p>
                </div>
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1">No. WhatsApp</p>
                    <p class="text-slate-700 font-bold"><?php echo htmlspecialchars($wa); ?></p>
                </div>
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1">Jenis Kelamin</p>
                    <p class="text-slate-700 font-bold"><?php echo htmlspecialchars($gender); ?></p>
                </div>
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1">Tahun Angkatan</p>
                    <p class="text-slate-700 font-bold"><?php echo htmlspecialchars($angkatan); ?></p>
                </div>
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 lg:col-span-1">
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1">Program Studi</p>
                    <p class="text-slate-700 font-bold"><?php echo htmlspecialchars($prodi); ?></p>
                </div>
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 lg:col-span-2">
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1">Jurusan</p>
                    <p class="text-slate-700 font-bold"><?php echo htmlspecialchars($jurusan); ?></p>
                </div>
            </div>

            <!-- Form Update KHS Sederhana -->
            <div class="mt-4 pt-8 border-t border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Perbarui Berkas KHS</h3>
                        <p class="text-sm text-slate-500">Pilih file gambar baru untuk mengganti data KHS lama.</p>
                    </div>
                </div>

                <form action="../proses/proses_update_khs.php" method="POST" enctype="multipart/form-data" class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                    <div class="flex flex-col md:flex-row gap-4 items-center">
                        <div class="w-full relative">
                            <input type="file" name="khs_image" id="khs_image" accept="image/*" required
                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">
                        </div>
                        <button type="submit" class="w-full md:w-auto bg-slate-800 hover:bg-black text-white px-8 py-3 rounded-xl font-bold text-sm transition-all shadow-lg active:scale-95 whitespace-nowrap">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- MODAL ZOOM KHS FULL SCREEN -->
<div id="modalKHS" class="fixed inset-0 z-[999] bg-black items-center justify-center transition-all duration-300 hidden opacity-0 overflow-hidden" style="display: none;">
    <div class="relative w-screen h-screen flex items-center justify-center">
        <button type="button" onclick="closeModalKHS()" class="absolute top-5 right-5 text-white/40 hover:text-white text-6xl transition-all z-[1000] cursor-pointer font-light">&times;</button>
        <img id="imgKHS" src="" class="w-full h-full object-contain animate-zoom">
    </div>
</div>

<style>
    #modalKHS.show { display: flex !important; opacity: 1 !important; }
    .animate-zoom { animation: zoomIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    @keyframes zoomIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
</style>

<script>
    function openModalKHS(imgSrc) {
        const modal = document.getElementById('modalKHS');
        const img = document.getElementById('imgKHS');
        img.src = imgSrc;
        modal.style.display = 'flex';
        setTimeout(() => { modal.classList.remove('hidden'); modal.classList.add('show'); }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeModalKHS() {
        const modal = document.getElementById('modalKHS');
        modal.classList.remove('show');
        setTimeout(() => { modal.classList.add('hidden'); modal.style.display = 'none'; }, 300);
        document.body.style.overflow = 'auto';
    }
</script>
    





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