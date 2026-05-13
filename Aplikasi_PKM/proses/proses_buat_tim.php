<?php
session_start();
// Pastikan path koneksi benar sesuai folder kamu
require_once "../config/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Pastikan user sudah login
    if (!isset($_SESSION['id_user'])) {
        header("Location: ../auth/login.php");
        exit;
    }

    $id_ketua     = $_SESSION['id_user'];
    $nama_tim     = trim($_POST['nama_tim']);
    $kategori_pkm = $_POST['kategori_pkm'];
    $deskripsi    = trim($_POST['deskripsi']);

    try {
        // --- VALIDASI: CEK APAKAH USER SUDAH PUNYA TIM ---
        $cek_sql = "SELECT id_tim FROM data_tim_pkm WHERE id_ketua = :id_ketua";
        $stmt_cek = $pdo->prepare($cek_sql);
        $stmt_cek->execute([':id_ketua' => $id_ketua]);
        
        if ($stmt_cek->fetch()) {
            echo "<script>
                    alert('Gagal! Anda sudah terdaftar sebagai ketua di tim lain. Satu mahasiswa hanya boleh membuat satu tim.');
                    window.location.href = '../buat_tim/buat_tim.php';
                  </script>";
            exit;
        }
        // --- END VALIDASI ---

        // 2. Query Insert (Perbaikan Typo pada :id_ketua)
        $sql = "INSERT INTO data_tim_pkm (id_ketua, nama_tim, kategori_pkm, deskripsi_projek) 
                VALUES (:id_ketua, :nama_tim, :kategori_pkm, :deskripsi)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_ketua'     => $id_ketua,
            ':nama_tim'     => $nama_tim,
            ':kategori_pkm' => $kategori_pkm,
            ':deskripsi'    => $deskripsi
        ]);

        echo "<script>
                window.location.href = '../loading/loading_buat_tim.php';
              </script>";

    } catch (PDOException $e) {
        // Jangan tampilkan die() di produksi, tapi untuk sekarang ini oke untuk debugging
        die("Gagal membuat tim: " . $e->getMessage());
    }
} else {
    // Perbaikan typo path pada redirect
    header("Location: ../buat_tim/buat_tim.php");
    exit;
}
?>