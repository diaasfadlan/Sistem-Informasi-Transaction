<?php
session_start();
include('includes/db.php');

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Pastikan parameter ID ada
if (isset($_GET['id'])) {
    $produk_id = intval($_GET['id']); // Pastikan ID adalah integer

    // Hapus data yang terkait di tabel detail_pesanan terlebih dahulu
    $query_delete_detail = "DELETE FROM detail_pesanan WHERE id_produk = ?";
    $stmt_delete_detail = mysqli_prepare($conn, $query_delete_detail);

    if ($stmt_delete_detail) {
        mysqli_stmt_bind_param($stmt_delete_detail, "i", $produk_id);

        if (mysqli_stmt_execute($stmt_delete_detail)) {
            // Setelah data terkait dihapus, hapus produk dari tabel produk
            $query_delete_produk = "DELETE FROM produk WHERE id = ?";
            $stmt_delete_produk = mysqli_prepare($conn, $query_delete_produk);

            if ($stmt_delete_produk) {
                mysqli_stmt_bind_param($stmt_delete_produk, "i", $produk_id);

                if (mysqli_stmt_execute($stmt_delete_produk)) {
                    header('Location: admin_dashboard.php');
                    exit();
                } else {
                    echo "Error deleting product: " . mysqli_stmt_error($stmt_delete_produk);
                }

                mysqli_stmt_close($stmt_delete_produk);
            } else {
                echo "Prepare Error: " . mysqli_error($conn);
            }
        } else {
            echo "Error deleting related details: " . mysqli_stmt_error($stmt_delete_detail);
        }

        mysqli_stmt_close($stmt_delete_detail);
    } else {
        echo "Prepare Error: " . mysqli_error($conn);
    }
} else {
    echo "ID produk tidak ditemukan.";
}

// Tutup koneksi database
mysqli_close($conn);
?>
