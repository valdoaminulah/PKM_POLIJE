<?php
session_start();
require_once '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_tim = $_POST['id_tim'];
    $id_user = $_SESSION['id_user'];
    $nama_tim = trim($_POST['nama_tim']);
    $kategori = $_POST['kategori_pkm'];
    $deskripsi = trim($_POST['deskripsi_projek']);

    try {
        // Query Update: Hanya jika user adalah ketuanya
        $sql = "UPDATE data_tim_pkm 
                SET nama_tim = :nama, kategori_pkm = :kat, deskripsi_projek = :desc 
                WHERE id_tim = :id AND id_ketua = :id_ketua";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nama' => $nama_tim,
            ':kat' => $kategori,
            ':desc' => $deskripsi,
            ':id' => $id_tim,
            ':id_ketua' => $id_user
        ]);

        echo "<script>
                window.location.href = '../loading/loading_update_data_tim.php';
              </script>";

    } catch (PDOException $e) {
        die("Gagal memperbarui data: " . $e->getMessage());
    }
} else {
    header("Location: ../buat_tim/daftar_tim.php");
    exit;
}