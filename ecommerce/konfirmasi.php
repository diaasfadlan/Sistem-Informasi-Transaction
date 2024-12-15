<?php
session_start();
include('../dasboard_uas/includes/db.php'); // Menghubungkan ke database

// Cek apakah ada ID pesanan yang diterima
if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit();
}

// Menggunakan prepared statement untuk menghindari SQL Injection
$order_id = $_GET['order_id'];

// Mengambil data pesanan dari tabel pesanan
$order_query = "SELECT p.id_user, p.nama_penerima, p.alamat, p.metode_pembayaran, p.total_harga, p.status, p.tanggal_pesanan, u.nama AS nama_user 
                FROM pesanan p
                JOIN pengguna u ON p.id_user = u.id
                WHERE p.id = ?";
$stmt = mysqli_prepare($conn, $order_query);
mysqli_stmt_bind_param($stmt, "i", $order_id); // Bind parameter untuk memastikan tipe data yang sesuai
mysqli_stmt_execute($stmt);
$order_result = mysqli_stmt_get_result($stmt);

// Mengecek apakah query pesanan berhasil
if (mysqli_num_rows($order_result) === 0) {
    echo "Pesanan tidak ditemukan.";
    exit();
}

$order = mysqli_fetch_assoc($order_result);

// Mengambil detail pesanan
$detail_query = "SELECT dp.id_produk, pr.nama_produk, dp.jumlah, dp.harga, dp.total
                 FROM detail_pesanan dp
                 JOIN produk pr ON dp.id_produk = pr.id
                 WHERE dp.id_pesanan = ?";
$stmt_detail = mysqli_prepare($conn, $detail_query);
mysqli_stmt_bind_param($stmt_detail, "i", $order_id);
mysqli_stmt_execute($stmt_detail);
$detail_result = mysqli_stmt_get_result($stmt_detail);

// Mengecek apakah query detail pesanan berhasil
if (!$detail_result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Konfirmasi Pesanan</h2>

        <div class="alert alert-success">
            <strong>Pesanan Anda telah berhasil dibuat!</strong> Berikut adalah rincian pesanan Anda.
        </div>

        <h4>Detail Pesanan</h4>

        <table class="table table-striped table-bordered mt-4">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Informasi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Nama Pengguna</strong></td>
                    <td><?php echo htmlspecialchars($order['nama_user']); ?></td>
                </tr>
                <tr>
                    <td><strong>Nama Penerima</strong></td>
                    <td><?php echo htmlspecialchars($order['nama_penerima']); ?></td>
                </tr>
                <tr>
                    <td><strong>Alamat Pengiriman</strong></td>
                    <td><?php echo htmlspecialchars($order['alamat']); ?></td>
                </tr>
                <tr>
                    <td><strong>Metode Pembayaran</strong></td>
                    <td><?php echo htmlspecialchars($order['metode_pembayaran']); ?></td>
                </tr>
                <tr>
                    <td><strong>Status Pesanan</strong></td>
                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                </tr>
                <tr>
                    <td><strong>Tanggal Pesanan</strong></td>
                    <td><?php echo date("d-m-Y H:i:s", strtotime($order['tanggal_pesanan'])); ?></td>
                </tr>
            </tbody>
        </table>

        <h4>Rincian Produk</h4>

        <table class="table table-striped table-bordered mt-4">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (mysqli_num_rows($detail_result) > 0) {
                    while ($row = mysqli_fetch_assoc($detail_result)): 
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo $row['jumlah']; ?></td>
                    <td>Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; 
                } else {
                    echo "<tr><td colspan='4'>Detail pesanan tidak ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="text-right mt-4">
            <h4><strong>Total Harga: Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></strong></h4>
        </div>

        <!-- Tombol untuk Kembali ke Halaman Utama -->
        <div class="row mt-4">
            <div class="col-6">
                <a href="index.php" class="btn btn-primary btn-block">Kembali ke Belanja</a>
            </div>
            <div class="col-6 text-end">
                <a href="history_pesanan.php" class="btn btn-secondary btn-block">Lihat Riwayat Pesanan</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
