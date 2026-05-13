<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Halaman
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php"); 
    exit();
}

// 2. Ambil ID Dosen yang akan diedit
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) {
    header("Location: ../admin_panel/data_dosen.php");
    exit();
}

// 3. Ambil data lama dari database
try {
    $stmt = $pdo->prepare("SELECT * FROM data_dosen WHERE id_dosen = :id");
    $stmt->execute([':id' => $id]);
    $dosen = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dosen) {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='../admin_panel/data_dosen.php';</script>";
        exit();
    }

    // Tentukan Foto Lama
    $pathFisik = dirname(__DIR__) . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . $dosen['foto_dosen'];
    $urlFotoLama = (file_exists($pathFisik) && !empty($dosen['foto_dosen'])) ? "../upload/" . $dosen['foto_dosen'] : null;

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Dosen - Polije</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-4 md:p-10">

    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Header -->
        <div class="bg-amber-600 px-8 py-5 flex justify-between items-center">
            <h2 class="text-white text-xl font-bold uppercase tracking-wider italic">
                <i class="fas fa-edit mr-2"></i> Edit Data Dosen
            </h2>
            <span class="text-amber-100 text-xs">Update Data: <?= htmlspecialchars($dosen['nama_lengkap']) ?></span>
        </div>

        <!-- Perhatikan action diarahkan ke proses_edit_dosen.php -->
        <form action="../proses_edit/proses_edit_dosen.php" method="POST" enctype="multipart/form-data" class="p-8">
            
            <!-- Hidden Input ID -->
            <input type="hidden" name="id_dosen" value="<?= $dosen['id_dosen'] ?>">
            <!-- Hidden Input Foto Lama (Digunakan jika admin tidak ganti foto) -->
            <input type="hidden" name="foto_lama" value="<?= $dosen['foto_dosen'] ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                
                <!-- 1. Foto Dosen -->
                <div class="md:col-span-2 flex items-center space-x-6 bg-amber-50 p-4 rounded-lg border border-dashed border-amber-300 mb-2">
                    <div class="w-24 h-24 rounded-full border-2 border-white shadow-md overflow-hidden bg-gray-200 flex items-center justify-center">
                        <?php if ($urlFotoLama): ?>
                            <img id="preview-img" src="<?= $urlFotoLama ?>" alt="Preview" class="w-full h-full object-cover">
                            <i id="placeholder-icon" class="fas fa-user-tie text-gray-400 text-3xl hidden"></i>
                        <?php else: ?>
                            <img id="preview-img" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                            <i id="placeholder-icon" class="fas fa-user-tie text-gray-400 text-3xl"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Ganti Foto Profil (Opsional)</label>
                        <input type="file" name="foto_dosen" id="foto_input" accept="image/*" 
                               class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer">
                        <p class="text-[10px] text-amber-700 mt-1 italic">*Kosongkan jika tidak ingin mengubah foto</p>
                    </div>
                </div>

                <!-- 2. Nama & NIP -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase">Nama Lengkap Dosen</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($dosen['nama_lengkap']) ?>" class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-amber-500 transition shadow-sm" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase">NIP Dosen</label>
                    <input type="number" name="nip" value="<?= htmlspecialchars($dosen['nip']) ?>" class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-amber-500 transition shadow-sm" required>
                </div>

                <!-- 3. Jurusan & No WA -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase">Jurusan</label>
                    <select name="jurusan" class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-amber-500 transition shadow-sm bg-white" required>
                        <option value="Teknik Informatika" <?= $dosen['jurusan'] == 'Teknik Informatika' ? 'selected' : '' ?>>Teknik Informatika</option>
                        <option value="Teknologi Informasi" <?= $dosen['jurusan'] == 'Teknologi Informasi' ? 'selected' : '' ?>>Teknologi Informasi</option>
                        <option value="Manajemen Informatika" <?= $dosen['jurusan'] == 'Manajemen Informatika' ? 'selected' : '' ?>>Manajemen Informatika</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase">Nomor WhatsApp</label>
                    <input type="tel" name="wa" value="<?= htmlspecialchars($dosen['no_whatsapp']) ?>" class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-amber-500 transition shadow-sm" required>
                </div>

                <!-- 4. Email & LinkedIn -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase">Email Instansi</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($dosen['email']) ?>" class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-amber-500 transition shadow-sm" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase italic">Nama Akun LinkedIn</label>
                    <input type="text" name="linkedin_name" value="<?= htmlspecialchars($dosen['linkedin_name']) ?>" class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-amber-500 transition shadow-sm">
                </div>

                <!-- 5. Instagram & Facebook -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase italic">Username Instagram</label>
                    <input type="text" name="instagram" value="<?= htmlspecialchars($dosen['instagram_username']) ?>" class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-amber-500 transition shadow-sm">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-600 uppercase italic">Nama Akun Facebook</label>
                    <input type="text" name="facebook_name" value="<?= htmlspecialchars($dosen['facebook_name']) ?>" class="w-full rounded-lg border-gray-300 border p-3 outline-none focus:ring-2 focus:ring-amber-500 transition shadow-sm">
                </div>

                <!-- 6. Riwayat Bimbingan -->
                <div class="md:col-span-2 space-y-2 bg-amber-50 p-4 rounded-lg border border-amber-100">
                    <label class="block text-sm font-bold text-amber-800 uppercase">Total Riwayat Bimbingan PKM</label>
                    <div class="flex items-center space-x-4">
                        <input type="number" name="riwayat_bimbingan" value="<?= $dosen['riwayat_bimbingan'] ?>" min="0" class="w-32 rounded-lg border-amber-200 border p-3 text-center text-xl font-bold outline-none focus:ring-2 focus:ring-amber-500 shadow-inner">
                        <span class="text-amber-600 font-semibold italic uppercase text-xs">Total Bimbingan saat ini</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-10 pt-6 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <a href="../admin_panel/data_dosen.php" class="w-full md:w-auto px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition text-center uppercase tracking-widest text-xs flex items-center justify-center shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Batal Edit
                </a>

                <button type="submit" class="w-full md:w-auto px-12 py-3 bg-amber-600 text-white font-bold rounded-lg hover:bg-amber-700 shadow-md transform hover:scale-[1.02] transition uppercase tracking-widest text-sm flex items-center justify-center">
                    <i class="fas fa-sync-alt mr-2"></i> Update Data Dosen
                </button>
            </div>
        </form>
    </div>

    <script>
        const fotoInput = document.getElementById('foto_input');
        const previewImg = document.getElementById('preview-img');
        const placeholderIcon = document.getElementById('placeholder-icon');

        fotoInput.onchange = evt => {
            const [file] = fotoInput.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file);
                previewImg.classList.remove('hidden');
                if(placeholderIcon) placeholderIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>