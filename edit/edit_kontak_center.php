<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 2. Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    header("Location: ../admin_panel/kontak_center.php");
    exit();
}

try {
    // 3. Ambil data lama dari database
    $stmt = $pdo->prepare("SELECT * FROM data_kontak_center WHERE id_kontak = :id");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='../admin_panel/kontak_center.php';</script>";
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kontak Admin - PKM POLIJE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-10 flex justify-center items-start">

    <div class="max-w-2xl w-full">
        <!-- Header Card -->
        <div class="bg-white border border-slate-200 rounded-t-3xl p-6 md:p-8 border-b-0 shadow-sm">
            <div class="flex items-center gap-4 text-left">
                <div class="bg-amber-500 w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-200">
                    <i class="fas fa-user-edit text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-slate-800 font-black text-2xl tracking-tighter uppercase italic">Edit Kontak</h1>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-0.5">Perbarui informasi admin jurusan yang sudah terdaftar</p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form action="../proses_edit/proses_edit_kontak_center.php" method="POST" enctype="multipart/form-data" class="space-y-0 shadow-xl">
            
            <!-- Hidden ID Input -->
            <input type="hidden" name="id_kontak" value="<?= $data['id_kontak'] ?>">

            <div class="bg-white p-6 md:p-8 border-x border-slate-200 space-y-6">
                
                <!-- Nama & Jurusan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Nama Lengkap</label>
                        <div class="relative">
                            <input type="text" name="nama_admin" value="<?= htmlspecialchars($data['nama_admin']) ?>"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition font-bold text-slate-700" required>
                            <i class="fas fa-user absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Jurusan</label>
                        <div class="relative text-left">
                            <select name="jurusan" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition font-bold text-slate-700 appearance-none cursor-pointer" required>
                                <?php 
                                $jurusan_list = ["Teknik Informatika", "Teknik Mesin", "Akuntansi", "Produksi Pertanian"];
                                foreach($jurusan_list as $j) :
                                ?>
                                    <option value="<?= $j ?>" <?= ($data['jurusan'] == $j) ? 'selected' : '' ?>><?= $j ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-graduation-cap absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                            <i class="fas fa-chevron-down absolute right-4 top-4 text-slate-300 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- WhatsApp & Lokasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Nomor WhatsApp</label>
                        <div class="relative">
                            <input type="number" name="whatsapp" value="<?= htmlspecialchars($data['whatsapp']) ?>"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition font-bold text-slate-700" required>
                            <i class="fa-brands fa-whatsapp absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Lokasi Kantor</label>
                        <div class="relative">
                            <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 pl-10 outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition font-bold text-slate-700" required>
                            <i class="fas fa-map-marker-alt absolute left-3.5 top-4 text-slate-400 text-sm"></i>
                        </div>
                    </div>
                </div>

                <!-- Foto Profile Saat Ini -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Foto Profile (Kosongkan jika tidak ingin ganti)</label>
                    <div class="flex items-center gap-4 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                        <img src="../image_center/<?= $data['foto_admin'] ?>" class="w-16 h-16 rounded-xl object-cover border-2 border-white shadow-sm" onerror="this.src='../image_center/default.jpg'">
                        <div class="flex-1">
                            <label class="flex flex-col items-center justify-center w-full h-16 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer hover:bg-slate-100 transition-all">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider"><i class="fas fa-upload mr-2"></i>Pilih Foto Baru</span>
                                <input type="file" name="foto_admin" class="hidden" accept="image/*" />
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="bg-slate-50 p-6 md:p-8 rounded-b-3xl border border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <a href="../admin_panel/kontak_center.php" class="text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:text-rose-500 transition-colors">
                    <i class="fas fa-times mr-1"></i> Batalkan Perubahan
                </a>
                
                <button type="submit" class="w-full md:w-auto px-10 py-3 bg-amber-500 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-xl shadow-amber-900/20 hover:bg-amber-600 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Perbarui Data Kontak
                </button>
            </div>
        </form>
    </div>

</body>
</html>