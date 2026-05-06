<?php
session_start();
// Panggil koneksi database
require '../config/koneksi.php'; 

// Jika user sudah login, tendang balik ke dashboard masing-masing biar nggak bisa akses login lagi
if (isset($_SESSION['role_staff'])) {
    if ($_SESSION['role_staff'] == 'admin') header("Location: ../dashboard/admin.php");
    else header("Location: ../auth/login.php");
    exit;
}

// Jika tombol login diklik
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Ganti $conn jadi $koneksi kalau di file koneksi.php lo pakainya $koneksi
    $password = $_POST['password'];

    // Cari username di tabel staff
    $query = mysqli_query($conn, "SELECT * FROM staff WHERE username='$username'");
    
    // Cek apakah username ada (jumlah baris = 1)
    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        
        // Verifikasi kecocokan password yang diketik dengan hash di database
        if (password_verify($password, $row['password'])) {
            // Set variabel Session
            $_SESSION['id_staff'] = $row['id_staff'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role_staff'] = $row['role_staff'];

            // Otorisasi & Routing berdasarkan Role (Arahkan ke folder masing-masing)
// Otorisasi & Routing berdasarkan Role
            if ($row['role_staff'] == 'admin') {
                header("Location: ../dashboard/admin.php"); // Arahkan ke folder dashboard file admin.php
                exit;
            } else if ($row['role_staff'] == 'manager') {
                header("Location: ../dashboard/manager.php"); // Arahkan ke folder dashboard file manager.php
                exit;
            }
        } else {
            $error = "Password yang Anda masukkan salah!";
        }
    } else {
        $error = "Username tidak ditemukan di sistem!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem | Jo Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0a0e17; color: white; font-family: 'Poppins', sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { background-color: #111826; padding: 40px; border-radius: 12px; border: 1px solid #1f2937; width: 100%; max-width: 400px; box-shadow: 0 15px 30px rgba(0,0,0,0.5); }
        .form-control { background-color: #0d121d; border: 1px solid #1f2937; color: white; padding: 12px; }
        .form-control:focus { background-color: #0d121d; color: white; border-color: #f89b1c; box-shadow: none; }
        .btn-jo { background-color: #f89b1c; color: white; font-weight: 600; width: 100%; padding: 12px; border: none; border-radius: 6px; transition: 0.3s; }
        .btn-jo:hover { background-color: #e08915; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="text-center mb-4">
            <h2 style="font-weight: 800; color: #f89b1c;">JO CAFE.</h2>
            <p class="text-white">Login Management System</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger py-2 small text-center"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label text-white small fw-bold">USERNAME</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="form-label text-white small fw-bold">PASSWORD</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn-jo mb-3">Login ke Sistem</button>
            <div class="text-center">
                <a href="register.php" class="text-white text-decoration-none small">Belum punya akun? Registrasi dulu</a>
            </div>
        </form>
    </div>
</body>
</html>