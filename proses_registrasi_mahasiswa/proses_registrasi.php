<?php
// Menampilkan error untuk mempermudah debugging (Matikan jika sudah live)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../koneksi/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Menangkap data dari form
    $nama     = $_POST['nama_lengkap'] ?? '';
    $nim      = $_POST['nim'] ?? '';
    $email    = $_POST['email'] ?? '';
    $whatsapp = $_POST['whatsapp'] ?? '';
    $gender   = $_POST['gender'] ?? '';
    $jurusan  = $_POST['jurusan'] ?? '';
    $prodi    = $_POST['prodi'] ?? '';
    $angkatan = $_POST['angkatan'] ?? '';
    $password = $_POST['password'] ?? '';

    // =========================================================================
    // TAMBAHAN VALIDASI EMAIL WAJIB @student.polije.ac.id
    // =========================================================================
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with(strtolower($email), '@student.polije.ac.id')) {
        echo "<script>
            alert('Registrasi Gagal! Anda wajib menggunakan email institusi resmi (@student.polije.ac.id).'); 
            window.history.back();
        </script>";
        exit;
    }
    // =========================================================================

    // 2. Logika Upload Gambar KHS
    $nama_file_khs = null;
    
    // Cek apakah file dikirim dan tidak ada error
    if (isset($_FILES['khs_image']) && $_FILES['khs_image']['error'] === UPLOAD_ERR_OK) {
        
        // Folder tujuan (Pastikan diakhiri dengan '/')
        $target_dir = "../KHS_image/"; 
        
        // Buat folder secara otomatis jika belum ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name      = $_FILES["khs_image"]["name"];
        $file_tmp       = $_FILES["khs_image"]["tmp_name"];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Beri nama unik: NIM_timestamp.ekstensi agar tidak tertukar
        $nama_file_khs = $nim . "_" . time() . "." . $file_extension;
        $target_file   = $target_dir . $nama_file_khs;

        // Validasi ekstensi
        $allowed_types = ['jpg', 'jpeg', 'png'];
        if (!in_array($file_extension, $allowed_types)) {
            echo "<script>alert('Format gambar harus JPG atau PNG!'); window.history.back();</script>";
            exit;
        }

        // Pindahkan file dari folder sementara ke folder tujuan
        if (!move_uploaded_file($file_tmp, $target_file)) {
            echo "<script>alert('Gagal memindahkan file ke folder tujuan. Cek izin akses folder!'); window.history.back();</script>";
            exit;
        }
    } else {
        // Jika file tidak diupload atau ada error dari PHP (seperti file terlalu besar)
        $error_code = $_FILES['khs_image']['error'] ?? 'No file';
        echo "<script>alert('File KHS wajib diunggah! (Error Code: $error_code)'); window.history.back();</script>";
        exit;
    }

    // 3. Validasi data teks kosong
    if (empty($nama) || empty($nim) || empty($gender) || empty($password)) {
        echo "<script>alert('Data wajib diisi!'); window.history.back();</script>";
        exit;
    }

    // 4. Hash Password
    $pass_hash = password_hash($password, PASSWORD_BCRYPT);

    try {
        // 5. Query SQL
        $sql = "INSERT INTO user_mahasiswa (nama_lengkap, nim, email_polije, no_whatsapp, gender, jurusan, program_studi, angkatan, password, khs_image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            $nama, 
            $nim, 
            $email, 
            $whatsapp, 
            $gender, 
            $jurusan, 
            $prodi, 
            $angkatan, 
            $pass_hash, 
            $nama_file_khs
        ]);

        // 6. Alihkan jika berhasil
        header("Location: ../loading/sukses_register.php");
        exit;

    } catch (PDOException $e) {
        // Hapus file yang sudah terlanjur diupload jika database error (opsional)
        if (file_exists($target_file)) { unlink($target_file); }

        if ($e->getCode() == 23000) {
            echo "<script>alert('Error: NIM atau Email sudah terdaftar!'); window.history.back();</script>";
        } else {
            die("Terjadi kesalahan database: " . $e->getMessage());
        }
    }
} else {
    header("Location: ../auth/register.php");
    exit;
}
?>