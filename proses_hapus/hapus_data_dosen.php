<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Keamanan: Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 2. Ambil ID dari parameter URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    try {
        // 3. Ambil nama file foto sebelum datanya dihapus
        // Ini penting untuk menghapus file fisik di folder upload
        $stmt_foto = $pdo->prepare("SELECT foto_dosen FROM data_dosen WHERE id_dosen = :id");
        $stmt_foto->execute([':id' => $id]);
        $data = $stmt_foto->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $nama_foto = $data['foto_dosen'];
            $path_foto = "../upload/" . $nama_foto;

            // 4. Hapus file fisik dari folder jika file tersebut ada
            if (!empty($nama_foto) && file_exists($path_foto)) {
                unlink($path_foto); // Menghapus file
            }

            // 5. Hapus data dari database
            $stmt_hapus = $pdo->prepare("DELETE FROM data_dosen WHERE id_dosen = :id");
            $stmt_hapus->execute([':id' => $id]);

            echo "<script>
                    window.location.href = '../loading/loading_hapus_dosen.php';
                  </script>";
        } else {
            throw new Exception("Data tidak ditemukan.");
        }

    } catch (Exception $e) {
        echo "<script>
                alert('Gagal menghapus data: " . addslashes($e->getMessage()) . "');
                window.location.href = '../admin_panel/data_dosen.php';
              </script>";
    }
} else {
    // Jika mencoba akses file ini tanpa ID
    header("Location: ../admin_panel/data_dosen.php");
    exit();
}
?>