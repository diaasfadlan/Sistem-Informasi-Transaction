<?php
session_start();
include('../dasboard_uas/includes/db.php'); // Menghubungkan ke database

// Cek apakah pengguna sudah login, jika belum, alihkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Pengguna harus login untuk mengakses halaman keranjang
    exit();
}

// Mengambil ID pengguna dari session
$id_user = $_SESSION['user_id'];

// Menangani perubahan jumlah produk di keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk = $_POST['id_produk'];
    $jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : 1; // Jumlah produk yang akan diperbarui
    $aksi = isset($_POST['aksi']) ? $_POST['aksi'] : null;

    // Cek apakah produk sudah ada di keranjang
    if ($aksi === 'tambah') {
        // Jika produk sudah ada, update jumlahnya
        $cek_query = "SELECT * FROM keranjang WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
        $cek_result = mysqli_query($conn, $cek_query);
        if (mysqli_num_rows($cek_result) > 0) {
            $update_query = "UPDATE keranjang SET jumlah = jumlah + '$jumlah' WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
            mysqli_query($conn, $update_query);
        } else {
            // Jika produk belum ada, insert produk ke dalam keranjang
            $insert_query = "INSERT INTO keranjang (id_user, id_produk, jumlah) VALUES ('$id_user', '$id_produk', '$jumlah')";
            mysqli_query($conn, $insert_query);
        }

        // Mengurangi stok produk
        $update_stok_query = "UPDATE produk SET stok = stok - '$jumlah' WHERE id = '$id_produk'";
        mysqli_query($conn, $update_stok_query);
    } elseif ($aksi === 'kurangi') {
        // Kurangi jumlah produk dalam keranjang
        $cek_keranjang_query = "SELECT jumlah FROM keranjang WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
        $cek_keranjang_result = mysqli_query($conn, $cek_keranjang_query);
        $keranjang_data = mysqli_fetch_assoc($cek_keranjang_result);
        $jumlah_keranjang = $keranjang_data['jumlah'];

        if ($jumlah_keranjang > 1) {
            // Jika jumlah lebih dari 1, kurangi jumlahnya
            $update_query = "UPDATE keranjang SET jumlah = jumlah - 1 WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
            mysqli_query($conn, $update_query);

            // Mengembalikan stok produk
            $update_stok_query = "UPDATE produk SET stok = stok + 1 WHERE id = '$id_produk'";
            mysqli_query($conn, $update_stok_query);
        } else {
            // Jika jumlahnya 1, hapus produk dari keranjang
            $delete_query = "DELETE FROM keranjang WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
            mysqli_query($conn, $delete_query);

            // Mengembalikan stok produk
            $update_stok_query = "UPDATE produk SET stok = stok + 1 WHERE id = '$id_produk'";
            mysqli_query($conn, $update_stok_query);
        }
    } elseif ($aksi === 'hapus') {
        // Menghapus produk dari keranjang
        $delete_query = "DELETE FROM keranjang WHERE id_user = '$id_user' AND id_produk = '$id_produk'";
        mysqli_query($conn, $delete_query);

        // Mengembalikan stok produk
        $update_stok_query = "UPDATE produk SET stok = stok + 1 WHERE id = '$id_produk'";
        mysqli_query($conn, $update_stok_query);
    }

    // Refresh halaman keranjang
    header('Location: keranjang.php');
    exit();
}

// Mengambil data produk dalam keranjang
$query = "SELECT p.id, p.nama_produk, p.harga, k.jumlah, (p.harga * k.jumlah) AS total 
          FROM keranjang k
          JOIN produk p ON k.id_produk = p.id
          WHERE k.id_user = '$id_user'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Keranjang Belanja Anda</h1>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_belanja = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $total_belanja += $row['total'];
                ?>
                <tr>
                    <td><?php echo $row['nama_produk']; ?></td>
                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td>
                        <form method="POST" action="keranjang.php" class="d-inline">
                            <input type="hidden" name="id_produk" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="aksi" value="kurangi">
                            <button type="submit" class="btn btn-warning btn-sm">-</button>
                        </form>
                        <?php echo $row['jumlah']; ?>
                        <form method="POST" action="keranjang.php" class="d-inline">
                            <input type="hidden" name="id_produk" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="aksi" value="tambah">
                            <input type="number" name="jumlah" value="1" min="1" class="d-none">
                            <button type="submit" class="btn btn-primary btn-sm">+</button>
                        </form>
                    </td>
                    <td>Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                    <td>
                        <form method="POST" action="keranjang.php">
                            <input type="hidden" name="id_produk" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="aksi" value="hapus">
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Total Belanja: Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></h3>
        <a href="index.php" class="btn btn-primary mt-4">Lanjut Belanja</a>
        <a href="checkout.php" class="btn btn-success mt-4">Checkout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
