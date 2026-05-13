<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // 2. Ambil data dari form
        $id_pkm            = $_POST['id_pkm'];
        $nama_pkm          = $_POST['nama_pkm'];
        $singkatan         = $_POST['singkatan'];
        $deskripsi_singkat = $_POST['deskripsi_singkat'];
        $panduan_umum      = $_POST['panduan_umum'];
        $panduan_penulisan = $_POST['panduan_penulisan'];
        $foto_lama         = $_POST['foto_lama'];

        // 3. Logika Update Foto
        $nama_file_baru = $_FILES['foto_pkm']['name'];
        $tmp_name       = $_FILES['foto_pkm']['tmp_name'];
        
        // Default gunakan foto lama
        $foto_final = $foto_lama;

        // Cek jika ada unggahan foto baru
        if (!empty($nama_file_baru)) {
            $target_dir = "../upload/";
            $ext = strtolower(pathinfo($nama_file_baru, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed_ext)) {
                throw new Exception("Format file harus JPG, JPEG, atau PNG.");
            }

            // Buat nama unik baru
            $nama_pkm_clean = strtolower(str_replace(' ', '_', $singkatan));
            $foto_final = "banner_" . $nama_pkm_clean . "_" . time() . "." . $ext;
            $target_file = $target_dir . $foto_final;

            if (move_uploaded_file($tmp_name, $target_file)) {
                // Hapus foto lama jika ada di folder
                $path_foto_lama = $target_dir . $foto_lama;
                if (!empty($foto_lama) && file_exists($path_foto_lama)) {
                    unlink($path_foto_lama);
                }
            } else {
                throw new Exception("Gagal mengunggah foto baru.");
            }
        }

        // 4. Query Update Data
        $sql = "UPDATE data_pkm SET 
                    foto_pkm = :foto, 
                    nama_pkm = :nama, 
                    singkatan = :singkatan, 
                    deskripsi_singkat = :deskripsi, 
                    panduan_umum = :panduan_u, 
                    panduan_penulisan = :panduan_p 
                WHERE id_pkm = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':foto'      => $foto_final,
            ':nama'      => $nama_pkm,
            ':singkatan' => $singkatan,
            ':deskripsi' => $deskripsi_singkat,
            ':panduan_u' => $panduan_umum,
            ':panduan_p' => $panduan_penulisan,
            ':id'        => $id_pkm
        ]);

        echo "<script>
                window.location.href = '../loading/loading_edit_pkm.php';
              </script>";

    } catch (Exception $e) {
        echo "<script>
                alert('Gagal memperbarui data: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: ../admin_panel/data_pkm.php");
    exit();
}
?>