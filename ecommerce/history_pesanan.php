<?php
session_start();
include('../dasboard_uas/includes/db.php'); // Menghubungkan ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Jika belum login, alihkan ke halaman login
    exit();
}

$user_id = $_SESSION['user_id'];

// Mengambil riwayat pesanan dari tabel pesanan
$order_query = "SELECT p.id, p.nama_penerima, p.alamat, p.metode_pembayaran, p.total_harga, p.status, p.tanggal_pesanan
                FROM pesanan p
                WHERE p.id_user = '$user_id'
                ORDER BY p.tanggal_pesanan DESC";
$order_result = mysqli_query($conn, $order_query);

// Mengecek apakah pesanan ditemukan
if (mysqli_num_rows($order_result) === 0) {
    echo "Anda belum memiliki pesanan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Riwayat Pesanan</h2>

        <!-- Menampilkan Riwayat Pesanan -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Penerima</th>
                    <th>Total Harga</th>
                    <th>Status Pesanan</th>
                    <th>Tanggal Pesanan</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($order = mysqli_fetch_assoc($order_result)):
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $order['nama_penerima']; ?></td>
                    <td>Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td><?php echo $order['tanggal_pesanan']; ?></td>
                    <td><a href="konfirmasi.php?order_id=<?php echo $order['id']; ?>" class="btn btn-info">Lihat Detail</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Tombol untuk Kembali ke Halaman Utama -->
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary">Kembali ke Belanja</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
