<?php
session_start();
require_once "../config/koneksi.php"; // Pastikan path ke file koneksi benar

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['khs_image'])) {
    // Mengambil ID user dari session (sesuaikan dengan nama session Anda)
    $id_user = $_SESSION['id_user']; 
    $nim = $_SESSION['nim']; // Digunakan untuk penamaan file agar unik

    // 1. Pengaturan Folder dan Nama File Baru
    $target_dir = "../../KHS_image/";
    $file_name = $_FILES["khs_image"]["name"];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Penamaan unik: KHS_NIM_WAKTU.ekstensi
    $new_file_name = "KHS_" . $nim . "_" . time() . "." . $file_ext;
    $target_file = $target_dir . $new_file_name;

    // 2. Validasi File
    $uploadOk = true;
    $check = getimagesize($_FILES["khs_image"]["tmp_name"]);
    
    // Cek apakah benar gambar, ukuran (maks 2MB), dan ekstensi
    if ($check === false || $_FILES["khs_image"]["size"] > 2000000 || !in_array($file_ext, ['jpg', 'jpeg', 'png', 'webp'])) {
        $uploadOk = false;
    }

    if ($uploadOk) {
        try {
            // 3. Ambil Nama File Lama untuk Dihapus dari Server
            $stmt_old = $pdo->prepare("SELECT khs_image FROM user_mahasiswa WHERE id = ?");
            $stmt_old->execute([$id_user]);
            $old_data = $stmt_old->fetch();
            $old_file = $old_data['khs_image'];

            // 4. Proses Upload File Baru
            if (move_uploaded_file($_FILES["khs_image"]["tmp_name"], $target_file)) {
                
                // Hapus file fisik lama jika ada di folder
                if (!empty($old_file) && file_exists($target_dir . $old_file)) {
                    unlink($target_dir . $old_file);
                }

                // 5. Update Nama File Baru ke Database
                $stmt_update = $pdo->prepare("UPDATE user_mahasiswa SET khs_image = ? WHERE id = ?");
                $stmt_update->execute([$new_file_name, $id_user]);

                // Redirect kembali ke beranda dengan status sukses
                header("Location: ../loading/loading_update_khs.php?status=success");
                exit;
            } else {
                header("Location: ../cari_tim/beranda.php?status=upload_error");
            }
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    } else {
        header("Location: ../cari_tim/beranda.php?status=invalid_file");
    }
} else {
    header("Location: ../cari_tim/beranda.php");
}
?>