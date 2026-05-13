<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 2. Pastikan data dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Ambil data dari form edit
        $id_pesan   = $_POST['id_pesan'];
        $judul      = trim($_POST['judul_pesan']);
        $tujuan     = $_POST['tujuan_pesan'];
        $isi_pesan  = trim($_POST['isi_pesan']);

        // 3. Validasi Dasar: Pastikan tidak ada field yang dikosongkan
        if (empty($id_pesan) || empty($judul) || empty($tujuan) || empty($isi_pesan)) {
            throw new Exception("Semua kolom (Judul, Tujuan, dan Isi) wajib diisi!");
        }

        // 4. Query Update ke Database
        $sql = "UPDATE pesan SET 
                    judul_pesan  = :judul, 
                    tujuan_pesan = :tujuan, 
                    isi_pesan    = :isi_pesan 
                WHERE id_pesan   = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':judul'     => $judul,
            ':tujuan'    => $tujuan,
            ':isi_pesan' => $isi_pesan,
            ':id'         => $id_pesan
        ]);

        // 5. Feedback Berhasil
        echo "<script>
                window.location.href = '../loading/loading_edit_broadcast.php';
              </script>";

    } catch (Exception $e) {
        // 6. Feedback Gagal
        echo "<script>
                alert('Gagal memperbarui pesan: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
} else {
    // Jika diakses tanpa melalui form (akses ilegal)
    header("Location: ../admin_panel/broadcast.php");
    exit();
}
?>