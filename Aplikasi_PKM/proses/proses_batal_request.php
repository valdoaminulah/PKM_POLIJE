<?php
session_start();
require_once "../config/koneksi.php";

// 1. Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pendaftaran = $_POST['id_pendaftaran'];
    $id_user = $_SESSION['id_user'];

    try {
        // 2. Keamanan: Cek apakah pendaftaran ini benar milik user yang sedang login
        // Dan pastikan statusnya masih 'Pending' (Tidak bisa membatalkan jika sudah diterima/ditolak)
        $cek_sql = "SELECT id_pendaftaran FROM pendaftaran_tim 
                    WHERE id_pendaftaran = :id_p AND id_mahasiswa = :id_u AND status_pendaftaran = 'Pending'";
        $stmt_cek = $pdo->prepare($cek_sql);
        $stmt_cek->execute([
            ':id_p' => $id_pendaftaran,
            ':id_u' => $id_user
        ]);

        if ($stmt_cek->fetch()) {
            // 3. Proses hapus data permintaan
            $delete_sql = "DELETE FROM pendaftaran_tim WHERE id_pendaftaran = :id_p";
            $stmt_delete = $pdo->prepare($delete_sql);
            $stmt_delete->execute([':id_p' => $id_pendaftaran]);

            echo "<script>
                    window.location.href = '../loading/loading_batal_request_join.php';
                  </script>";
        } else {
            // Jika data tidak ditemukan atau status sudah bukan pending
            echo "<script>
                    alert('Gagal membatalkan: Permintaan tidak ditemukan atau sudah diproses oleh Ketua Tim.');
                    window.location.href = '../cari_tim/status.php';
                  </script>";
        }

    } catch (PDOException $e) {
        die("Terjadi kesalahan sistem: " . $e->getMessage());
    }
} else {
    header("Location: ../cari_tim/status.php");
    exit;
}
?>