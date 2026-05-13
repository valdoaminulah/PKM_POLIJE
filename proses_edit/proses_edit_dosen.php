<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Keamanan: Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // 2. Ambil data dari form
        $id_dosen          = $_POST['id_dosen'];
        $nama              = $_POST['nama'];
        $nip               = $_POST['nip'];
        $jurusan           = $_POST['jurusan'];
        $wa                = $_POST['wa'];
        $email             = $_POST['email'];
        $linkedin_name     = $_POST['linkedin_name'];
        $instagram         = $_POST['instagram'];
        $facebook_name     = $_POST['facebook_name'];
        $riwayat_bimbingan = (int)$_POST['riwayat_bimbingan'];
        $foto_lama         = $_POST['foto_lama'];

        // 3. Logika Pengunggahan Foto
        $nama_file_baru = $_FILES['foto_dosen']['name'];
        $tmp_name       = $_FILES['foto_dosen']['tmp_name'];
        
        // Defaultnya, gunakan foto lama
        $foto_final = $foto_lama;

        // Cek jika user mengunggah foto baru
        if (!empty($nama_file_baru)) {
            $target_dir = "../upload/";
            $ext = pathinfo($nama_file_baru, PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png'];

            // Validasi ekstensi
            if (!in_array(strtolower($ext), $allowed_ext)) {
                throw new Exception("Format file harus JPG, JPEG, atau PNG.");
            }

            // Buat nama unik agar tidak bentrok
            $foto_final = "dosen_" . $nip . "_" . time() . "." . $ext;
            $target_file = $target_dir . $foto_final;

            if (move_uploaded_file($tmp_name, $target_file)) {
                // Hapus foto lama dari folder jika ada dan bukan inisial/default
                $path_foto_lama = $target_dir . $foto_lama;
                if (!empty($foto_lama) && file_exists($path_foto_lama)) {
                    unlink($path_foto_lama); // Fungsi menghapus file fisik
                }
            } else {
                throw new Exception("Gagal mengunggah foto baru.");
            }
        }

        // 4. Query Update Data menggunakan Prepared Statement
        $sql = "UPDATE data_dosen SET 
                    foto_dosen = :foto, 
                    nama_lengkap = :nama, 
                    nip = :nip, 
                    jurusan = :jurusan, 
                    no_whatsapp = :wa, 
                    email = :email, 
                    linkedin_name = :linkedin, 
                    instagram_username = :instagram, 
                    facebook_name = :facebook, 
                    riwayat_bimbingan = :riwayat 
                WHERE id_dosen = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':foto'      => $foto_final,
            ':nama'      => $nama,
            ':nip'       => $nip,
            ':jurusan'   => $jurusan,
            ':wa'        => $wa,
            ':email'     => $email,
            ':linkedin'  => $linkedin_name,
            ':instagram' => $instagram,
            ':facebook'  => $facebook_name,
            ':riwayat'   => $riwayat_bimbingan,
            ':id'        => $id_dosen
        ]);

        echo "<script>
                window.location.href='../loading/loading_edit_dosen.php';
              </script>";

    } catch (Exception $e) {
        echo "<script>
                alert('Gagal memperbarui data: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
}
?>