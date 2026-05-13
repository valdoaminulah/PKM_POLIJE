<?php
session_start();
include '../koneksi/koneksi.php';

// 1. PROTEKSI HALAMAN
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login_user/login_user.php"); 
    exit();
}

// 2. TANGKAP ID DARI URL
$id_edit = $_GET['id'] ?? '';

if (empty($id_edit)) {
    die("Error: ID tidak ditemukan. Silakan kembali ke tabel mahasiswa.");
}

try {
    // 3. AMBIL DATA DARI DATABASE BERDASARKAN ID
    $query = $pdo->prepare("SELECT * FROM user_mahasiswa WHERE id = ?");
    $query->execute([$id_edit]);
    $data = $query->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        die("Error: Data dengan ID tersebut tidak ada di database.");
    }
} catch (PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data - <?= htmlspecialchars($data['nama_lengkap']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .input-focus:focus { border-color: #2563eb; ring: 2px; ring-color: #2563eb; }
    </style>
</head>
<body class="p-4 md:p-10">

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="javascript:history.back()" class="text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Mahasiswa
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h1 class="text-xl font-extrabold text-slate-800">Edit Profil Mahasiswa</h1>
                <p class="text-sm text-slate-500">Perbarui informasi untuk akun: <span class="font-mono font-bold text-blue-600"><?= $data['nim'] ?></span></p>
            </div>

            <!-- Tambahkan enctype="multipart/form-data" agar bisa upload file -->
            <form action="../proses_edit/proses_edit_mahasiswa.php" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                
                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                <input type="hidden" name="nim_lama" value="<?= $data['nim'] ?>">
                <input type="hidden" name="khs_lama" value="<?= $data['khs_image'] ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 uppercase ml-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required 
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-blue-700 uppercase ml-1">NIM Baru</label>
                        <input type="text" name="nim_baru" value="<?= $data['nim'] ?>" required 
                               class="w-full px-4 py-2.5 border-blue-200 bg-blue-50/30 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-semibold text-blue-900">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 uppercase ml-1">Email Polije</label>
                        <input type="email" name="email_polije" value="<?= htmlspecialchars($data['email_polije']) ?>" required 
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 uppercase ml-1">No. WhatsApp</label>
                        <input type="text" name="no_whatsapp" value="<?= htmlspecialchars($data['no_whatsapp']) ?>" required 
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 uppercase ml-1">Jenis Kelamin</label>
                        <select name="gender" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="L" <?= $data['gender'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= $data['gender'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-600 uppercase ml-1">Jurusan</label>
                        <select name="jurusan" required class="w-full px-4 py-2.5 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <?php
                            $jurusans = [
                                'PP'=>'Produksi Pertanian', 'TP'=>'Teknologi Pertanian', 
                                'PETERNAKAN'=>'Peternakan', 'MNA'=>'Manajemen Agribisnis', 
                                'TI'=>'Teknologi Informasi', 'BKP'=>'Bahasa & Komunikasi', 
                                'KESEHATAN'=>'Kesehatan', 'TEKNIK'=>'Teknik', 'BISNIS'=>'Bisnis'
                            ];
                            foreach($jurusans as $key => $val) {
                                $sel = ($data['jurusan'] == $key) ? 'selected' : '';
                                echo "<option value='$key' $sel>$val</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="space-y-1 md:col-span-2">
                        <label class="text-xs font-bold text-slate-600 uppercase ml-1">Program Studi</label>
                        <input type="text" name="program_studi" value="<?= htmlspecialchars($data['program_studi']) ?>" required 
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <!-- BAGIAN EDIT KHS -->
                <div class="pt-6 border-t border-slate-100">
                    <label class="text-xs font-bold text-slate-600 uppercase ml-1 block mb-3">Update Dokumen KHS</label>
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6 p-4 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                        <!-- Preview KHS Lama -->
                        <div class="relative group">
                            <img src="../KHS_image/<?= $data['khs_image'] ?>" class="w-24 h-24 object-cover rounded-lg border border-slate-200 shadow-sm transition-all group-hover:brightness-50" alt="KHS Lama">
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all pointer-events-none">
                                <i class="fa-solid fa-eye text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1 space-y-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ganti File Baru (JPG/PNG)</p>
                            <input type="file" name="khs_image" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <p class="text-[9px] text-red-400 italic font-medium">*Kosongkan jika tidak ingin mengubah dokumen KHS.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-red-500 uppercase tracking-tighter ml-1">Ganti Password (Kosongkan jika tidak ingin diubah)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </span>
                            <input type="password" name="password" placeholder="••••••••" 
                                   class="w-full pl-10 pr-4 py-2.5 border border-red-100 rounded-lg focus:ring-2 focus:ring-red-400 outline-none bg-red-50/10">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col space-y-3 pt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Data
                    </button>
                    <p class="text-[10px] text-center text-slate-400 italic tracking-wide">Pembaruan data NIM akan mempengaruhi login mahasiswa yang bersangkutan.</p>
                </div>

            </form>
        </div>
    </div>

</body>
</html>