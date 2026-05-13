<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Logo Broser -->
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAFTAR PKM POLIJE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('../image/bg_polije.jpeg'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        select { appearance: none; }
        
        .input-custom:focus {
            background-color: white !important;
            transform: translateY(-1px);
        }

        /* Style khusus untuk file input agar terlihat rapi */
        input[type="file"]::file-selector-button {
            margin-right: 20px;
            border: none;
            background: #1e3a8a;
            padding: 8px 16px;
            border-radius: 10px;
            color: #fff;
            cursor: pointer;
            transition: background .2s ease-in-out;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        input[type="file"]::file-selector-button:hover {
            background: #000;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 md:p-8">

    <div class="w-full max-w-[750px] glass-card rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] p-8 md:p-12 my-5">
        
        <div class="flex flex-col items-center mb-10">
            <div class="flex items-center space-x-4 mb-4">
                <img src="../image/LogoPolije.png" alt="Logo Polije" class="h-12 w-auto object-contain">
                <div class="h-8 w-[1.5px] bg-slate-200"></div>
                <img src="../image/logoPKM.png" alt="Logo PKM" class="h-12 w-auto object-contain">
            </div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight text-center">Registrasi Mahasiswa</h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Lengkapi data diri Anda untuk akun PKM</p>
        </div>

        <!-- Tambahkan enctype="multipart/form-data" untuk upload file -->
        <form action="../proses_registrasi_mahasiswa/proses_registrasi.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                
                <div class="group">
                    <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-1.5 ml-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required 
                           class="input-custom w-full px-5 py-3.5 bg-slate-100/50 border border-slate-200 rounded-2xl outline-none transition-all duration-300 text-sm" 
                           placeholder="Nama sesuai KTM">
                </div>

                <div class="group">
                    <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-1.5 ml-1">NIM</label>
                    <input type="text" name="nim" required 
                           class="input-custom w-full px-5 py-3.5 bg-slate-100/50 border border-slate-200 rounded-2xl outline-none transition-all duration-300 text-sm" 
                           placeholder="E4121xxxx">
                </div>

                <div class="group">
                    <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-1.5 ml-1">Email Polije</label>
                    <input type="email" name="email" required 
                           class="input-custom w-full px-5 py-3.5 bg-slate-100/50 border border-slate-200 rounded-2xl outline-none transition-all duration-300 text-sm" 
                           placeholder="user@student.polije.ac.id">
                </div>

                <div class="group">
                    <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-1.5 ml-1">No. WhatsApp</label>
                    <input type="tel" name="whatsapp" required 
                           class="input-custom w-full px-5 py-3.5 bg-slate-100/50 border border-slate-200 rounded-2xl outline-none transition-all duration-300 text-sm" 
                           placeholder="0812xxxxxxxx">
                </div>

                <div class="group">
                    <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-1.5 ml-1">Jurusan</label>
                    <div class="relative">
                        <select name="jurusan" required class="input-custom w-full px-5 py-3.5 bg-slate-100/50 border border-slate-200 rounded-2xl outline-none transition-all duration-300 text-sm text-slate-600 cursor-pointer">
                            <option value="" disabled selected>Pilih Jurusan...</option>
                            <option value="PP">Produksi Pertanian</option>
                            <option value="TP">Teknologi Pertanian</option>
                            <option value="PETERNAKAN">Peternakan</option>
                            <option value="MNA">Manajemen Agribisnis</option>
                            <option value="TI">Teknologi Informasi</option>
                            <option value="BKP">Bahasa, Komunikasi & Pariwisata</option>
                            <option value="KESEHATAN">Kesehatan</option>
                            <option value="TEKNIK">Teknik</option>
                            <option value="BISNIS">Bisnis</option>
                        </select>
                    </div>
                </div>

                <div class="group">
                    <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-1.5 ml-1">Jenis Kelamin</label>
                    <div class="relative">
                        <select name="gender" required class="input-custom w-full px-5 py-3.5 bg-slate-100/50 border border-slate-200 rounded-2xl outline-none transition-all duration-300 text-sm text-slate-600 cursor-pointer">
                            <option value="" disabled selected>Pilih Gender...</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="group">
                    <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-1.5 ml-1">Program Studi</label>
                    <input type="text" name="prodi" required 
                           class="input-custom w-full px-5 py-3.5 bg-slate-100/50 border border-slate-200 rounded-2xl outline-none transition-all duration-300 text-sm" 
                           placeholder="Teknik Informatika">
                </div>

                <div class="group">
                    <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-1.5 ml-1">Angkatan</label>
                    <div class="relative">
                        <select name="angkatan" required class="input-custom w-full px-5 py-3.5 bg-slate-100/50 border border-slate-200 rounded-2xl outline-none transition-all duration-300 text-sm text-slate-600 cursor-pointer">
                            <option value="" disabled selected>Pilih Tahun...</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                        </select>
                    </div>
                </div>

                <div class="group">
                    <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-1.5 ml-1">Password</label>
                    <input type="password" name="password" id="pass" required 
                           class="input-custom w-full px-5 py-3.5 bg-slate-100/50 border border-slate-200 rounded-2xl outline-none transition-all duration-300 text-sm" 
                           placeholder="••••••••">
                </div>

                <!-- INPUT BARU: UPLOAD KHS -->
                <div class="group">
                    <label class="block text-[11px] font-bold text-blue-900 uppercase tracking-widest mb-1.5 ml-1">Upload KHS (Gambar)</label>
                    <input type="file" name="khs_image" accept="image/*" required 
                           class="input-custom w-full px-5 py-2.5 bg-slate-100/50 border border-slate-200 rounded-2xl outline-none transition-all duration-300 text-xs text-slate-500">
                    <p class="text-[10px] text-slate-400 mt-1 ml-1">*Format: JPG, PNG (Max 2MB)</p>
                </div>

            </div>

            <button type="submit" class="w-full bg-blue-900 text-white text-sm font-bold py-4 rounded-2xl hover:bg-black shadow-xl transition-all duration-300 mt-4 uppercase tracking-widest">
                Daftar Akun Sekarang
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-slate-500 font-medium">Sudah punya akun? 
                <a href="./login.php" class="text-blue-900 font-bold hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>
</body>
</html>