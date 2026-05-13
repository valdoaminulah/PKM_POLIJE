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
        // 3. Query Hapus Data
        $stmt = $pdo->prepare("DELETE FROM jadwal_pkm WHERE id_jadwal = :id");
        $stmt->execute([':id' => $id]);

        // 4. Feedback Berhasil
        echo "<script>
                window.location.href = '../loading/loading_hapus_timeline.php';
              </script>";

    } catch (PDOException $e) {
        // 5. Feedback Gagal (Misal karena ada relasi database)
        echo "<script>
                alert('Gagal menghapus data: " . addslashes($e->getMessage()) . "');
                window.location.href = '../admin_panel/timeline.php';
              </script>";
    }
} else {
    // Jika mencoba akses file tanpa kirim ID
    header("Location: ../admin_panel/timeline.php");
    exit();
}
?>