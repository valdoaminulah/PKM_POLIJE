<?php
session_start();
include '../koneksi/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass  = md5($_POST['password']); // Mengubah input password menjadi MD5

    try {
        // Cari user berdasarkan email DAN password MD5
        $sql = "SELECT * FROM user_pengelola WHERE email = ? AND password = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $pass]);
        $user = $stmt->fetch();

        if ($user) {
            // Jika data ditemukan, simpan ke Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['email']   = $user['email'];

            // Arahkan ke halaman sesuai role
            if ($user['role'] == 'admin') {
                header("Location: ../admin_panel/dasboard.php");
            } else {
                header("Location: ../humas_panel/dasboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Email atau Password Salah!'); window.history.back();</script>";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>