

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                url('walp.png') no-repeat center center fixed;
            background-size: cover;
            color: white;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 1200px;
        }

        .left-section,
        .right-section {
            padding: 20px;
            flex: 1;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            /* Set the max width for the form */
            width: 100%;
            padding: 30px;
            text-align: center;
        }

        .card h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .icon {
            font-size: 4rem;
            color: #007bff;
            margin-bottom: 20px;
        }

        .btn {
            padding: 12px 20px;
            font-size: 1.2rem;
            border-radius: 50px;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .welcome-text {
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
            text-align: right;
            margin-top: 100px;
        }

        footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: #ccc;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Left Section for Form -->
        <div class="left-section">
            <div class="card">
                <i class="icon fas fa-store"></i>
                <h1>Selamat Datang</h1>
                <p style="color:black">Pilih login sebagai User atau Admin untuk melanjutkan.</p>

                <!-- Tombol User dan Admin -->
                <div class="d-grid gap-3 mt-3">
                    <!-- Langsung masuk ke User -->
                    <a href="ecommerce/index.php" class="btn btn-primary">Masuk sebagai User</a>

                    <!-- Form Login untuk Admin -->
                    <form action="dasboard_uas/admin_dashboard.php" method="POST">
                        <input type="hidden" name="role" value="admin">
                        <div class="form-group text-start">
                            <label style="color:black" for="username" class="form-label">Username Admin:</label>
                            <input type="text" id="admin" name="username" class="form-control" required>
                        </div>
                        <div class="form-group text-start">
                            <label style="color:black" for="password" class="form-label">Password Admin:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 mt-3">Login sebagai Admin</button>
                    </form>

                </div>
            </div>
        </div>

        <!-- Right Section for Welcome Text -->
        <div class="right-section">
            <div class="welcome-text">
            </div>
        </div>
    </div>

    <footer>&copy; <?php echo date('Y'); ?> Toko Online. Semua Hak Dilindungi.</footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>