<?php
session_start();
include '../koneksi/koneksi.php';

// 1. Proteksi
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login_user/login_user.php");
    exit();
}

// 2. Tangkap ID
$id = $_GET['id'] ?? '';

if (!empty($id)) {
    try {
        // --- PERBAIKAN: Hapus File Gambar KHS Terlebih Dahulu ---
        
        // A. Ambil nama file gambar dari database sebelum datanya dihapus
        $queryFoto = $pdo->prepare("SELECT khs_image FROM user_mahasiswa WHERE id = ?");
        $queryFoto->execute([$id]);
        $dataFoto = $queryFoto->fetch(PDO::FETCH_ASSOC);

        if ($dataFoto && !empty($dataFoto['khs_image'])) {
            $pathFile = "../KHS_image/" . $dataFoto['khs_image'];
            
            // B. Cek apakah file fisik ada di folder, jika ada maka hapus (unlink)
            if (file_exists($pathFile)) {
                unlink($pathFile);
            }
        }

        // --- SELESAI Hapus File ---

        // 3. Eksekusi Query Hapus Data di Database
        $sql = "DELETE FROM user_mahasiswa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        // 4. ALIKAN KE LOADING HAPUS
        header("Location: ../loading/sukses_hapus_data_mahasiswa.php");
        exit();

    } catch (PDOException $e) {
        die("Gagal menghapus data: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_panel/data_mahasiswa.php");
    exit();
}
?>