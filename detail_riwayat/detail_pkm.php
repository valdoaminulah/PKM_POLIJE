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
    header("Location: ../admin_panel/informasi_pkm.php");
    exit();
}

try {
    // 3. Query ambil data PKM
    $stmt = $pdo->prepare("SELECT * FROM data_pkm WHERE id_pkm = :id");
    $stmt->execute([':id' => $id]);
    $pkm = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pkm) {
        echo "<script>alert('Kategori PKM tidak ditemukan!'); window.location.href='../admin_panel/data_pkm.php';</script>";
        exit();
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DETAIL SKEMA <?= htmlspecialchars($pkm['singkatan']) ?> - ADMIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Pengaturan Khusus Cetak */
        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .print-area { 
                box-shadow: none !important; 
                border: none !important; 
                margin: 0 !important; 
                padding: 0 !important;
                width: 100% !important;
            }
        }
        /* Menjaga format baris baru dari database */
        .text-content {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">

    <main class="w-full min-h-screen pt-10 pb-20 px-4 md:px-10 flex justify-center">
        <div class="max-w-4xl w-full">
            
            <!-- Tombol Navigasi (Hidden saat Print) -->
            <div class="mb-8 no-print flex justify-start">
                <a href="../admin_panel/informasi_pkm.php" class="inline-flex items-center gap-3 px-6 py-3 bg-blue-900 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] shadow-lg shadow-blue-900/30 hover:bg-blue-800 hover:-translate-x-1 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>

            <!-- Area Dokumen -->
            <div class="print-area bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden">
                
                <!-- Toolbar Atas Dokumen (Hidden saat Print) -->
                <div class="no-print bg-slate-50/50 px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dokumen Resmi PKM</span>
                    </div>
                    <button onclick="window.print()" class="text-blue-600 hover:text-blue-800 text-[11px] font-black flex items-center gap-2 transition-all">
                        <i class="fas fa-print"></i> Cetak Panduan
                    </button>
                </div>

                <div class="p-8 md:p-16 text-left">
                    <!-- Header Dokumen -->
                    <header class="border-b border-slate-100 pb-10 mb-12">
                        <h1 class="text-2xl md:text-4xl font-black text-slate-900 leading-tight mb-4 uppercase tracking-tighter">
                            <?= htmlspecialchars($pkm['nama_pkm']) ?> (<?= htmlspecialchars($pkm['singkatan']) ?>)
                        </h1>
                        <div class="flex flex-col gap-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Subjek :</p>
                            <p class="text-sm font-bold text-slate-700 uppercase italic">Panduan Pelaksanaan dan Sistematika Penulisan Proposal 2026</p>
                        </div>
                    </header>

                    <!-- Isi Dokumen -->
                    <article class="text-sm md:text-base leading-relaxed text-slate-700 space-y-12 antialiased">
                        
                        <!-- I. Panduan Umum -->
                       <section>
                                <p class="text-[11px] font-black text-blue-900 uppercase tracking-[0.3em] mb-6 flex items-center gap-3">
                                    <span class="w-8 h-px bg-blue-900"></span> I. PANDUAN UMUM PROGRAM
                                </p>
                                <!-- Pastikan tag PHP menempel langsung setelah tag div tanpa spasi/enter -->
                                <div class="text-content text-left"><?= htmlspecialchars(trim($pkm['panduan_umum'])) ?></div>
                            </section>

                            <section>
                                <p class="text-[11px] font-black text-blue-900 uppercase tracking-[0.3em] mb-6 flex items-center gap-3">
                                    <span class="w-8 h-px bg-blue-900"></span> II. PANDUAN PENULISAN PROPOSAL
                                </p>
                                <!-- Gunakan trim() untuk membuang spasi tak sengaja dari database -->
                                <div class="text-content text-left"><?= htmlspecialchars(trim($pkm['panduan_penulisan'])) ?></div>
                            </section>

                        <!-- Footer Internal Dokumen -->
                        <div class="pt-12 border-t border-slate-50 mt-16 flex justify-between items-end">
                            <div class="hidden md:block opacity-20 grayscale">
                                <img src="../image/LogoPolije.png" class="w-16" alt="Logo">
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Diterbitkan Oleh</p>
                                <p class="text-[10px] font-black text-slate-800 uppercase">PKM Center Polije 2026</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <!-- Footer Halaman (Hidden saat Print) -->
            <div class="mt-10 text-center no-print">
                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-[0.5em]">&copy; 2026 PKM CENTER POLIJE</p>
            </div>

        </div>
    </main>

</body>
</html>