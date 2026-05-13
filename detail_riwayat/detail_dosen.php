<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    header("Location: informasi_dosen.php");
    exit();
}

try {
    // 2. Query ambil data dosen berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM data_dosen WHERE id_dosen = :id");
    $stmt->execute([':id' => $id]);
    $dosen = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data tidak ditemukan
    if (!$dosen) {
        echo "<script>alert('Data dosen tidak ditemukan!'); window.location.href='informasi_dosen.php';</script>";
        exit();
    }

    // 3. Logika Foto
    $namaFoto = $dosen['foto_dosen'];
    $pathFisik = dirname(__DIR__) . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . $namaFoto;
    $urlFoto = "../upload/" . $namaFoto;

    if (empty($namaFoto) || !file_exists($pathFisik)) {
        $urlFoto = "https://ui-avatars.com/api/?name=" . urlencode($dosen['nama_lengkap']) . "&background=eff6ff&color=2563eb&rounded=true&bold=true&size=512";
    }

} catch (PDOException $e) {
    die("Kesalahan database: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DETAIL DOSEN - <?= htmlspecialchars($dosen['nama_lengkap']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-gray-50 text-slate-900">

    <section class="w-full min-h-screen py-10 md:py-16 px-4">
        <div class="max-w-4xl mx-auto">
            
            <div class="mb-8">
                <a href="../admin_panel/data_dosen.php" class="inline-flex items-center gap-3 px-5 py-2.5 bg-white text-blue-900 hover:bg-blue-900 hover:text-white transition-all duration-300 rounded-xl shadow-sm border border-gray-200 group">
                    <i class="fas fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-[11px] font-black uppercase tracking-widest">Kembali ke Daftar Dosen</span>
                </a>
            </div>

            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    
                    <!-- Sisi Kiri: Foto & Badge -->
                    <div class="w-full md:w-[320px] p-8 bg-slate-50/50 flex-shrink-0 flex items-center justify-center">
                        <div class="relative inline-block">
                            <div class="w-48 h-64 md:w-56 md:h-72 rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white">
                                <img src="<?= $urlFoto ?>" class="w-full h-full object-cover">
                            </div>

                            <div class="absolute -bottom-5 -right-5 bg-blue-900 text-white w-20 h-20 md:w-24 md:h-24 rounded-full shadow-2xl border-4 border-white flex flex-col items-center justify-center transform hover:scale-110 transition-transform duration-300">
                                <span class="text-xl md:text-2xl font-black italic leading-none"><?= $dosen['riwayat_bimbingan'] ?>+</span>
                                <span class="text-[7px] md:text-[8px] font-bold uppercase tracking-tighter mt-1 opacity-80 text-center">Riwayat<br>Bimbingan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Informasi Detail -->
                    <div class="flex-grow p-6 md:p-10 flex flex-col justify-center">
                        <div class="flex flex-col gap-1 mb-4 text-left">
                            <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest"><?= htmlspecialchars($dosen['jurusan']) ?></span>
                            <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">
                                <?= htmlspecialchars($dosen['nama_lengkap']) ?>
                            </h1>
                            <p class="text-[11px] font-bold text-slate-400 tracking-wider">NIP. <?= htmlspecialchars($dosen['nip']) ?></p>
                        </div>

                        <div class="h-[1px] bg-slate-100 w-full my-6"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-left">
                            <!-- Kontak -->
                            <div class="space-y-4">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">Kontak Langsung</p>
                                <div class="flex items-center gap-4 group">
                                    <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600 border border-green-100"><i class="fab fa-whatsapp"></i></div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase leading-none mb-1">WhatsApp</span>
                                        <span class="text-xs font-black text-slate-700 tracking-wide"><?= htmlspecialchars($dosen['no_whatsapp']) ?></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100"><i class="fas fa-envelope"></i></div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase leading-none mb-1">Email</span>
                                        <span class="text-xs font-black text-slate-700 tracking-wide"><?= htmlspecialchars($dosen['email']) ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Media Sosial -->
                            <div class="space-y-4">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">Jejaring Sosial</p>
                                <div class="flex items-center gap-4 group">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100"><i class="fab fa-linkedin-in"></i></div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase leading-none mb-1">LinkedIn</span>
                                        <span class="text-xs font-black text-slate-700 tracking-wide"><?= htmlspecialchars($dosen['linkedin_name'] ?: '-') ?></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="w-8 h-8 rounded-lg bg-pink-50 flex items-center justify-center text-pink-600 border border-pink-100"><i class="fab fa-instagram"></i></div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase leading-none mb-1">Instagram</span>
                                        <span class="text-xs font-black text-slate-700 tracking-wide"><?= htmlspecialchars($dosen['instagram_username'] ?: '-') ?></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 group">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-800 border border-blue-100"><i class="fab fa-facebook-f"></i></div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase leading-none mb-1">Facebook</span>
                                        <span class="text-xs font-black text-slate-700 tracking-wide"><?= htmlspecialchars($dosen['facebook_name'] ?: '-') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 pt-6 border-t border-slate-50">
                            <p class="text-[10px] text-slate-400 italic leading-relaxed text-center md:text-left">
                                "Berkomitmen mendampingi mahasiswa dalam melahirkan inovasi PKM yang berdampak bagi masyarakat."
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-[0.3em]">&copy; 2026 PKM CENTER POLIJE</p>
            </div>
        </div>
    </section>

</body>
</html>