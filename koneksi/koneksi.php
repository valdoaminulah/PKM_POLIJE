<?php
// Konfigurasi database 
$host     = 'localhost';
$db_name  = 'pkm_database';
$username = 'root';
$password = ''; 

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    
    // Set error mode ke exception untuk mempermudah debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Baris ini bisa kamu hapus jika sudah berhasil konek
    // echo "Koneksi Berhasil!"; 
    
} catch (PDOException $e) {
    // Jika gagal, tampilkan pesan error
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>