<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id      = $_POST['id_kontak'];
    $nama    = trim($_POST['nama_admin']);
    $jurusan = $_POST['jurusan'];
    $wa      = trim($_POST['whatsapp']);
    $lokasi  = trim($_POST['lokasi']);

    try {
        // 2. Ambil data lama untuk cek foto lama
        $stmt_lama = $pdo->prepare("SELECT foto_admin FROM data_kontak_center WHERE id_kontak = :id");
        $stmt_lama->execute([':id' => $id]);
        $data_lama = $stmt_lama->fetch(PDO::FETCH_ASSOC);

        $foto_nama = $_FILES['foto_admin']['name'];
        $foto_tmp  = $_FILES['foto_admin']['tmp_name'];
        
        // Jika ada foto baru yang diupload
        if (!empty($foto_nama)) {
            $ext_boleh = ['jpg', 'jpeg', 'png'];
            $x = explode('.', $foto_nama);
            $ekstensi = strtolower(end($x));

            if (in_array($ekstensi, $ext_boleh)) {
                // Buat nama unik
                $nama_file_baru = time() . "_" . $foto_nama;
                $path_baru = '../image_center/' . $nama_file_baru;

                if (move_uploaded_file($foto_tmp, $path_baru)) {
                    // Hapus foto lama jika bukan default.jpg
                    if ($data_lama['foto_admin'] != 'default.jpg' && file_exists('../image_center/' . $data_lama['foto_admin'])) {
                        unlink('../image_center/' . $data_lama['foto_admin']);
                    }
                    $foto_final = $nama_file_baru;
                }
            } else {
                echo "<script>alert('Format foto tidak valid!'); window.history.back();</script>";
                exit;
            }
        } else {
            // Jika tidak upload foto baru, pakai foto lama
            $foto_final = $data_lama['foto_admin'];
        }

        // 3. Update Database
        $sql = "UPDATE data_kontak_center 
                SET nama_admin = :nama, 
                    jurusan = :jurusan, 
                    whatsapp = :wa, 
                    lokasi = :lokasi, 
                    foto_admin = :foto 
                WHERE id_kontak = :id";
        
        $stmt_update = $pdo->prepare($sql);
        $stmt_update->execute([
            ':nama'    => $nama,
            ':jurusan' => $jurusan,
            ':wa'      => $wa,
            ':lokasi'  => $lokasi,
            ':foto'    => $foto_final,
            ':id'      => $id
        ]);

        echo "<script>
                window.location.href = '../loading/loading_edit_kontak.php';
              </script>";

    } catch (PDOException $e) {
        die("Gagal memperbarui data: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_panel/kontak_center.php");
    exit();
}