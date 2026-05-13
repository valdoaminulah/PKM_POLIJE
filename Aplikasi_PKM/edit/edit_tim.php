<?php
session_start();
require_once '../config/koneksi.php'; 

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../buat_tim/daftar_tim.php");
    exit();
}

$id_tim = $_GET['id'];
$id_user_login = $_SESSION['id_user'];

try {
    // Ambil data tim JOIN dengan user_mahasiswa untuk mendapatkan Nama dan NIM Ketua
    $stmt = $pdo->prepare("SELECT t.*, u.nama_lengkap, u.nim 
                           FROM data_tim_pkm t 
                           JOIN user_mahasiswa u ON t.id_ketua = u.id 
                           WHERE t.id_tim = :id AND t.id_ketua = :id_ketua");
    $stmt->execute(['id' => $id_tim, 'id_ketua' => $id_user_login]);
    $tim = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tim) {
        echo "<script>alert('Tim tidak ditemukan atau Anda tidak memiliki akses.'); window.location='../buat_tim/daftar_tim.php';</script>";
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKM</title>
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
                        
                        <a href="../buat_tim/beranda.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Beranda</a>
                        
                        <a href="../buat_tim/buat_tim.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Buat Tim</a>
                        
                        <a href="../buat_tim/daftar_tim.php" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold text-sm shadow-lg shadow-blue-200 text-center">Daftar Tim</a>
                        
                        <a href="../buat_tim/rekrut_tim.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Rekrut</a>

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



<main class="max-w-4xl mx-auto px-4 py-12">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Informasi Tim</h1>
                <p class="text-gray-500 text-sm">Sesuaikan detail ide projek PKM kamu.</p>
            </div>
            <a href="../buat_tim/daftar_tim.php" class="text-sm font-semibold text-gray-400 hover:text-blue-600 transition">Kembali ke Daftar</a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="../proses/proses_edit_tim.php" method="POST" class="p-8 md:p-12">
                
                <input type="hidden" name="id_tim" value="<?= $id_tim ?>">

                <div class="grid grid-cols-1 gap-8">
                    
                    <!-- INFO OWNER & NIM (Read Only) -->
                    <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-blue-400 font-bold">Ketua Tim / Owner</p>
                            <p class="text-blue-900 font-bold text-lg"><?= htmlspecialchars($tim['nama_lengkap']) ?></p>
                        </div>
                        <div class="bg-white px-4 py-2 rounded-xl border border-blue-200">
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">NIM</p>
                            <p class="text-blue-700 font-mono font-bold tracking-widest"><?= htmlspecialchars($tim['nim']) ?></p>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- Input Nama Tim -->
                    <div>
                        <label for="nama_tim" class="block text-sm font-bold text-gray-700 mb-2">Nama Tim</label>
                        <input type="text" id="nama_tim" name="nama_tim" 
                               value="<?= htmlspecialchars($tim['nama_tim']) ?>" 
                               class="w-full px-5 py-3 rounded-2xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none" required>
                    </div>

                    <!-- Input Kategori -->
                    <div>
                        <label for="kategori_pkm" class="block text-sm font-bold text-gray-700 mb-2">Kategori PKM</label>
                        <select id="kategori_pkm" name="kategori_pkm" 
                                class="w-full px-5 py-3 rounded-2xl border border-gray-200 focus:border-blue-500 outline-none bg-white cursor-pointer appearance-none" required>
                            <?php 
                            $categories = ["PKM-RE", "PKM-RSH", "PKM-K", "PKM-PM", "PKM-PI", "PKM-KC", "PKM-KI", "PKM-VGK", "PKM-GFT", "PKM-AI"];
                            foreach ($categories as $cat):
                                $selected = ($tim['kategori_pkm'] == $cat) ? 'selected' : '';
                                echo "<option value='$cat' $selected>$cat</option>";
                            endforeach; 
                            ?>
                        </select>
                    </div>

                    <!-- Input Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat Konsep Projek</label>
                        <textarea id="deskripsi" name="deskripsi_projek" rows="6" 
                                  class="w-full px-5 py-4 rounded-2xl border border-gray-200 focus:border-blue-500 outline-none resize-none transition-all" required><?= htmlspecialchars($tim['deskripsi_projek']) ?></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-6">
                        <button type="submit" class="bg-blue-600 text-white px-10 py-3 rounded-full font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transform hover:-translate-y-1 transition-all active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>
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
    <script>
    function confirmKick(nama) {
        if (confirm(`Apakah Anda yakin ingin mengeluarkan ${nama} dari tim?`)) {
            // Logika hapus anggota di sini (bisa panggil AJAX atau form submission)
            alert(`${nama} telah dikeluarkan.`);
        }
    }
</script>
</body>
</html>