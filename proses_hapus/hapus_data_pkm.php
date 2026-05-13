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
        // 3. Cari nama file foto sebelum data dihapus dari database
        $stmt_foto = $pdo->prepare("SELECT foto_pkm FROM data_pkm WHERE id_pkm = :id");
        $stmt_foto->execute([':id' => $id]);
        $pkm = $stmt_foto->fetch(PDO::FETCH_ASSOC);

        if ($pkm) {
            $nama_foto = $pkm['foto_pkm'];
            $path_foto = "../upload/" . $nama_foto;

            // 4. Hapus file fisik banner jika ada di folder upload
            if (!empty($nama_foto) && file_exists($path_foto)) {
                unlink($path_foto);
            }

            // 5. Hapus data dari database
            $stmt_hapus = $pdo->prepare("DELETE FROM data_pkm WHERE id_pkm = :id");
            $stmt_hapus->execute([':id' => $id]);

            echo "<script>
                    window.location.href = '../loading/loading_hapus_pkm.php';
                  </script>";
        } else {
            throw new Exception("Data PKM tidak ditemukan.");
        }

    } catch (Exception $e) {
        echo "<script>
                alert('Gagal menghapus data: " . addslashes($e->getMessage()) . "');
                window.location.href = '../admin_panel/informasi_pkm.php';
              </script>";
    }
} else {
    // Jika akses langsung tanpa ID
    header("Location: ../admin_panel/informasi_pkm.php");
    exit();
}
?>