<?php
// Sertakan file koneksi Anda
require_once '../koneksi/koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // 1. Ambil data dari form
        $nama               = $_POST['nama'];
        $nip                = $_POST['nip'];
        $jurusan            = $_POST['jurusan'];
        $wa                 = $_POST['wa'];
        $email              = $_POST['email'];
        $linkedin_name      = $_POST['linkedin_name'];
        $instagram          = $_POST['instagram'];
        $facebook_name      = $_POST['facebook_name'];
        $riwayat_bimbingan  = (int)$_POST['riwayat_bimbingan'];

        // 2. Proses Unggah Foto
        $foto_name = $_FILES['foto_dosen']['name'];
        $tmp_name  = $_FILES['foto_dosen']['tmp_name'];
        $size      = $_FILES['foto_dosen']['size'];
        
        $target_dir = "../upload/";
        
        // Buat folder jika belum ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Penamaan foto unik: dosen_NIP_waktu.ekstensi
        $ext = pathinfo($foto_name, PATHINFO_EXTENSION);
        $new_foto_name = "dosen_" . $nip . "_" . time() . "." . $ext;
        $target_file = $target_dir . $new_foto_name;

        // Validasi file (Hanya gambar & Max 2MB)
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        if (!in_array(strtolower($ext), $allowed_ext)) {
            throw new Exception("Format file harus JPG, JPEG, atau PNG.");
        }
        if ($size > 2000000) {
            throw new Exception("Ukuran file maksimal 2MB.");
        }

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($tmp_name, $target_file)) {
            
            // 3. Query INSERT menggunakan Prepared Statement PDO
            $sql = "INSERT INTO data_dosen (
                        foto_dosen, nama_lengkap, nip, jurusan, no_whatsapp, 
                        email, linkedin_name, instagram_username, facebook_name, riwayat_bimbingan
                    ) VALUES (
                        :foto, :nama, :nip, :jurusan, :wa, 
                        :email, :linkedin, :instagram, :facebook, :riwayat
                    )";

            $stmt = $pdo->prepare($sql);

            // Bind parameter untuk keamanan
            $stmt->execute([
                ':foto'      => $new_foto_name,
                ':nama'      => $nama,
                ':nip'       => $nip,
                ':jurusan'   => $jurusan,
                ':wa'        => $wa,
                ':email'     => $email,
                ':linkedin'  => $linkedin_name,
                ':instagram' => $instagram,
                ':facebook'  => $facebook_name,
                ':riwayat'   => $riwayat_bimbingan
            ]);

            echo "<script>
                    window.location.href='../loading/loading_tambah_dosen.php'; 
                  </script>";
        } else {
            throw new Exception("Gagal mengunggah foto ke server.");
        }

    } catch (Exception $e) {
        // Tangkap error (seperti NIP duplikat atau gagal upload)
        echo "<script>
                alert('Gagal menyimpan data: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
}
?>