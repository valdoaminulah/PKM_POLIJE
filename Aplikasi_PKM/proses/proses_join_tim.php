<?php
session_start();
require_once "../config/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan session ID ada, jika tidak ada jangan biarkan lanjut
    if (!isset($_SESSION['id_user'])) {
        die("Sesi berakhir, silakan login kembali.");
    }

    $id_tim  = $_POST['id_tim'];
    $id_user = $_SESSION['id_user'];

    try {
        // 1. Cek pendaftaran aktif
        $stmt_cek = $pdo->prepare("SELECT id_pendaftaran FROM pendaftaran_tim 
                                    WHERE id_tim = :id_tim AND id_mahasiswa = :id_user 
                                    AND status_pendaftaran IN ('Pending', 'Diterima')");
        $stmt_cek->execute([':id_tim' => $id_tim, ':id_user' => $id_user]);
        
        if ($stmt_cek->fetch()) {
            echo "<script>alert('Anda sudah mengirim permintaan.'); window.location.href='../cari_tim/lowongan.php';</script>";
            exit;
        }

        // 2. Bersihkan penolakan lama
        $stmt_del = $pdo->prepare("DELETE FROM pendaftaran_tim WHERE id_tim = :id_tim AND id_mahasiswa = :id_user AND status_pendaftaran = 'Ditolak'");
        $stmt_del->execute([':id_tim' => $id_tim, ':id_user' => $id_user]);

        // 3. Insert data baru
        $sql = "INSERT INTO pendaftaran_tim (id_tim, id_mahasiswa, status_pendaftaran, created_at) 
                VALUES (:id_tim, :id_user, 'Pending', NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_tim' => $id_tim, ':id_user' => $id_user]);

        // Gunakan JS redirect untuk menghindari header conflict
        echo "<script> window.location.href='../loading/loading_request_join.php';</script>";
        exit;

    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
} else {
    header("Location: ../cari_tim/lowongan.php");
    exit;
}