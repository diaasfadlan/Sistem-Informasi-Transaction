<?php
$servername = "localhost";
$username = "root";  // Username default XAMPP
$password = "";      // Password default XAMPP (kosong)
$dbname = "ecommerce_uas";  // Nama database Anda

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
