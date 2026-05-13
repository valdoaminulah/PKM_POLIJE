<?php
session_start();
require_once '../koneksi/koneksi.php';

// Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'humas') {
    header("Location: ../login_user/login_user.php");
    exit();
}

if (isset($_POST['update'])) {
    try {
        $id        = $_POST['id_berita'];
        $judul     = $_POST['judul_berita'];
        $tanggal   = $_POST['tanggal_publikasi'];
        $link      = $_POST['link_website'];
        $ringkasan = $_POST['ringkasan'];

        // Ambil info file gambar baru
        $nama_file   = $_FILES['gambar_utama']['name'];
        $tmp_name    = $_FILES['gambar_utama']['tmp_name'];
        $error       = $_FILES['gambar_utama']['error'];

        // 1. CEK APAKAH USER MENGUNGGAH GAMBAR BARU
        if ($error === 0) {
            // Proses gambar baru
            $ekstensi_valid = ['jpg', 'jpeg', 'png'];
            $ekstensi_gambar = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

            if (!in_array($ekstensi_gambar, $ekstensi_valid)) {
                echo "<script>alert('Format harus JPG, JPEG, atau PNG!'); window.history.back();</script>";
                exit;
            }

            // Buat nama unik untuk gambar baru
            $nama_file_baru = uniqid() . '.' . $ekstensi_gambar;

            // Hapus gambar lama dari folder uploads agar tidak menumpuk
            $sql_old = "SELECT gambar_utama FROM data_berita WHERE id_berita = :id";
            $stmt_old = $pdo->prepare($sql_old);
            $stmt_old->execute(['id' => $id]);
            $old_data = $stmt_old->fetch();

            if ($old_data && file_exists('../berita/' . $old_data['gambar_utama'])) {
                unlink('../berita/' . $old_data['gambar_utama']);
            }

            // Upload gambar baru
            move_uploaded_file($tmp_name, '../berita/' . $nama_file_baru);

            // Query Update dengan gambar baru
            $sql = "UPDATE data_berita SET 
                    judul_berita = :judul, 
                    tanggal_publikasi = :tanggal, 
                    link_website = :link, 
                    ringkasan = :ringkasan, 
                    gambar_utama = :gambar 
                    WHERE id_berita = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':gambar', $nama_file_baru);
        } else {
            // 2. QUERY UPDATE TANPA MENGGANTI GAMBAR
            $sql = "UPDATE data_berita SET 
                    judul_berita = :judul, 
                    tanggal_publikasi = :tanggal, 
                    link_website = :link, 
                    ringkasan = :ringkasan 
                    WHERE id_berita = :id";
            
            $stmt = $pdo->prepare($sql);
        }

        // Bind parameter umum
        $stmt->bindParam(':judul', $judul);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->bindParam(':link', $link);
        $stmt->bindParam(':ringkasan', $ringkasan);
        $stmt->bindParam(':id', $id);
        
        $stmt->execute();

        echo "<script> window.location.href='../loading/loading_edit_berita.php';</script>";

    } catch (PDOException $e) {
        echo "Error Database: " . $e->getMessage();
    }
} else {
    header("Location: ../humas_panel/tabel_berita.php");
}
?>