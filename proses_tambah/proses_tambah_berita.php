<?php
session_start();
require_once '../koneksi/koneksi.php'; // Pastikan path ini benar

// Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'humas') {
    header("Location: ../login_user/login_user.php");
    exit();
}

if (isset($_POST['submit'])) {
    try {
        // Ambil data dari form (PDO tidak butuh real_escape_string jika pakai prepared statements)
        $judul    = $_POST['judul_berita'];
        $tanggal  = $_POST['tanggal_publikasi'];
        $link     = $_POST['link_website'];
        $ringkasan = $_POST['ringkasan'];

        // Proses Upload Gambar
        $nama_file   = $_FILES['gambar_utama']['name'];
        $ukuran_file = $_FILES['gambar_utama']['size'];
        $error       = $_FILES['gambar_utama']['error'];
        $tmp_name    = $_FILES['gambar_utama']['tmp_name'];

        if ($error === 0) {
            $ekstensi_valid = ['jpg', 'jpeg', 'png'];
            $ekstensi_gambar = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

            if (!in_array($ekstensi_gambar, $ekstensi_valid)) {
                echo "<script>alert('Format harus JPG, JPEG, atau PNG!'); window.history.back();</script>";
                exit;
            }

            if ($ukuran_file > 5000000) {
                echo "<script>alert('Ukuran Maks 5MB!'); window.history.back();</script>";
                exit;
            }

            $nama_file_baru = uniqid() . '.' . $ekstensi_gambar;

            if (move_uploaded_file($tmp_name, '../berita/' . $nama_file_baru)) {
                
                // --- PROSES INSERT DENGAN PDO ---
                $sql = "INSERT INTO data_berita (judul_berita, tanggal_publikasi, link_website, ringkasan, gambar_utama) 
                        VALUES (:judul, :tanggal, :link, :ringkasan, :gambar)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':judul', $judul);
                $stmt->bindParam(':tanggal', $tanggal);
                $stmt->bindParam(':link', $link);
                $stmt->bindParam(':ringkasan', $ringkasan);
                $stmt->bindParam(':gambar', $nama_file_baru);
                
                $stmt->execute();

                echo "<script> window.location.href='../loading/loading_tambah_berita.php';</script>";
            } else {
                echo "Gagal mengunggah gambar ke folder uploads.";
            }
        } else {
            echo "Error pada file gambar.";
        }
    } catch (PDOException $e) {
        echo "Error Database: " . $e->getMessage();
    }
}
?>