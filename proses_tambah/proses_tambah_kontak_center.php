<?php
session_start();
require_once '../koneksi/koneksi.php';

// Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama    = trim($_POST['nama_admin']);
    $jurusan = $_POST['jurusan'];
    $wa      = trim($_POST['whatsapp']);
    $lokasi  = trim($_POST['lokasi']);
    
    // Penanganan Upload Foto
    $foto_nama = $_FILES['foto_admin']['name'];
    $foto_tmp  = $_FILES['foto_admin']['tmp_name'];
    $foto_size = $_FILES['foto_admin']['size'];
    $ext_boleh = ['jpg', 'jpeg', 'png'];
    
    $nama_file_baru = "default.jpg"; // Default jika tidak upload

    if (!empty($foto_nama)) {
        $x = explode('.', $foto_nama);
        $ekstensi = strtolower(end($x));
        
        // Validasi Ekstensi & Ukuran (Max 2MB)
        if (in_array($ekstensi, $ext_boleh) === true) {
            if ($foto_size < 2044070) {
                // Generate nama unik agar tidak bentrok
                $nama_file_baru = time() . "_" . $foto_nama;
                // Mengunggah ke folder ../image_center/
                move_uploaded_file($foto_tmp, '../image_center/' . $nama_file_baru);
            } else {
                echo "<script>alert('Ukuran file terlalu besar! Max 2MB'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format file harus JPG/PNG'); window.history.back();</script>";
            exit;
        }
    }

    try {
        $sql = "INSERT INTO data_kontak_center (nama_admin, jurusan, whatsapp, lokasi, foto_admin) 
                VALUES (:nama, :jurusan, :wa, :lokasi, :foto)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nama'    => $nama,
            ':jurusan' => $jurusan,
            ':wa'      => $wa,
            ':lokasi'  => $lokasi,
            ':foto'    => $nama_file_baru
        ]);

        echo "<script>
                window.location.href = '../loading/loading_tambah_kontak.php';
              </script>";

    } catch (PDOException $e) {
        die("Gagal menyimpan ke database: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_panel/kontak_center.php");
    exit();
}