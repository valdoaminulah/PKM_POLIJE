<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['status_login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_tim = isset($_GET['id']) ? $_GET['id'] : null;
$id_user_login = $_SESSION['id_user'];

if (!$id_tim) {
    header("Location: ../cari_tim/daftar_tim.php");
    exit;
}

try {
    // 1. Ambil Info Tim & Ketua
    $stmt_tim = $pdo->prepare("SELECT t.*, u.nama_lengkap, u.nim, u.angkatan, u.program_studi, u.no_whatsapp 
                               FROM data_tim_pkm t 
                               JOIN user_mahasiswa u ON t.id_ketua = u.id 
                               WHERE t.id_tim = :id_t");
    $stmt_tim->execute([':id_t' => $id_tim]);
    $data_tim = $stmt_tim->fetch(PDO::FETCH_ASSOC);

    if (!$data_tim) {
        die("Tim tidak ditemukan.");
    }

    // 2. Ambil Anggota yang sudah Diterima
    $stmt_anggota = $pdo->prepare("SELECT p.id_pendaftaran, u.id as id_mhs, u.nama_lengkap, u.nim, u.angkatan, u.program_studi, u.no_whatsapp 
                                   FROM pendaftaran_tim p 
                                   JOIN user_mahasiswa u ON p.id_mahasiswa = u.id 
                                   WHERE p.id_tim = :id_t AND p.status_pendaftaran = 'Diterima'");
    $stmt_anggota->execute([':id_t' => $id_tim]);
    $list_anggota = $stmt_anggota->fetchAll(PDO::FETCH_ASSOC);

    $total_anggota = 1 + count($list_anggota);
    $is_ketua = ($data_tim['id_ketua'] == $id_user_login);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Anggota</title>
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
                        
                        <a href="../cari_tim/beranda.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Beranda</a>
                        
                        <a href="../cari_tim/lowongan.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Lowongan</a>
                        
                        <a href="../cari_tim/status.php" class="text-gray-500 hover:text-blue-600 px-6 py-2 rounded-full font-medium text-sm transition text-center">Status</a>
                        
                        <a href="../cari_tim/daftar_tim.php" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold text-sm shadow-lg shadow-blue-200 text-center">Daftar Tim</a>

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
    
    <div class="flex items-center space-x-4 mb-8">
        <a href="../cari_tim/daftar_tim.php" class="p-2 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Anggota Tim</h1>
            <p class="text-gray-500">Manajemen kolaborator untuk tim <span class="text-blue-600 font-semibold"><?php echo htmlspecialchars($data_tim['nama_tim']); ?></span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Total Anggota</p>
            <p class="text-3xl font-black text-gray-800"><?php echo $total_anggota; ?> <span class="text-lg text-gray-300 font-medium">/ 5</span></p>
        </div>
        <div class="md:col-span-2 flex items-center justify-end">
            <?php if (!$is_ketua): ?>
                <button onclick="confirmLeaveTeam(<?php echo $id_tim; ?>)" class="flex items-center space-x-2 bg-red-50 text-red-600 px-6 py-3 rounded-2xl font-bold text-sm hover:bg-red-600 hover:text-white transition-all border border-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar dari Tim</span>
                </button>
            <?php else: ?>
                <p class="text-xs text-orange-500 font-bold bg-orange-50 px-4 py-2 rounded-xl border border-orange-100 italic">Anda memegang kendali penuh sebagai Ketua Tim</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400">Nama Mahasiswa</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400">Identitas</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400">Program Studi</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-400 text-center">No WhatsApp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    
                    <tr class="hover:bg-slate-50/50 transition bg-blue-50/30">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    <?php echo strtoupper(substr($data_tim['nama_lengkap'], 0, 2)); ?>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($data_tim['nama_lengkap']); ?> <?php echo ($data_tim['id_ketua'] == $id_user_login) ? '(Anda)' : ''; ?></p>
                                    <span class="text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-md font-bold uppercase">Ketua</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm text-gray-600 font-medium"><?php echo $data_tim['nim']; ?></p>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter"><?php echo $data_tim['angkatan']; ?></p>
                        </td>
                        <td class="px-6 py-5 leading-relaxed">
                            <p class="text-sm text-gray-700 font-semibold"><?php echo $data_tim['program_studi']; ?></p>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <a href="https://wa.me/<?php echo $data_tim['no_whatsapp']; ?>" target="_blank" class="text-xs text-green-500 font-bold hover:underline"><?php echo $data_tim['no_whatsapp']; ?></a>
                        </td>
                    </tr>

                    <?php foreach ($list_anggota as $anggota): ?>
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 font-bold text-sm">
                                    <?php echo strtoupper(substr($anggota['nama_lengkap'], 0, 2)); ?>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($anggota['nama_lengkap']); ?> <?php echo ($anggota['id_mhs'] == $id_user_login) ? '(Anda)' : ''; ?></p>
                                    <span class="text-[10px] bg-gray-50 text-gray-500 px-2 py-0.5 rounded-md font-bold uppercase">Anggota</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm text-gray-600 font-medium"><?php echo $anggota['nim']; ?></p>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter"><?php echo $anggota['angkatan']; ?></p>
                        </td>
                        <td class="px-6 py-5 leading-relaxed">
                            <p class="text-sm text-gray-700 font-semibold"><?php echo $anggota['program_studi']; ?></p>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <a href="https://wa.me/<?php echo $anggota['no_whatsapp']; ?>" target="_blank" class="text-xs text-green-500 font-bold hover:underline"><?php echo $anggota['no_whatsapp']; ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    function confirmLeaveTeam(idTim) {
        if (confirm('Apakah Anda yakin ingin keluar dari tim ini? Tindakan ini akan menghapus keanggotaan Anda.')) {
            // Arahkan ke file proses penghapusan pendaftaran
            window.location.href = '../proses/proses_keluar_tim.php?id=' + idTim;
        }
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