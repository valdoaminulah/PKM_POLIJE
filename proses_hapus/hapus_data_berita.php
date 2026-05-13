<?php
session_start();
require_once '../koneksi/koneksi.php';

// Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'humas') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// Cek apakah ada ID yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // 1. Ambil nama file gambar terlebih dahulu sebelum datanya dihapus
        $sql_select = "SELECT gambar_utama FROM data_berita WHERE id_berita = :id";
        $stmt_select = $pdo->prepare($sql_select);
        $stmt_select->execute(['id' => $id]);
        $data = $stmt_select->fetch();

        if ($data) {
            $nama_gambar = $data['gambar_utama'];
            $path_gambar = '../berita/' . $nama_gambar;

            // 2. Hapus file gambar dari folder jika filenya ada
            if (file_exists($path_gambar)) {
                unlink($path_gambar);
            }

            // 3. Hapus data dari database
            $sql_delete = "DELETE FROM data_berita WHERE id_berita = :id";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->execute(['id' => $id]);

            echo "<script>
                    window.location.href = '../loading/loading_hapus_berita.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Data tidak ditemukan!');
                    window.location.href = ../humas_panel/tabel_berita.php';
                  </script>";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Jika tidak ada ID, kembalikan ke tabel
    header("Location: ../humas/tabel_berita.php");
    exit();
}
?>