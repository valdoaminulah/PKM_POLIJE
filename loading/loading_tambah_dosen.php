<?php
session_start();

// Proteksi tambahan: Jika user iseng akses tanpa login, tendang ke login
// Cek apakah user sudah login dan apakah perannya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php"); 
    exit();
}

// Ambil data dari URL untuk hiasan animasi
$pesan  = isset($_GET['msg']) ? $_GET['msg'] : 'Berhasil!';
$tujuan = isset($_GET['to']) ? $_GET['to'] : '../tambah_data/tambah_dosen.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Sistem Sedang Memproses...</title>
    <!-- Logo Broser -->
     <link rel="icon" type="image/png" href="../img/logoPolije.png">
    <!-- Logo Broser -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .animate-slow-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center h-screen overflow-hidden">

    <div class="flex flex-col items-center gap-6">
        <!-- Kontainer Ikon Animasi -->
        <div class="relative flex items-center justify-center">
            
            <!-- Spinner -->
            <div id="loading-spinner" class="w-20 h-20 border-[6px] border-slate-200 border-t-blue-600 rounded-full animate-slow-spin transition-all duration-300"></div>

            <!-- Centang Biru (Muncul setelah Spinner hilang) -->
            <div id="success-check" class="hidden scale-0 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)]">
                <div class="flex items-center justify-center w-20 h-20 bg-blue-600 rounded-full shadow-2xl shadow-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Teks Status Dinamis -->
        <div class="text-center">
            <p id="status-text" class="text-xl font-bold text-slate-400 animate-pulse tracking-tight">
                Sedang memproses...
            </p>
            <!-- Info User (Optional, untuk estetika bahwa ini sesi mereka) -->
            <p class="text-[10px] text-slate-300 uppercase tracking-[0.2em] mt-2 font-bold">
                Sesi : Tambah Data Dosen
            </p>
        </div>
    </div>

    <script>
        // Ambil variabel dari PHP ke JS
        const msgSuccess = "<?php echo addslashes($pesan); ?>";
        const targetUrl = "<?php echo addslashes($tujuan); ?>";

        // Simulasi durasi loading
        setTimeout(() => {
            const spinner = document.getElementById('loading-spinner');
            const check = document.getElementById('success-check');
            const text = document.getElementById('status-text');

            // 1. Matikan Spinner
            spinner.classList.add('hidden');

            // 2. Tampilkan Animasi Centang
            check.classList.remove('hidden');
            setTimeout(() => {
                check.classList.replace('scale-0', 'scale-100');
            }, 50);

            // 3. Update Teks Menjadi Pesan Berhasil
            text.innerText = msgSuccess;
            text.classList.remove('text-slate-400', 'animate-pulse');
            text.classList.add('text-blue-600');
            
            // 4. Redirect Final
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 1500);

        }, 2000); // Loading memutar selama 2 detik
    </script>

</body>
</html>