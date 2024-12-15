<?php
session_start();
include('../dasboard_uas/includes/db.php'); // Menghubungkan ke database

// Mengambil daftar produk dari database
$query = "SELECT id, nama_produk, harga, stok, deskripsi, gambar FROM produk";
$result = mysqli_query($conn, $query);

// Cek apakah query berhasil
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Mengambil ID pengguna dari session
$id_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Jika pengguna belum login, arahkan ke halaman login
if (!$id_user) {
    header('Location: login.php'); // Arahkan ke halaman login jika belum login
    exit();
}

// Proses penambahan produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produk'], $_POST['jumlah'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];

    // Cek apakah produk sudah ada di keranjang
    $keranjang_check_query = "SELECT * FROM keranjang WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
    $keranjang_check_result = mysqli_query($conn, $keranjang_check_query);
    
    if (mysqli_num_rows($keranjang_check_result) > 0) {
        // Jika sudah ada, update jumlah produk di keranjang
        $update_query = "UPDATE keranjang SET jumlah = jumlah + $jumlah WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
        mysqli_query($conn, $update_query);
    } else {
        // Jika belum ada, insert produk baru ke keranjang
        $insert_query = "INSERT INTO keranjang (id_user, id_produk, jumlah) VALUES ('$id_user', '$id_produk', '$jumlah')";
        mysqli_query($conn, $insert_query);
    }
    
    header('Location: index.php'); // Arahkan kembali ke halaman index setelah menambahkan ke keranjang
    exit();
}

// Menghitung jumlah produk dalam keranjang
$keranjang_query = "SELECT SUM(jumlah) AS total FROM keranjang WHERE id_user = '$id_user'";
$keranjang_result = mysqli_query($conn, $keranjang_query);
$keranjang_data = mysqli_fetch_assoc($keranjang_result);
$keranjang_count = $keranjang_data['total'] ? $keranjang_data['total'] : 0;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
         font-family: 'Poppins', sans-serif;
         background: black url('../back3.png')no-repeat center center fixed;
         background-size: cover;
         
        }

        h1 {
            font-weight: bold;
            color: #4CAF50;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            background-color: #b3d9ff;
            padding: 10px;
            border-radius: 5px;
        }

        .navbar {
            background-color: #003366 !important;
        }

        .navbar a.navbar-brand,
        .navbar .nav-link {
            color: #ffffff !important;
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">🏠 Eundang's Apotek</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="keranjang.php">Keranjang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Keluar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="text-center mb-4">
    <h1 class="d-inline-block text-white px-200 py-2" style="background-color: #003366; width: 85%; margin: 0 auto;">🌟 Produk Terbaik untuk Anda 🌟</h1>
    </div>

        <div class="keranjang-container mb-4">
            <a href="keranjang.php" class="btn btn-success">
                <i class="bi bi-cart-fill"></i> Keranjang
                (<?php echo $keranjang_count; ?>)
            </a>
        </div>

        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <img class="card-img-top" src="../dasboard_uas/assets/images/<?php echo $row['gambar']; ?>"
                            alt="<?php echo $row['nama_produk']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['nama_produk']; ?> <span
                                    class="badge bg-success">Baru</span></h5>
                            <p class="card-text">Harga: Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                            <p class="card-text">Stok: <?php echo $row['stok']; ?></p>
                            <p class="card-text"><strong>Deskripsi:</strong> <?= $row['deskripsi'] ?></p>
                            <form method="POST" action="index.php">
                                <input type="hidden" name="id_produk" value="<?php echo $row['id']; ?>">
                                <input type="number" name="jumlah" value="1" min="1" max="<?php echo $row['stok']; ?>"
                                       class="form-control mb-2">
                                <button type="submit" class="btn btn-primary w-100" 
                                        <?php if ($row['stok'] <= 0) echo 'disabled'; ?>>Tambah ke Keranjang</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
