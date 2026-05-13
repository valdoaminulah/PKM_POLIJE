<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 2. Pastikan data dikirim via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Ambil data dan bersihkan spasi kosong
        $judul  = trim($_POST['judul_pesan']);
        $tujuan = isset($_POST['tujuan_pesan']) ? $_POST['tujuan_pesan'] : '';
        $pesan  = trim($_POST['isi_pesan']);

        // 3. Validasi: Semua field wajib diisi
        if (empty($judul) || empty($tujuan) || empty($pesan)) {
            throw new Exception("Lengkapi semua form! Judul, Tujuan, dan Isi Pesan tidak boleh kosong.");
        }

        // 4. Query Insert ke tabel 'pesan'
        $sql = "INSERT INTO pesan (judul_pesan, tujuan_pesan, isi_pesan) 
                VALUES (:judul, :tujuan, :pesan)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':judul'  => $judul,
            ':tujuan' => $tujuan,
            ':pesan'  => $pesan
        ]);

        // 5. Berhasil: Redirect dengan notifikasi
        echo "<script>
                window.location.href = '../loading/loading_tambah_broadcast.php';
              </script>";

    } catch (Exception $e) {
        // 6. Gagal: Tampilkan pesan error dan kembali ke halaman sebelumnya
        echo "<script>
                alert('Gagal Mengirim: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
} else {
    // Jika mencoba akses langsung file ini tanpa POST
    header("Location: ../admin_panel/broadcast.php");
    exit();
}
?>