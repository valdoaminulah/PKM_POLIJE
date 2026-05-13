<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .animate-check {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: dash 0.8s ease-in-out forwards;
            animation-delay: 0.2s;
        }
        @keyframes dash {
            to { stroke-dashoffset: 0; }
        }
        body {
            /* Menggunakan background yang sama dengan form agar konsisten */
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('../image/bg_polije.jpeg'); 
            background-size: cover;
            background-position: center;
        }
    </style>
    <meta http-equiv="refresh" content="4;url=../auth/login.php">
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="text-center">
        <div class="relative flex items-center justify-center mb-6">
            <div id="spinner" class="w-24 h-24 border-8 border-white/20 border-t-green-500 rounded-full animate-spin"></div>
            
            <div id="check-icon" class="hidden absolute">
                <svg class="w-24 h-24 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="animate-check" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h1 id="status-text" class="text-3xl font-black text-white tracking-widest uppercase italic italic">Memproses...</h1>
        <p id="sub-text" class="text-slate-300 mt-2 font-medium">Menyiapkan akun PKM Anda</p>
    </div>

    <script>
        // Simulasi loading selama 2 detik
        setTimeout(() => {
            // Sembunyikan Spinner
            document.getElementById('spinner').classList.add('hidden');
            
            // Tampilkan Centang
            document.getElementById('check-icon').classList.remove('hidden');
            
            // Ubah Teks
            const statusText = document.getElementById('status-text');
            statusText.innerText = 'SUKSES!';
            statusText.classList.add('text-green-500'); // Berubah jadi hijau saat sukses
            
            document.getElementById('sub-text').innerText = 'Registrasi berhasil, mengalihkan...';
        }, 2000);
    </script>

</body>
</html>