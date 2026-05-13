<?php
session_start();
// Memanggil koneksi database (pastikan path folder benar)
require_once "../koneksi/koneksi.php"; 

if (isset($_POST['login'])) {
    // 1. Ambil data dari form dan bersihkan spasi
    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $kategori = isset($_POST['kategori']) ? $_POST['kategori'] : '';

    // Validasi input kosong
    if (empty($email) || empty($password) || empty($kategori)) {
        echo "<script>alert('Harap isi semua bidang!'); window.history.back();</script>";
        exit();
    }

    try {
        // 2. Cari user berdasarkan email_polije
        $query = "SELECT * FROM user_mahasiswa WHERE email_polije = :email LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // 3. Verifikasi Password Hash
            // Membandingkan password input dengan hash di database
            if (password_verify($password, $user['password'])) {
                
                // LOGIN BERHASIL - Simpan SEMUA data tabel ke Session
                $_SESSION['status_login'] = true;
                $_SESSION['id_user']      = $user['id'];
                $_SESSION['nama']         = $user['nama_lengkap'];
                $_SESSION['nim']          = $user['nim'];
                $_SESSION['email']        = $user['email_polije'];
                $_SESSION['wa']           = $user['no_whatsapp'];
                $_SESSION['gender']       = $user['gender'];
                $_SESSION['jurusan']      = $user['jurusan'];
                $_SESSION['prodi']        = $user['program_studi'];
                $_SESSION['angkatan']     = $user['angkatan'];

                // 4. Redirect berdasarkan kategori yang dipilih di dropdown login
                if ($kategori === "buat") {
                    // Jika memilih 'Buat Tim'
                    header("Location: ../Aplikasi_PKM/buat_tim/beranda.php");
                } elseif ($kategori === "cari") {
                    // Jika memilih 'Cari Tim'
                    header("Location: ../Aplikasi_PKM/cari_tim/beranda.php");
                } else {
                    // Default jika terjadi error pada nilai kategori
                    header("Location: ../auth/login.php");
                }
                exit();

            } else {
                // Password tidak cocok dengan hash
                echo "<script>alert('Login Gagal: Password salah!'); window.history.back();</script>";
            }
        } else {
            // Email tidak ditemukan di database
            echo "<script>alert('Login Gagal: Email tidak terdaftar!'); window.history.back();</script>";
        }

    } catch (PDOException $e) {
        // Error handling jika ada masalah pada database
        die("Terjadi kesalahan sistem: " . $e->getMessage());
    }
} else {
    // Jika user mencoba akses file ini secara langsung tanpa submit form
    header("Location: ../auth/login.php");
    exit();
}
?>