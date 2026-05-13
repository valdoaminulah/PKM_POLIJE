<!-- Secition Start -->
<?php
session_start();
include '../koneksi/koneksi.php';

// 1. PROTEKSI HALAMAN (Auth & Role)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login_user/login_user.php"); 
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo "<script>
            alert('Akses Ditolak! Halaman ini hanya untuk Admin.');
            window.location.href = '../login_user/login_user.php';
          </script>";
    exit();
}

// 2. LOGIKA PAGINATION
$batas = 5; // Data per halaman
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

try {
    // A. Hitung total data untuk menentukan jumlah halaman
    $query_total = $pdo->query("SELECT COUNT(*) FROM user_mahasiswa");
    $total_data = $query_total->fetchColumn();
    $total_halaman = ceil($total_data / $batas);

    // B. Ambil data HANYA sesuai limit halaman aktif (Lebih efisien)
    $query = "SELECT * FROM user_mahasiswa ORDER BY id DESC LIMIT :start, :limit";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':start', (int) $halaman_awal, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int) $batas, PDO::PARAM_INT);
    $stmt->execute();
    $mahasiswa_list = $stmt->fetchAll();

    // Navigasi
    $prev = $halaman - 1;
    $next = $halaman + 1;

} catch (PDOException $e) {
    die("Gagal mengambil data: " . $e->getMessage());
}
?>
<!-- end -->

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Logo Web -->
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <!-- Logo web -->

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
                </button></a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50">
            
            <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 flex items-center px-8 border-b border-slate-200 shadow-sm">
                <div class="text-slate-400 text-sm font-medium tracking-wide">
                    Menu / <span class="text-slate-900 font-bold">Data Mahasiswa</span>
                </div>
            </header>

            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-md animate-fade-up w-full max-w-6xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-xl font-bold text-slate-800 border-l-4 border-blue-500 pl-3">Daftar Mahasiswa</h2>
            
            <div class="relative w-full md:w-72">
                <input type="text" id="searchInput" placeholder="Cari nama atau NIM..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-slate-600 focus:bg-white">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 mb-6 overflow-hidden shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 text-slate-600 text-xs uppercase font-bold tracking-wide border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5 text-center w-12">No</th>
                        <th class="px-4 py-3.5">Mahasiswa</th>
                        <th class="px-4 py-3.5">Program Studi</th>
                        <th class="px-4 py-3.5">Gender</th>
                        <th class="px-4 py-3.5">No WA</th>
                        <th class="px-4 py-3.5 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    
                    <?php 
                    $no = $halaman_awal + 1;
                    if (count($mahasiswa_list) > 0):
                        foreach ($mahasiswa_list as $row): 
                    ?>
                    <tr class="hover:bg-blue-50/40 transition-colors duration-200 even:bg-slate-50/60">
                        <td class="px-4 py-3 text-center text-slate-500 font-medium"><?php echo $no++; ?></td>
                        <td class="px-4 py-3">
                            <div class="font-bold text-slate-800"><?php echo htmlspecialchars($row['nama_lengkap']); ?></div>
                            <div class="text-xs text-slate-500 font-mono mt-0.5"><?php echo htmlspecialchars($row['nim']); ?></div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">
                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-slate-100 border border-slate-200 text-xs font-medium">
                                <?php echo htmlspecialchars($row['program_studi']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">
                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-slate-100 border border-slate-200 text-xs font-medium">
                                <?php echo htmlspecialchars($row['gender']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600"><?php echo htmlspecialchars($row['no_whatsapp']); ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="../detail_riwayat/detail_mahasiswa.php?id=<?php echo $row['id']; ?>" 
                                    class="w-8 h-8 rounded bg-teal-50 text-teal-600 hover:bg-teal-500 hover:text-white flex items-center justify-center transition-all border border-teal-100" 
                                    title="Detail">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                <a href="../edit/edit_mahasiswa.php?id=<?php echo $row['id']; ?>" 
                                        class="w-8 h-8 rounded bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-all border border-amber-100" 
                                        title="Edit">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                </button>
                                <a href="../proses_hapus/hapus_data_mahasiswa.php?id=<?php echo $row['id']; ?>" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini?')" 
                                    class="w-8 h-8 rounded bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all border border-rose-100" 
                                    title="Hapus">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endforeach; 
                    else: 
                    ?>
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-400 italic">Belum ada data mahasiswa terdaftar.</td>
                    </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500 border-t border-slate-100 pt-4">
            <div>
                Menampilkan <span class="font-bold text-slate-700"><?php echo count($mahasiswa_list); ?></span> 
                dari <span class="font-bold text-slate-700"><?php echo $total_data; ?></span> data
            </div>

            <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-200 shadow-sm">
                <a href="?halaman=<?php echo ($halaman > 1) ? $prev : 1; ?>" 
                class="px-2.5 py-1.5 rounded hover:bg-slate-100 <?php echo ($halaman <= 1) ? 'text-slate-300 pointer-events-none' : 'text-slate-600'; ?>">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>

                <?php for($x=1; $x<=$total_halaman; $x++): ?>
                    <a href="?halaman=<?php echo $x; ?>" 
                    class="w-8 h-8 flex items-center justify-center rounded font-bold transition-all <?php echo ($halaman == $x) ? 'bg-blue-600 text-white shadow-sm' : 'hover:bg-slate-100 text-slate-600'; ?>">
                        <?php echo $x; ?>
                    </a>
                <?php endfor; ?>

                <a href="?halaman=<?php echo ($halaman < $total_halaman) ? $next : $total_halaman; ?>" 
                class="px-2.5 py-1.5 rounded hover:bg-slate-100 <?php echo ($halaman >= $total_halaman) ? 'text-slate-300 pointer-events-none' : 'text-slate-600'; ?>">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
            </div>
        </div>

    </div>
</main>

        </div>
    </div>

</body>
</html>


<!-- js -->
 <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        // 1. Ambil nilai input dan ubah ke huruf kecil
        let filter = this.value.toLowerCase();
        
        // 2. Ambil semua baris data di dalam tbody
        // Pastikan tabel kamu menggunakan <tbody> atau beri id pada tabelnya
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            // 3. Ambil teks dari kolom Nama/NIM (kolom ke-2)
            // Kamu bisa mengambil seluruh teks baris agar pencarian lebih luas
            let text = row.innerText.toLowerCase();

            // 4. Jika teks cocok dengan filter, tampilkan baris, jika tidak sembunyikan
            if (text.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
<!-- js end -->