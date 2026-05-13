<?php
// 1. Memulai session agar PHP tahu session mana yang akan dihapus
session_start();

// 2. Menghapus semua data yang tersimpan di variabel $_SESSION
$_SESSION = array();

// 3. Menghapus cookie session jika ada (opsional tapi disarankan untuk keamanan)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Menghancurkan (destroy) session sepenuhnya di sisi server
session_destroy();

// 5. Mengarahkan user kembali ke halaman login atau homepage
echo "<script>
        window.location.href = '../loading/loading_logout_user.php';
      </script>";
exit();
?>