<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 2. Ambil ID pesan dari parameter URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    try {
        // 3. Persiapkan Query Hapus
        $sql = "DELETE FROM pesan WHERE id_pesan = :id";
        $stmt = $pdo->prepare($sql);
        
        // Eksekusi penghapusan
        $stmt->execute([':id' => $id]);

        // 4. Feedback Berhasil
        echo "<script>
                window.location.href = '../loading/loading_hapus_brodcast.php';
              </script>";

    } catch (PDOException $e) {
        // 5. Feedback Gagal
        echo "<script>
                alert('Gagal menghapus pesan: " . addslashes($e->getMessage()) . "');
                window.location.href = '../admin_panel/broadcast.php';
              </script>";
    }
} else {
    // Jika tidak ada ID, kembalikan ke halaman utama broadcast
    header("Location: ../admin_panel/broadcast.php");
    exit();
}
?>