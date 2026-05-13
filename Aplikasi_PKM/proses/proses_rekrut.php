<?php
session_start();
require_once "../config/koneksi.php";

$id_pendaftaran = $_GET['id'];
$aksi = $_GET['aksi'];

try {
    // Ambil data pendaftaran
    $stmt = $pdo->prepare("SELECT id_tim FROM pendaftaran_tim WHERE id_pendaftaran = :id");
    $stmt->execute([':id' => $id_pendaftaran]);
    $pendaftaran = $stmt->fetch();

    if ($aksi == 'terima') {
        // Cek kuota tim (Maks 5 termasuk ketua)
        $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM pendaftaran_tim WHERE id_tim = :id_t AND status_pendaftaran = 'Diterima'");
        $stmt_count->execute([':id_t' => $pendaftaran['id_tim']]);
        $anggota_diterima = $stmt_count->fetchColumn();

        if ((1 + $anggota_diterima) >= 5) {
            echo "<script>alert('Kuota tim sudah penuh (Maks 5)!'); window.history.back();</script>";
            exit;
        }

        $status = 'Diterima';
    } else {
        $status = 'Ditolak';
    }

    $update = $pdo->prepare("UPDATE pendaftaran_tim SET status_pendaftaran = :status, updated_at = NOW() WHERE id_pendaftaran = :id");
    $update->execute([':status' => $status, ':id' => $id_pendaftaran]);

    header("Location: ../buat_tim/rekrut_tim.php?msg=success");
} catch (PDOException $e) {
    die($e->getMessage());
}