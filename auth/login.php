<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Logo Broser -->
   <link rel="icon" type="image/png" href="../image/LogoPolije.png">
<!-- Logo Broser -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN PKM POLIJE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif;
            /* Menggunakan overlay gelap agar form putih lebih menonjol */
            background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), 
                        url('../image/bg_polije.jpeg'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        select { appearance: none; }
    </style>
</head>
<body class="flex items-center justify-center p-6">

    <div class="w-full max-w-[420px] glass-card rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] p-10 transition-all duration-500">
        
        <div class="flex flex-col items-center mb-8">
            <div class="flex items-center space-x-4 mb-6">
                <img src="../image/LogoPolije.png" alt="Logo Polije" class="h-12 w-auto object-contain transition-transform hover:rotate-3 duration-300">
                <div class="h-8 w-[1.5px] bg-slate-200"></div>
                <img src="../image/logoPKM.png" alt="Logo PKM" class="h-12 w-auto object-contain transition-transform hover:-rotate-3 duration-300">
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Selamat Datang</h2>
            <p class="text-slate-500 text-sm font-medium">Masuk ke SIM PKM Polije</p>
        </div>

        <form action="../proses_login_mahasiswa/proses_login_mahasiswa.php" method="POST" class="space-y-5">
    <div class="group">
        <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-2 ml-1">Email Kampus</label>
        <input type="email" name="email" required 
               class="w-full px-5 py-3.5 bg-slate-100/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900 focus:bg-white outline-none transition-all duration-300 text-sm" 
               placeholder="nim@student.polije.ac.id">
    </div>

    <div class="group">
        <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-2 ml-1">Kategori Tim</label>
        <div class="relative">
            <select name="kategori" required class="w-full px-5 py-3.5 bg-slate-100/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900 focus:bg-white outline-none transition-all duration-300 text-sm text-slate-600 cursor-pointer">
                <option value="" disabled selected>Pilih status tim...</option>
                <option value="buat">Buat Tim </option>
                <option value="cari">Cari Tim</option>
            </select>
            </div>
    </div>

    <div class="group">
        <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-2 ml-1">Password</label>
        <div class="relative">
            <input type="password" name="password" id="pass" required 
                   class="w-full px-5 py-3.5 bg-slate-100/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-900/10 focus:border-blue-900 focus:bg-white outline-none transition-all duration-300 text-sm" 
                   placeholder="••••••••">
            </div>
    </div>

    <button type="submit" name="login"
            class="w-full bg-blue-900 text-white text-sm font-bold py-4 rounded-2xl hover:bg-black shadow-[0_15px_30px_rgba(30,58,138,0.3)] transform active:scale-[0.98] transition-all duration-300 mt-4 uppercase tracking-[0.2em]">
        Masuk Sekarang
    </button>
</form>

        <div class="mt-10 text-center">
            <p class="text-sm text-slate-500 font-medium">Belum memiliki akun? 
                <a href="./register.php" class="text-blue-900 font-bold hover:text-blue-700 transition-colors underline-offset-4 hover:underline decoration-2">Daftar</a>
            </p>
        </div>
    </div>

    <script>
        function toggle() {
            const p = document.getElementById('pass');
            const i = document.getElementById('icon');
            if (p.type === 'password') {
                p.type = 'text';
                i.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
            } else {
                p.type = 'password';
                i.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }
    </script>
</body>
</html>