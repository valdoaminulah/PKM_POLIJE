<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'humas') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 2. Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: ./tabel_berita.php");
    exit();
}

$id = $_GET['id'];

// 3. Ambil data lama dari database
try {
    $query = "SELECT * FROM data_berita WHERE id_berita = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='./tabel_berita.php';</script>";
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
    <!-- Logo Web -->
    <link rel="icon" type="image/png" href="../image/LogoPolije.png">
    <!-- Logo web -->
    <title>Edit Berita - Dashboard Humas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-lg p-6 sticky top-0 h-screen flex flex-col">
        <h1 class="text-xl font-bold text-gray-800">Dashboard Humas</h1>
        <p class="text-sm text-gray-500 mb-8">Manajemen Berita</p>

        <nav class="space-y-4 flex-1">
            <a href="./dasboard.php" class="flex items-center gap-3 text-gray-600 hover:text-blue-500 px-4 py-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 3h7v4h-7v-4z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="./tambah_berita.php" class="flex items-center gap-3 text-gray-600 hover:text-blue-500 px-4 py-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Input Berita</span>
            </a>

            <a href="./tabel_berita.php" class="flex items-center gap-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-3 rounded-full shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18M3 7v10M9 7v10M15 7v10M21 7v10" />
                </svg>
                <span>Tabel Berita</span>
            </a>
        </nav>

        <div class="mt-auto">
            <a href="../login_user/proses_logout_user.php" class="flex items-center gap-3 text-red-500 hover:text-red-600 px-4 py-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V4" />
                </svg>
                <span>Log out</span>
            </a>
        </div>
    </aside>

    <!-- CONTENT WRAPPER -->
    <main class="flex-1 p-8">

        <!-- HEADER -->
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-start gap-4">
                <!-- BACK BUTTON -->
                <a href="./tabel_berita.php" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Perbarui Berita</h2>
                    <p class="text-sm text-gray-500">Sesuaikan informasi berita yang ingin diubah</p>
                </div>
            </div>
        </div>

        <!-- FORM EDIT -->
        <form action="../proses_edit/proses_edit_berita.php" method="POST" enctype="multipart/form-data">
            <!-- Hidden ID -->
            <input type="hidden" name="id_berita" value="<?= $data['id_berita']; ?>">

            <div class="bg-white rounded-2xl shadow-sm border p-6 space-y-6">

                <!-- Judul Berita -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1-1v2m7.071 2.929a2 2 0 010 2.828L9 22H4v-5L17.071 5.929a2 2 0 012.828 0z" />
                        </svg>
                        Judul Berita <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul_berita" required maxlength="100" 
                        value="<?= htmlspecialchars($data['judul_berita']); ?>"
                        placeholder="Masukkan judul berita..."
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>

                <!-- Tanggal Publikasi -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
                        </svg>
                        Tanggal Publikasi <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_publikasi" required 
                        value="<?= $data['tanggal_publikasi']; ?>"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>

                <!-- Link Website -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656l-1.414 1.414a4 4 0 01-5.656-5.656l1.414-1.414m4.242-4.242a4 4 0 015.656 5.656l-1.414 1.414" />
                        </svg>
                        Link Website Resmi Kampus <span class="text-red-500">*</span>
                    </label>
                    <input type="url" name="link_website" required 
                        value="<?= htmlspecialchars($data['link_website']); ?>"
                        placeholder="https://www.polije.ac.id"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>

                <!-- Ringkasan -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>
                        Ringkasan Berita
                    </label>
                    <textarea name="ringkasan" rows="4"
                        placeholder="Tuliskan ringkasan singkat berita di sini..."
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"><?= htmlspecialchars($data['ringkasan']); ?></textarea>
                </div>

                <!-- Preview Gambar Saat Ini -->
                <div class="flex flex-col gap-4">
                    <label class="text-sm font-medium text-gray-700">Gambar Saat Ini:</label>
                    <img src="../berita/<?= $data['gambar_utama']; ?>" alt="Preview" class="w-48 h-32 object-cover rounded-lg border">
                </div>

                <!-- Ganti Gambar Utama -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 17h18M5 7v10m14-10v10" />
                        </svg>
                        Ganti Gambar Utama <span class="text-gray-400 text-xs italic">(Kosongkan jika tidak diubah)</span>
                    </label>

                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 transition cursor-pointer group relative">
                        <input type="file" name="gambar_utama" id="file-input" accept="image/png, image/jpeg, image/jpg"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                        <div class="flex flex-col items-center justify-center gap-2 text-gray-400 group-hover:text-blue-500 transition" id="preview-container">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 0116 6a4 4 0 011 7.874M12 12v6m0-6l-3 3m3-3l3 3" />
                            </svg>
                            <p class="text-sm font-medium text-blue-600" id="file-label">Klik untuk ganti gambar baru</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-between items-center pt-4 border-t">
                    <a href="./tabel_berita.php" class="text-gray-500 hover:text-gray-700 font-medium transition">Batal</a>
                    <button type="submit" name="update"
                        class="px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white font-bold rounded-xl hover:shadow-lg transition-all active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

    </main>
</div>

<script>
    const fileInput = document.getElementById('file-input');
    const fileLabel = document.getElementById('file-label');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            fileLabel.innerText = "Siap diganti: " + this.files[0].name;
            fileLabel.classList.add('text-green-600');
        }
    });
</script>

</body>
</html>