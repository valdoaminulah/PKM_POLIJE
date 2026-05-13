<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php"); 
    exit();
}

// 2. Ambil ID PKM yang akan diedit
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) {
    header("Location: ../admin_panel/data_pkm.php");
    exit();
}

// 3. Tarik data lama dari database
try {
    $stmt = $pdo->prepare("SELECT * FROM data_pkm WHERE id_pkm = :id");
    $stmt->execute([':id' => $id]);
    $pkm = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pkm) {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='../admin_panel/data_pkm.php';</script>";
        exit();
    }

    // Tentukan pratinjau foto lama
    $pathFisik = "../upload/" . $pkm['foto_pkm'];
    $urlFotoLama = (!empty($pkm['foto_pkm']) && file_exists($pathFisik)) ? $pathFisik : null;

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori PKM - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        textarea::-webkit-scrollbar { width: 6px; }
        textarea::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 10px; }
        textarea:focus { background-color: #ffffff !important; }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen p-4 md:p-10">

    <div class="max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="bg-white border border-slate-200 rounded-t-3xl p-6 md:p-8 border-b-0">
            <div class="flex items-center gap-4">
                <div class="bg-blue-600 w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <i class="fas fa-edit text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-slate-800 font-black text-2xl tracking-tighter uppercase italic">Edit Kategori PKM</h1>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-0.5">Memperbarui Data: <?= htmlspecialchars($pkm['singkatan']) ?></p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form action="../proses_edit/proses_edit_pkm.php" method="POST" enctype="multipart/form-data" class="space-y-0">
            
            <!-- Hidden Inputs -->
            <input type="hidden" name="id_pkm" value="<?= $pkm['id_pkm'] ?>">
            <input type="hidden" name="foto_lama" value="<?= $pkm['foto_pkm'] ?>">

            <!-- Section 1: Visual & Identitas -->
            <div class="bg-white p-6 md:p-8 border-x border-slate-200 space-y-6">
                <div class="relative group">
                    <div id="image-preview" class="w-full h-56 md:h-64 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 flex flex-col items-center justify-center transition-all group-hover:border-blue-400 group-hover:bg-blue-50/30 overflow-hidden">
                        <?php if ($urlFotoLama): ?>
                            <img id="preview-img" src="<?= $urlFotoLama ?>" alt="Preview" class="w-full h-full object-cover">
                            <div id="upload-instruction" class="hidden text-center">
                                <i class="fas fa-image text-xl text-slate-400"></i>
                            </div>
                        <?php else: ?>
                            <div id="upload-instruction" class="text-center">
                                <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-3 text-slate-400">
                                    <i class="fas fa-image text-xl"></i>
                                </div>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Klik untuk Ganti Banner PKM</p>
                            </div>
                            <img id="preview-img" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                        <?php endif; ?>
                    </div>
                    <input type="file" name="foto_pkm" id="foto_input" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap Program</label>
                        <input type="text" name="nama_pkm" value="<?= htmlspecialchars($pkm['nama_pkm']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition font-bold text-slate-700 shadow-sm" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Singkatan</label>
                        <input type="text" name="singkatan" value="<?= htmlspecialchars($pkm['singkatan']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition font-black text-blue-600 text-center uppercase shadow-sm" required>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi Singkat</label>
                    <input type="text" name="deskripsi_singkat" value="<?= htmlspecialchars($pkm['deskripsi_singkat']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition text-sm shadow-sm">
                </div>
            </div>

            <!-- Section 2: Panduan -->
            <div class="bg-white p-6 md:p-8 border-x border-t border-slate-200 space-y-8">
                <div class="space-y-3">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-book-open text-blue-600"></i> Panduan Umum Program
                        </label>
                    </div>
                    <textarea name="panduan_umum" oninput="autoResize(this)" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-5 outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition min-h-[200px] text-sm leading-relaxed text-slate-600 shadow-inner" placeholder="Update aturan umum..."><?= htmlspecialchars($pkm['panduan_umum']) ?></textarea>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-file-alt text-blue-600"></i> Panduan Penulisan Proposal
                        </label>
                    </div>
                    <textarea name="panduan_penulisan" oninput="autoResize(this)" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-5 outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition min-h-[200px] text-sm leading-relaxed text-slate-600 shadow-inner" placeholder="Update tata cara penulisan..."><?= htmlspecialchars($pkm['panduan_penulisan']) ?></textarea>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="bg-slate-50 p-6 md:p-8 rounded-b-3xl border border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <a href="../admin_panel/informasi_pkm.php" class="text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:text-rose-500 transition-colors">
                    <i class="fas fa-times mr-1"></i> Batal Perubahan
                </a>
                
                <div class="flex gap-3 w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto px-10 py-3 bg-blue-600 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-xl shadow-blue-900/10 hover:bg-blue-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-sync-alt"></i> Perbarui Program
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Preview Banner
        const fotoInput = document.getElementById('foto_input');
        const previewImg = document.getElementById('preview-img');
        const uploadInstruction = document.getElementById('upload-instruction');

        fotoInput.onchange = evt => {
            const [file] = fotoInput.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file);
                previewImg.classList.remove('hidden');
                if(uploadInstruction) uploadInstruction.classList.add('hidden');
            }
        }

        // Auto Resize Textarea on Load & Input
        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        // Jalankan autoResize saat halaman dimuat untuk menyesuaikan teks lama
        window.onload = function() {
            document.querySelectorAll('textarea').forEach(el => autoResize(el));
        };
    </script>
</body>
</html>