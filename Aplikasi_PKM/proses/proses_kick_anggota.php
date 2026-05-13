<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) { exit; }

$id_pendaftaran = $_GET['id_p'] ?? null;
$id_tim = $_GET['id_t'] ?? null;
$id_ketua = $_SESSION['id_user'];

try {
    // 1. Validasi: Pastikan yang melakukan kick adalah ketua tim yang sah
    $stmt_cek = $pdo->prepare("SELECT id_tim FROM data_tim_pkm WHERE id_tim = :id_t AND id_ketua = :id_k");
    $stmt_cek->execute([':id_t' => $id_tim, ':id_k' => $id_ketua]);
    
    if ($stmt_cek->fetch()) {
        // 2. Proses Hapus: Kita hapus datanya dari tabel pendaftaran_tim
        $stmt_kick = $pdo->prepare("DELETE FROM pendaftaran_tim WHERE id_pendaftaran = :id_p AND id_tim = :id_t");
        $stmt_kick->execute([':id_p' => $id_pendaftaran, ':id_t' => $id_tim]);

        echo "<script>
                window.location.href = '../loading/loading_kick_anggota.php?id=" . $id_tim . "';
              </script>";
    } else {
        die("Akses Ilegal: Anda bukan ketua tim.");
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>