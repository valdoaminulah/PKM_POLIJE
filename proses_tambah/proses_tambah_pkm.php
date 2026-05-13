<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Halaman (Hanya Admin)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // 2. Ambil data dari form
        $nama_pkm          = $_POST['nama_pkm'];
        $singkatan         = $_POST['singkatan'];
        $deskripsi_singkat = $_POST['deskripsi_singkat'];
        $panduan_umum      = $_POST['panduan_umum'];
        $panduan_penulisan = $_POST['panduan_penulisan'];

        // 3. Logika Upload Foto PKM
        $foto_name = $_FILES['foto_pkm']['name'];
        $foto_tmp  = $_FILES['foto_pkm']['tmp_name'];
        $foto_size = $_FILES['foto_pkm']['size'];
        $foto_err  = $_FILES['foto_pkm']['error'];

        if ($foto_err === 4) {
            throw new Exception("Mohon unggah banner/foto PKM terlebih dahulu.");
        }

        // Cek ekstensi file
        $file_ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        if (!in_array($file_ext, $allowed_ext)) {
            throw new Exception("Format gambar harus JPG, JPEG, atau PNG.");
        }

        // Cek ukuran (Max 2MB)
        if ($foto_size > 2000000) {
            throw new Exception("Ukuran gambar terlalu besar! Maksimal 2MB.");
        }

        // Buat nama file unik (Contoh: pkm_kc_1714552100.jpg)
        $nama_pkm_clean = strtolower(str_replace(' ', '_', $singkatan));
        $foto_final_name = "banner_" . $nama_pkm_clean . "_" . time() . "." . $file_ext;
        
        // Tentukan folder tujuan (folder 'upload' di luar folder proses)
        $target_dir = "../upload/";
        $target_file = $target_dir . $foto_final_name;

        // 4. Eksekusi Upload dan Simpan Database
        if (move_uploaded_file($foto_tmp, $target_file)) {
            
            $sql = "INSERT INTO data_pkm (foto_pkm, nama_pkm, singkatan, deskripsi_singkat, panduan_umum, panduan_penulisan) 
                    VALUES (:foto, :nama, :singkatan, :deskripsi, :panduan_u, :panduan_p)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':foto'      => $foto_final_name,
                ':nama'      => $nama_pkm,
                ':singkatan' => $singkatan,
                ':deskripsi' => $deskripsi_singkat,
                ':panduan_u' => $panduan_umum,
                ':panduan_p' => $panduan_penulisan
            ]);

            echo "<script>
                    window.location.href = '../loading/loading_tambah_pkm.php';
                  </script>";
        } else {
            throw new Exception("Gagal mengunggah gambar ke server.");
        }

    } catch (Exception $e) {
        // Jika ada error, berikan pesan dan kembali ke form
        echo "<script>
                alert('Terjadi Kesalahan: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
} else {
    // Jika akses file langsung tanpa POST
    header("Location: ../tambah_data/tambah_pkm.php");
    exit();
}
?>