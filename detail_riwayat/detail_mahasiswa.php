<?php
session_start();
include '../koneksi/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: data_mahasiswa.php");
    exit();
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM user_mahasiswa WHERE id = ?");
    $stmt->execute([$id]);
    $mhs = $stmt->fetch();

    if (!$mhs) {
        echo "<script>alert('Data tidak ditemukan!'); window.location='data_mahasiswa.php';</script>";
        exit();
    }
} catch (PDOException $e) {
    die("Gagal: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail - <?php echo htmlspecialchars($mhs['nama_lengkap']); ?></title>
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .profile-gradient { background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); }
        
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { animation: fadeInUp 0.5s ease-out forwards; }

        /* CSS Modal */
        #imageModal {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        #imageModal.show {
            display: flex;
            opacity: 1;
        }
        .animate-zoom {
            animation: zoomIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-700 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl animate-fade-up">
        
        <div class="flex justify-between items-center mb-4 px-2">
            <a href="../admin_panel/data_mahasiswa.php" class="flex items-center gap-2 text-slate-500 hover:text-blue-600 font-bold text-sm transition-all group">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Detail Profile</span>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100">
            
            <div class="profile-gradient p-6 md:p-8 text-white relative overflow-hidden">
                <i class="fa-solid fa-graduation-cap absolute -right-2 -bottom-2 text-8xl opacity-10 rotate-12"></i>
                <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">
                    <div class="w-24 h-24 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/30 shadow-xl">
                        <i class="fa-solid fa-user-graduate text-4xl"></i>
                    </div>
                    <div class="text-center md:text-left">
                        <h1 class="text-2xl font-extrabold tracking-tight mb-2 uppercase">
                            <?php echo htmlspecialchars($mhs['nama_lengkap']); ?>
                        </h1>
                        <div class="flex flex-wrap justify-center md:justify-start gap-2">
                            <span class="px-3 py-1 bg-white/10 backdrop-blur-sm rounded-lg text-[11px] font-bold border border-white/20">
                                <i class="fa-solid fa-id-card mr-1"></i> <?php echo htmlspecialchars($mhs['nim']); ?>
                            </span>
                            <span class="px-3 py-1 bg-blue-500/30 backdrop-blur-sm rounded-lg text-[11px] font-bold border border-white/20">
                                <i class="fa-solid fa-calendar-check mr-1"></i> ANGKATAN <?php echo htmlspecialchars($mhs['angkatan']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-book-open text-blue-600 text-xs"></i>
                            <h3 class="font-bold text-slate-800 uppercase tracking-tighter text-xs">Akademik</h3>
                        </div>
                        <div class="pl-6 space-y-3">
                            <div>
                                <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Jurusan</label>
                                <p class="text-slate-700 font-bold text-sm leading-tight"><?php echo htmlspecialchars($mhs['jurusan']); ?></p>
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Program Studi</label>
                                <p class="text-slate-700 font-bold text-sm leading-tight"><?php echo htmlspecialchars($mhs['program_studi']); ?></p>
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Gender</label>
                                <p class="text-slate-700 font-bold text-sm leading-tight"><?php echo htmlspecialchars($mhs['gender']); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-address-card text-emerald-600 text-xs"></i>
                            <h3 class="font-bold text-slate-800 uppercase tracking-tighter text-xs">Kontak</h3>
                        </div>
                        <div class="pl-6 space-y-3">
                            <div>
                                <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Email</label>
                                <p class="text-slate-700 font-bold text-sm break-all leading-tight"><?php echo htmlspecialchars($mhs['email_polije']); ?></p>
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">WhatsApp</label>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $mhs['no_whatsapp']); ?>" target="_blank" 
                                   class="flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 font-bold text-sm transition-all">
                                    <i class="fa-brands fa-whatsapp text-lg"></i>
                                    <?php echo htmlspecialchars($mhs['no_whatsapp']); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BAGIAN KHS -->
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-file-invoice text-orange-500 text-xs"></i>
                        <h3 class="font-bold text-slate-800 uppercase tracking-tighter text-xs">Dokumen Pendukung</h3>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl flex items-center justify-between border border-dashed border-slate-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-image text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-700 uppercase">Kartu Hasil Studi (KHS)</p>
                                <p class="text-[10px] text-slate-400 italic">Terlampir dalam sistem</p>
                            </div>
                        </div>
                        <button onclick="openModal()" class="px-4 py-2 bg-slate-800 hover:bg-black text-white text-[10px] font-bold rounded-lg transition-all shadow-lg">
                            <i class="fa-solid fa-eye mr-1"></i> LIHAT KHS
                        </button>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-50">
                    <div class="text-[10px] text-slate-400 font-medium italic">
                        <i class="fa-solid fa-clock-rotate-left mr-1"></i> 
                        Terdaftar: <?php echo date('d M Y', strtotime($mhs['created_at'])); ?>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center mt-6 text-slate-300 text-[10px] font-bold tracking-widest uppercase">
            &copy; 2026 PKM POLIJE
        </p>
    </div>

    <!-- MODAL ZOOM GAMBAR (Full Screen) -->
    <div id="imageModal" onclick="closeModal()" class="fixed inset-0 z-[100] bg-black items-center justify-center transition-all duration-300">
        <div class="relative w-full h-full flex items-center justify-center">
            <!-- Tombol Close -->
            <button onclick="closeModal()" class="absolute top-5 right-5 text-white/50 hover:text-white text-5xl transition-all z-[110] drop-shadow-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
            
            <img id="modalImage" src="../KHS_image/<?php echo htmlspecialchars($mhs['khs_image']); ?>" 
                 alt="KHS Mahasiswa" 
                 class="w-full h-full object-contain animate-zoom cursor-zoom-out"
                 onclick="event.stopPropagation()">

            <div class="absolute bottom-0 w-full p-6 bg-gradient-to-t from-black/60 to-transparent pointer-events-none text-center">
                <p class="text-white/80 text-xs font-bold uppercase tracking-[0.5em]">Mode Full Screen</p>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
            document.body.style.overflow = 'auto';
        }

        // Tutup dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                closeModal();
            }
        });
    </script>

</body>
</html>