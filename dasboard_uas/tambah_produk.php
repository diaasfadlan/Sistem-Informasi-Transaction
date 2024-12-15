<?php
session_start();
include('includes/db.php');

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Proses tambah produk
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $nama_produk = $_POST['nama_produk'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    $gambar_size = $_FILES['gambar']['size'];
    $gambar_type = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

    // Validasi file gambar
    $allowed_types = array('jpg', 'jpeg', 'png');
    $max_size = 5 * 1024 * 1024; // Maksimal 5MB

    if (!in_array($gambar_type, $allowed_types)) {
        echo "Hanya file gambar dengan format JPG, JPEG, dan PNG yang diperbolehkan.";
        exit();
    }

    if ($gambar_size > $max_size) {
        echo "Ukuran gambar terlalu besar. Maksimal 5MB.";
        exit();
    }

    // Tentukan lokasi penyimpanan gambar
    $target_dir = "assets/images/";

    // Cek apakah folder ada, jika tidak, buat folder baru
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Buat folder jika belum ada
    }

    $target_file = $target_dir . basename($gambar);

    // Cek apakah file gambar sudah ada
    if (file_exists($target_file)) {
        echo "Gambar dengan nama yang sama sudah ada. Gantilah nama file gambar.";
        exit();
    }

    // Pindahkan file gambar ke folder assets/images
    if (move_uploaded_file($gambar_tmp, $target_file)) {
        // Masukkan data ke database
        $query = "INSERT INTO produk (nama_produk, deskripsi, harga, stok, gambar) 
                  VALUES ('$nama_produk', '$deskripsi', $harga, $stok, '$gambar')";
        
        if (mysqli_query($conn, $query)) {
            header('Location: admin_dashboard.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal meng-upload gambar.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Tambah Produk</h2>
        <form action="tambah_produk.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama_produk" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" required>
            </div>
            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" class="form-control" id="stok" name="stok" required>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar Produk</label>
                <input type="file" class="form-control" id="gambar" name="gambar" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Tambah Produk</button>
        </form>
    </div>
</body>
</html>
