<?php
session_start();
require_once "../config/koneksi.php";

// 1. Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$id_tim  = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id_tim) {
    header("Location: ../buat_tim/daftar_tim.php");
    exit;
}

try {
    // 2. Cek keamanan ketua
    $stmt_cek = $pdo->prepare("SELECT id_ketua FROM data_tim_pkm WHERE id_tim = :id_t");
    $stmt_cek->execute([':id_t' => $id_tim]);
    $tim = $stmt_cek->fetch();

    if (!$tim) {
        header("Location: ../buat_tim/daftar_tim.php?pesan=tidak_ditemukan");
        exit;
    }

    if ($tim['id_ketua'] != $id_user) {
        header("Location: ../buat_tim/daftar_tim.php?pesan=akses_ditolak");
        exit;
    }

    // 3. Transaksi Database
    $pdo->beginTransaction();

    // Hapus pendaftaran anggota
    $stmt_del_anggota = $pdo->prepare("DELETE FROM pendaftaran_tim WHERE id_tim = :id_t");
    $stmt_del_anggota->execute([':id_t' => $id_tim]);

    // Hapus data tim utama
    $stmt_del_tim = $pdo->prepare("DELETE FROM data_tim_pkm WHERE id_tim = :id_t");
    $stmt_del_tim->execute([':id_t' => $id_tim]);

    $pdo->commit();

    // 4. PINDAH LANGSUNG KE HALAMAN LOADING
    // Menggunakan header Location jauh lebih cepat dan pasti jalan dibanding echo script
    header("Location: ../loading/loading_hapus_tim.php");
    exit();

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Gagal menghapus tim: " . $e->getMessage());
}
?>