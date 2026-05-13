<?php
require_once '../koneksi/koneksi.php';

// 1. Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    header("Location: broadcast.php");
    exit();
}

try {
    // 2. Query ambil data pesan berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM pesan WHERE id_pesan = :id");
    $stmt->execute([':id' => $id]);
    $pesan = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data tidak ditemukan
    if (!$pesan) {
        echo "<script>alert('Pesan tidak ditemukan!'); window.location.href='broadcast.php';</script>";
        exit();
    }

} catch (PDOException $e) {
    die("Kesalahan database: " . $e->getMessage());
}

// Fungsi format tanggal Indonesia
function tgl_indo($timestamp) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $date = date('Y-m-d', strtotime($timestamp));
    $split = explode('-', $date);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DETAIL PENGUMUMAN - PKM POLIJE</title>
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
        /* Menjaga format baris baru dari LONGTEXT database */
        .text-content {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">

    <main class="w-full min-h-screen pt-10 pb-20 px-4 md:px-10 flex justify-center">
        <div class="max-w-4xl w-full">
            
            <!-- Tombol Kembali -->
            <div class="mb-8 no-print flex justify-start">
                <a href="../admin_panel/broadcast.php" class="inline-flex items-center gap-3 px-6 py-2.5 bg-blue-900 text-white hover:bg-blue-800 transition-all duration-300 rounded-xl shadow-lg shadow-blue-900/20 group">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-[11px] font-black uppercase tracking-widest">Kembali ke Daftar</span>
                </a>
            </div>

            <!-- Area Dokumen -->
            <div class="print-area bg-white rounded-[2.5rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden">
                
                <div class="no-print bg-slate-50/50 px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Official Broadcast • <?= $pesan['tujuan_pesan'] ?></span>
                    <div class="flex gap-6">
                        <button onclick="window.print()" class="text-slate-500 hover:text-slate-800 text-[11px] font-black flex items-center gap-2 transition-all">
                            <i class="fas fa-download"></i> Simpan PDF
                        </button>
                    </div>
                </div>

                <div class="p-8 md:p-16 text-left">
                    <header class="mb-10 border-b border-slate-100 pb-10">
                        <h1 class="text-2xl md:text-4xl font-black text-slate-900 leading-tight mb-8 tracking-tighter text-left uppercase">
                            <?= htmlspecialchars($pesan['judul_pesan']) ?>
                        </h1>
                        
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-900 text-lg shadow-sm border border-blue-100">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1.5">Pengirim :</p>
                                    <p class="text-sm font-black text-slate-800 uppercase">Admin PKM Center Polije</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 md:text-right">
                                <div class="md:order-2 w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 text-lg shadow-sm border border-slate-200">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="md:order-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1.5">Tanggal Rilis :</p>
                                    <p class="text-sm font-black text-slate-800 uppercase"><?= tgl_indo($pesan['tgl_kirim']) ?></p>
                                </div>
                            </div>
                        </div>
                    </header>

                    <article class="text-sm md:text-base leading-relaxed text-slate-600 antialiased">
                        <!-- Isi Pesan Dinamis -->
                        <div class="text-content text-left min-h-[200px]"><?= htmlspecialchars(trim($pesan['isi_pesan'])) ?></div>

                        <!-- Tanda Tangan Digital -->
                        <div class="pt-12 flex justify-between items-end border-t border-slate-50 mt-16">
                            <div>
                                <p class="text-sm font-bold text-slate-900 italic">Hormat kami,</p>
                                <div class="mt-4">
                                    <p class="text-sm font-black text-blue-900 uppercase tracking-tight">Tim Admin PKM Center</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">Politeknik Negeri Jember</p>
                                </div>
                            </div>
                            <div class="opacity-10 grayscale hidden md:block">
                                <img src="../image/LogoPolije.png" class="w-20" alt="Logo Polije">
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <!-- Footer Halaman -->
            <div class="mt-10 text-center no-print">
                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-[0.5em]">&copy; 2026 PKM CENTER POLIJE</p>
            </div>

        </div>
    </main>

</body>
</html>