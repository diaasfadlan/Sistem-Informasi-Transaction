<?php
session_start();
include('../dasboard_uas/includes/db.php'); // Menghubungkan ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Jika belum login, alihkan ke halaman login
    exit();
}

// Mengambil ID pengguna dari session
$id_user = $_SESSION['user_id'];

// Mengambil data produk dalam keranjang
$query = "SELECT p.id, p.nama_produk, p.harga, k.jumlah, (p.harga * k.jumlah) AS total 
          FROM keranjang k
          JOIN produk p ON k.id_produk = p.id
          WHERE k.id_user = '$id_user'";
$result = mysqli_query($conn, $query);

// Hitung total harga
$total_harga = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $total_harga += $row['total'];
}

// Menangani pengisian alamat dan metode pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_penerima = $_POST['nama_penerima'];
    $alamat = $_POST['alamat'];
    $metode_pembayaran = $_POST['metode_pembayaran'];

    // Insert data pesanan ke tabel pesanan
    $order_query = "INSERT INTO pesanan (id_user, nama_penerima, total_harga, alamat, metode_pembayaran, status) 
                    VALUES ('$id_user', '$nama_penerima', '$total_harga', '$alamat', '$metode_pembayaran', 'Menunggu Pembayaran')";
    
    if (mysqli_query($conn, $order_query)) {
        // Ambil id pesanan yang baru saja dibuat
        $order_id = mysqli_insert_id($conn);

        // Insert detail pesanan
        mysqli_data_seek($result, 0); // Reset pointer hasil query
        while ($row = mysqli_fetch_assoc($result)) {
            $id_produk = $row['id'];
            $jumlah = $row['jumlah'];
            $harga = $row['harga'];
            $total = $row['total'];

            // Insert ke tabel detail pesanan
            $detail_query = "INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga, total) 
                             VALUES ('$order_id', '$id_produk', '$jumlah', '$harga', '$total')";
            if (!mysqli_query($conn, $detail_query)) {
                echo "Terjadi kesalahan saat memasukkan detail pesanan: " . mysqli_error($conn);
                exit();
            }
        }

        // Hapus data produk di keranjang setelah checkout
        $delete_query = "DELETE FROM keranjang WHERE id_user = '$id_user'";
        if (!mysqli_query($conn, $delete_query)) {
            echo "Terjadi kesalahan saat menghapus keranjang: " . mysqli_error($conn);
            exit();
        }

        // Redirect ke halaman konfirmasi pesanan
        header('Location: konfirmasi.php?order_id=' . $order_id);
        exit();
    } else {
        echo "Terjadi kesalahan saat memproses pesanan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Checkout</h2>

        <!-- Ringkasan Pesanan -->
        <h4>Ringkasan Pesanan</h4>
        <table class="table table-striped table-bordered">
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
                mysqli_data_seek($result, 0); // Reset pointer hasil query
                while ($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                    <td><?php echo $row['nama_produk']; ?></td>
                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo $row['jumlah']; ?></td>
                    <td>Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="text-right">
            <h4><strong>Total Harga: Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></strong></h4>
        </div>

        <!-- Formulir Alamat Pengiriman dan Metode Pembayaran -->
        <h4>Alamat Pengiriman</h4>
        <form action="checkout.php" method="POST">
            <div class="mb-3">
                <label for="nama_penerima" class="form-label">Nama Penerima</label>
                <input type="text" class="form-control" id="nama_penerima" name="nama_penerima" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="Kartu Kredit">Kartu Kredit</option>
                    <option value="COD">Cash on Delivery</option>
                </select>
            </div>

            <!-- Tombol Kembali ke Keranjang dan Selesaikan Pesanan -->
            <div class="row mt-4">
                <div class="col-6">
                    <a href="keranjang.php" class="btn btn-primary btn-block">Kembali ke Keranjang</a>
                </div>
                <div class="col-6 text-end">
                    <button type="submit" class="btn btn-success btn-block">Selesaikan Pesanan</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
