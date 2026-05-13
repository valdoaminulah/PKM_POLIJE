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
        $id_jadwal  = $_POST['id_jadwal'];
        $judul      = trim($_POST['judul_jadwal']);
        $tgl_mulai  = $_POST['tgl_mulai'];
        $tgl_akhir  = $_POST['tgl_berakhir'];
        $keterangan = trim($_POST['keterangan']);

        // 3. Validasi Dasar
        if (empty($id_jadwal) || empty($judul) || empty($tgl_mulai) || empty($tgl_akhir)) {
            throw new Exception("Semua data wajib diisi kecuali keterangan.");
        }

        // 4. Validasi Logika Tanggal (Selesai tidak boleh sebelum Mulai)
        if (strtotime($tgl_akhir) < strtotime($tgl_mulai)) {
            throw new Exception("Tanggal berakhir tidak boleh lebih awal dari tanggal mulai.");
        }

        // 5. Query Update Data
        $sql = "UPDATE jadwal_pkm SET 
                    judul_jadwal = :judul, 
                    tgl_mulai = :tgl_mulai, 
                    tgl_berakhir = :tgl_akhir, 
                    keterangan = :keterangan 
                WHERE id_jadwal = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':judul'      => $judul,
            ':tgl_mulai'  => $tgl_mulai,
            ':tgl_akhir'  => $tgl_akhir,
            ':keterangan' => $keterangan,
            ':id'         => $id_jadwal
        ]);

        // 6. Feedback Berhasil
        echo "<script>
                window.location.href = '../loading/loading_edit_timeline.php';
              </script>";

    } catch (Exception $e) {
        // 7. Feedback Gagal
        echo "<script>
                alert('Gagal Memperbarui: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: ../admin_panel/timeline.php");
    exit();
}
?>