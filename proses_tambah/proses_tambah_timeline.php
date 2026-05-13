<?php
session_start();
require_once '../koneksi/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 2. Cek apakah data dikirim melalui POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Ambil data dari form
        $judul      = trim($_POST['judul_jadwal']);
        $tgl_mulai  = $_POST['tgl_mulai'];
        $tgl_akhir  = $_POST['tgl_berakhir'];
        $keterangan = trim($_POST['keterangan']);

        // 3. Validasi Dasar
        if (empty($judul) || empty($tgl_mulai) || empty($tgl_akhir)) {
            throw new Exception("Judul dan Tanggal tidak boleh kosong!");
        }

        // 4. Validasi Logika Tanggal
        // Mengubah string tanggal ke objek waktu untuk dibandingkan
        if (strtotime($tgl_akhir) < strtotime($tgl_mulai)) {
            throw new Exception("Tanggal berakhir tidak boleh lebih awal dari tanggal mulai.");
        }

        // 5. Query Insert ke Database
        $sql = "INSERT INTO jadwal_pkm (judul_jadwal, tgl_mulai, tgl_berakhir, keterangan) 
                VALUES (:judul, :tgl_mulai, :tgl_akhir, :keterangan)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':judul'      => $judul,
            ':tgl_mulai'  => $tgl_mulai,
            ':tgl_akhir'  => $tgl_akhir,
            ':keterangan' => $keterangan
        ]);

        // 6. Feedback Berhasil
        echo "<script>
                window.location.href = '../loading/loading_tambah_timeline.php';
              </script>";

    } catch (Exception $e) {
        // 7. Feedback Gagal
        echo "<script>
                alert('Gagal: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
} else {
    // Jika akses ilegal tanpa POST
    header("Location: ../admin_panel/timeline.php");
    exit();
}
?>