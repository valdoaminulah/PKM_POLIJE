<?php
session_start();
require_once "../config/koneksi.php";

// 1. Cek Login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$id_tim  = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id_tim) {
    header("Location: ../cari_tim/daftar_tim.php");
    exit;
}

try {
    // 2. Keamanan: Cek apakah user adalah Ketua di tim tersebut
    // Ketua tidak boleh "Keluar", mereka harus "Hapus Tim" (logika berbeda)
    $stmt_cek_ketua = $pdo->prepare("SELECT id_ketua FROM data_tim_pkm WHERE id_tim = :id_t");
    $stmt_cek_ketua->execute([':id_t' => $id_tim]);
    $tim = $stmt_cek_ketua->fetch();

    if ($tim && $tim['id_ketua'] == $id_user) {
        echo "<script>
                alert('Gagal! Sebagai Ketua Tim, Anda tidak bisa keluar. Gunakan menu Manajemen Tim jika ingin membubarkan tim.');
                window.history.back();
              </script>";
        exit;
    }

    // 3. Proses Keluar (Hapus data pendaftaran)
    $sql_delete = "DELETE FROM pendaftaran_tim 
                   WHERE id_tim = :id_t AND id_mahasiswa = :id_u AND status_pendaftaran = 'Diterima'";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([
        ':id_t' => $id_tim,
        ':id_u' => $id_user
    ]);

    if ($stmt_delete->rowCount() > 0) {
        echo "<script>
                window.location.href = '../loading/loading_keluar_tim.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal keluar: Data keanggotaan tidak ditemukan.');
                window.history.back();
              </script>";
    }

} catch (PDOException $e) {
    die("Terjadi kesalahan sistem: " . $e->getMessage());
}
?>