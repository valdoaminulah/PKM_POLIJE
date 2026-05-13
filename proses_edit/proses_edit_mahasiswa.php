<?php
session_start();
include '../koneksi/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Tangkap Data dari Form
    $id           = $_POST['id'] ?? '';
    $nim_lama     = $_POST['nim_lama'] ?? '';
    $nim_baru     = $_POST['nim_baru'] ?? '';
    $nama         = $_POST['nama_lengkap'] ?? '';
    $email        = $_POST['email_polije'] ?? '';
    $whatsapp     = $_POST['no_whatsapp'] ?? '';
    $gender       = $_POST['gender'] ?? '';
    $jurusan      = $_POST['jurusan'] ?? '';
    $prodi        = $_POST['program_studi'] ?? '';
    $password_raw = $_POST['password'] ?? '';
    $khs_lama     = $_POST['khs_lama'] ?? ''; // Nama file lama dari input hidden

    // Validasi dasar
    if (empty($id) || empty($nim_baru)) {
        die("Error: ID atau NIM tidak boleh kosong.");
    }

    try {
        // 2. Cek duplikasi NIM jika NIM diganti
        if ($nim_baru !== $nim_lama) {
            $cek = $pdo->prepare("SELECT COUNT(*) FROM user_mahasiswa WHERE nim = ? AND id != ?");
            $cek->execute([$nim_baru, $id]);
            if ($cek->fetchColumn() > 0) {
                echo "<script>alert('Gagal: NIM $nim_baru sudah digunakan mahasiswa lain!'); window.history.back();</script>";
                exit;
            }
        }

        // 3. Logika Update File KHS
        $nama_file_final = $khs_lama; // Default gunakan yang lama

        if (isset($_FILES['khs_image']) && $_FILES['khs_image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../KHS_image/";
            
            // Ambil ekstensi file
            $file_extension = strtolower(pathinfo($_FILES["khs_image"]["name"], PATHINFO_EXTENSION));
            $allowed_types  = ['jpg', 'jpeg', 'png'];

            if (in_array($file_extension, $allowed_types)) {
                // Buat nama file baru yang unik
                $nama_file_baru = $nim_baru . "_" . time() . "." . $file_extension;
                $target_file    = $target_dir . $nama_file_baru;

                if (move_uploaded_file($_FILES["khs_image"]["tmp_name"], $target_file)) {
                    // Hapus file lama jika ada dan filenya memang ada di folder
                    if (!empty($khs_lama) && file_exists($target_dir . $khs_lama)) {
                        unlink($target_dir . $khs_lama);
                    }
                    $nama_file_final = $nama_file_baru;
                }
            } else {
                echo "<script>alert('Format KHS harus JPG/PNG!'); window.history.back();</script>";
                exit;
            }
        }

        // 4. Susun Query SQL Dinamis
        $sql = "UPDATE user_mahasiswa SET 
                nama_lengkap = ?, 
                nim = ?, 
                email_polije = ?, 
                no_whatsapp = ?, 
                gender = ?, 
                jurusan = ?, 
                program_studi = ?, 
                khs_image = ?";
        
        $params = [$nama, $nim_baru, $email, $whatsapp, $gender, $jurusan, $prodi, $nama_file_final];

        // Jika password diisi, tambahkan ke query
        if (!empty($password_raw)) {
            $pass_hash = password_hash($password_raw, PASSWORD_BCRYPT);
            $sql .= ", password = ?";
            $params[] = $pass_hash;
        }

        // Akhiri query dengan WHERE ID
        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // 5. Update Session jika user mengedit profilnya sendiri
        if (isset($_SESSION['nim']) && $_SESSION['nim'] == $nim_lama) {
            $_SESSION['nim'] = $nim_baru;
            $_SESSION['nama'] = $nama; // Update nama di session jika perlu
        }

        header("Location: ../loading/sukses_update.php");
        exit;

    } catch (PDOException $e) {
        die("Error Database: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_panel/data_mahasiswa.php");
    exit;
}