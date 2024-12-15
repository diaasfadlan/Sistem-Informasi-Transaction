<?php
session_start();
include('includes/db.php');

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Ambil id produk dari URL
$produk_id = $_GET['id'];

// Ambil data produk dari database
$query = "SELECT * FROM produk WHERE id = $produk_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])) {
    $nama_produk = $_POST['nama_produk'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];

    if ($gambar) {
        $target_dir = "assets/images/";
        $target_file = $target_dir . basename($gambar);
        move_uploaded_file($gambar_tmp, $target_file);
    } else {
        $gambar = $row['gambar'];
    }

    $query = "UPDATE produk SET nama_produk='$nama_produk', deskripsi='$deskripsi', harga=$harga, stok=$stok, gambar='$gambar' WHERE id=$produk_id";
    
    if (mysqli_query($conn, $query)) {
        header('Location: admin_dashboard.php');
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Produk</h2>
        <form action="edit_produk.php?id=<?php echo $produk_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama_produk" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo $row['nama_produk']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" required><?php echo $row['deskripsi']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" value="<?php echo $row['harga']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $row['stok']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar Produk</label>
                <input type="file" class="form-control" id="gambar" name="gambar">
                <img src="assets/images/<?php echo $row['gambar']; ?>" alt="Gambar Produk" width="100">
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
