<?php
session_start();
include('includes/db.php');

// Admin username dan password
$admin_user = 'admin';
$admin_pass = 'password';  // Ganti dengan password yang lebih aman

// Cek apakah form login dikirim dari index.html
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_user && $password === $admin_pass) {
        // Jika login berhasil, set session dan redirect ke dashboard
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_dashboard.php');  // Redirect ke halaman dashboard admin
        exit();
    } else {
        // Jika login gagal, tampilkan pesan error
        $error = "Username atau Password salah!";
    }
}

// Logika logout jika tombol logout diklik
if (isset($_GET['logout'])) {
    // Hapus semua variabel sesi
    session_unset();
    session_destroy();

    // Arahkan ke halaman utama (index.php)
    header('Location: ../index.php');
    exit();
}



// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../index.php');  // Redirect ke halaman login jika belum login
    exit();
}





// Ambil daftar produk dari database
$query = "SELECT * FROM produk";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff; /* Warna biru muda untuk latar belakang */
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            background-color: #007bff; /* Biru tua untuk sidebar */
            color: #fff;
            padding: 20px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            margin-bottom: 10px;
            background-color: #0056b3; /* Warna biru gelap untuk tombol */
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #003d80; /* Warna hover lebih gelap */
        }
        .content {
            margin-left: 270px;
            padding: 20px;
        }
        .content h2 {
            font-family: 'Arial', sans-serif;
            font-size: 28px;
            color: #007bff; /* Warna biru */
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2); /* Bayangan teks */
        }
        .card {
            background-color: #ffffff; /* Warna putih untuk kartu */
            border: 1px solid #007bff; /* Border biru */
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.1); /* Efek bayangan */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05); /* Efek zoom */
            box-shadow: 0 6px 10px rgba(0, 123, 255, 0.2); /* Bayangan lebih besar */
        }
        .product-img {
            object-fit: cover;
            width: 100%;
            height: 200px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-title {
            font-family: 'Georgia', serif;
            font-size: 20px;
            color: #0056b3; /* Warna biru gelap */
            font-weight: bold;
            margin-bottom: 10px;
        }
        .card-text {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
        }
        .btn-warning {
            background-color: #ffc107; /* Warna kuning */
            border: none;
        }
        .btn-warning:hover {
            background-color: #e0a800; /* Warna kuning gelap */
        }
        .btn-danger {
            background-color: #dc3545; /* Warna merah */
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333; /* Warna merah gelap */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php?logout=1" style="color:#007bff">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">Dashboard Admin</h4>
        <a href="tambah_produk.php" class="btn btn-success w-100 mb-3">Tambah Produk</a>
        <h5 class="mt-4">Statistik Produk</h5>
        <p>Total Produk: 
            <?php
            $query_total = "SELECT COUNT(*) AS total FROM produk";
            $result_total = mysqli_query($conn, $query_total);
            $total = mysqli_fetch_assoc($result_total)['total'];
            echo $total;
            ?>
        </p>
        <p>Stok Tersedia: 
            <?php
            $query_stok = "SELECT SUM(stok) AS total_stok FROM produk";
            $result_stok = mysqli_query($conn, $query_stok);
            $stok = mysqli_fetch_assoc($result_stok)['total_stok'];
            echo $stok ? $stok : 0;
            ?>
        </p>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <?php
                // Path gambar produk
                $gambar_path = "assets/images/" . $row['gambar'];
                if (!file_exists($gambar_path) || empty($row['gambar'])) {
                    $gambar_path = "assets/images/default.jpg";
                }
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card">
                        <img src="<?= $gambar_path ?>" alt="<?= $row['nama_produk'] ?>" class="card-img-top product-img">
                        <div class="card-body">
                            <h5 class="card-title"><?= $row['nama_produk'] ?></h5>
                            <p class="card-text"><strong>Harga:</strong> Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                            <p class="card-text"><strong>Stok:</strong> <?= $row['stok'] ?></p>
                            <p class="card-text"><strong>Deskripsi:</strong> <?= $row['deskripsi'] ?></p>
                            <div class="d-flex justify-content-between">
                                <a href="edit_produk.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapus_produk.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
