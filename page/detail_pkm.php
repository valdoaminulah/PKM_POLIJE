<?php
require_once '../koneksi/koneksi.php';

// 1. Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    // Jika tidak ada ID, kembali ke halaman informasi utama
    header("Location: informasi_pkm.php");
    exit();
}

try {
    // 2. Query ambil data PKM berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM data_pkm WHERE id_pkm = :id");
    $stmt->execute([':id' => $id]);
    $pkm = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data tidak ditemukan
    if (!$pkm) {
        echo "<script>alert('Informasi skema tidak ditemukan!'); window.location.href='informasi_pkm.php';</script>";
        exit();
    }

} catch (PDOException $e) {
    die("Kesalahan database: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Logo Browser -->
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DETAIL SKEMA <?= htmlspecialchars($pkm['singkatan']) ?> - POLIJE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
        /* Menjaga format baris baru (paragraf) dari database */
        .text-content {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">

    <main class="w-full min-h-screen pt-10 pb-20 px-4 md:px-10 flex justify-center">
        <div class="max-w-4xl w-full">
            
            <!-- Tombol Kembali (no-print) -->
            <div class="mb-8 no-print flex justify-start">
                <a href="informasi_pkm.php" class="inline-flex items-center gap-3 px-6 py-3 bg-blue-900 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] shadow-lg shadow-blue-900/30 hover:bg-blue-800 hover:-translate-x-1 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i> Kembali ke Skema
                </a>
            </div>

            <!-- Kontainer Utama Panduan -->
            <div class="print-area bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden">
                
                <!-- Toolbar Cetak (no-print) -->
                <div class="no-print bg-slate-50/50 px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Skema Program</p>
                    <button onclick="window.print()" class="text-blue-600 hover:text-blue-800 text-[11px] font-black flex items-center gap-2 transition-all">
                        <i class="fas fa-print"></i> Cetak Panduan
                    </button>
                </div>

                <div class="p-8 md:p-16 text-left">
                    <header class="border-b border-slate-100 pb-10 mb-12">
                        <h1 class="text-2xl md:text-4xl font-black text-slate-900 leading-tight mb-4 uppercase tracking-tighter">
                            <?= htmlspecialchars($pkm['nama_pkm']) ?> (<?= htmlspecialchars($pkm['singkatan']) ?>)
                        </h1>
                        <div class="flex flex-col gap-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Subjek :</p>
                            <p class="text-sm font-bold text-slate-700 uppercase italic">Panduan Pelaksanaan dan Sistematika Penulisan Proposal 2026</p>
                        </div>
                    </header>

                    <article class="text-sm md:text-base leading-relaxed text-slate-700 space-y-12 antialiased">
                        
                        <!-- Bagian I: Panduan Umum -->
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

                        <!-- Footer Dokumen -->
                        <div class="pt-12 border-t border-slate-50 mt-16 flex justify-between items-end">
                            <div class="opacity-10 grayscale hidden md:block">
                                <img src="../image/LogoPolije.png" class="w-20" alt="Logo Polije">
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">PKM CENTER</p>
                                <p class="text-[10px] font-black text-slate-800 uppercase">Politeknik Negeri Jember</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <!-- Footer Halaman (no-print) -->
            <div class="mt-10 text-center no-print">
                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-[0.5em]">&copy; 2026 PKM CENTER POLIJE</p>
            </div>

        </div>
    </main>

</body>
</html>