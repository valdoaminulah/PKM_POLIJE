<?php
session_start();

// 1. Kosongkan semua data di array $_SESSION
$_SESSION = array();

// 2. Hapus cookie sesi di browser untuk keamanan total
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 3. Hancurkan sesi di server
session_destroy();

// 4. Langsung pindah ke halaman login (tanpa animasi loading)
// Pastikan path ../auth/login.php sudah sesuai dengan struktur folder kamu
header("Location: ../loading/loading_logout.php");
exit();
?>