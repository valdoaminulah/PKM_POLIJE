<?php
session_start();

// Proteksi halaman: Jika belum login, tendang balik ke login.php
if (!isset($_SESSION['status_login'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Mengambil data dari session (Data ini didapat dari proses_login_mahasiswa.php)
$nama     = $_SESSION['nama'];
$nim      = $_SESSION['nim'];
$email    = $_SESSION['email'];
$wa       = $_SESSION['wa'];
$gender   = $_SESSION['gender'];
$jurusan  = $_SESSION['jurusan'];
$prodi    = $_SESSION['prodi'];
$angkatan = $_SESSION['angkatan'];
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
                        
                        <a href="./buat_tim.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Buat Tim</a>
                        
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

    <main class="max-w-6xl mx-auto px-4 py-8">
        
        <div class="bg-blue-600 rounded-2xl p-4 mb-8 shadow-lg shadow-blue-200">
            <p class="text-white text-center font-medium">
                Hai, <span class="font-bold uppercase"><?php echo htmlspecialchars($nama); ?></span> (Angkatan <?php echo htmlspecialchars($angkatan); ?>). Selamat Datang!
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            
            <div class="bg-gradient-to-br from-blue-800 to-indigo-900 p-8 md:p-12 flex flex-col md:flex-row items-center gap-8">
                <div class="w-32 h-32 md:w-40 md:h-40 bg-white/10 rounded-3xl backdrop-blur-xl border border-white/20 flex items-center justify-center shrink-0 shadow-2xl">
                    <?php if($gender == 'Laki-laki' || $gender == 'L'): ?>
                        <svg class="w-20 h-20 text-blue-200" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                    <?php else: ?>
                        <svg class="w-20 h-20 text-pink-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                    <?php endif; ?>
                </div>

                <div class="text-center md:text-left text-white">
                    <h2 class="text-3xl md:text-4xl font-extrabold mb-2 tracking-tight"><?php echo htmlspecialchars($nama); ?></h2>
                    <p class="text-blue-200 text-lg font-medium opacity-90"><?php echo htmlspecialchars($prodi); ?></p>
                    <p class="text-blue-300/80 text-sm mt-1"><?php echo htmlspecialchars($jurusan); ?> - Politeknik Negeri Jember</p>
                </div>
            </div>

            <div class="p-8 md:p-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
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

            </div>
        </div>
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