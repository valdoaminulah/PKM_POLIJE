<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 2. Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    try {
        // 3. Ambil nama file foto sebelum data dihapus dari database
        $stmt = $pdo->prepare("SELECT foto_admin FROM data_kontak_center WHERE id_kontak = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // 4. Hapus file fisik foto dari folder image_center
            // Pastikan tidak menghapus default.jpg
            if ($data['foto_admin'] != 'default.jpg') {
                $path_foto = '../image_center/' . $data['foto_admin'];
                if (file_exists($path_foto)) {
                    unlink($path_foto); // Menghapus file dari server
                }
            }

            // 5. Hapus data dari database
            $delete = $pdo->prepare("DELETE FROM data_kontak_center WHERE id_kontak = :id");
            $delete->execute([':id' => $id]);

            echo "<script>
                    window.location.href = '../loading/loading_hapus_kontak.php';
                  </script>";
        } else {
            header("Location: ../admin_panel/kontak_center.php");
        }

    } catch (PDOException $e) {
        die("Gagal menghapus data: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_panel/kontak_center.php");
    exit();
}