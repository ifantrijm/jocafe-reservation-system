<?php
session_start();
require '../config/koneksi.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role_staff']; // Isinya 'admin' atau 'manager'

    // Cek apakah username sudah pernah dipakai
    $cek = mysqli_query($conn, "SELECT * FROM staff WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah terdaftar! Silakan gunakan username lain.";
    } else {
        // Enkripsi Password (Hashing) biar aman
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Simpan langsung HANYA ke tabel staff
        $query_staff = "INSERT INTO staff (username, password, role_staff) VALUES ('$username', '$hashed_password', '$role')";
        
        if (mysqli_query($conn, $query_staff)) {
            $success = "Registrasi berhasil! Silakan ke halaman Login.";
        } else {
            $error = "Terjadi kesalahan sistem: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Staff | Jo Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0a0e17; color: white; font-family: 'Poppins', sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { background-color: #111826; padding: 40px; border-radius: 12px; border: 1px solid #1f2937; width: 100%; max-width: 450px; box-shadow: 0 15px 30px rgba(0,0,0,0.5); }
        .form-control, .form-select { background-color: #0d121d; border: 1px solid #1f2937; color: white; }
        .form-control:focus, .form-select:focus { background-color: #0d121d; color: white; border-color: #f89b1c; box-shadow: none; }
        .btn-jo { background-color: #f89b1c; color: white; font-weight: 600; width: 100%; padding: 12px; border: none; border-radius: 6px; }
        .btn-jo:hover { background-color: #e08915; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="text-center mb-4">
            <h2 style="font-weight: 800; color: #f89b1c;">JO CAFE.</h2>
            <p class="text-muted">Pendaftaran Akun Sistem Baru</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger py-2"><?= $error; ?></div>
        <?php endif; ?>
        <?php if(isset($success)): ?>
            <div class="alert alert-success py-2"><?= $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">USERNAME</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">PASSWORD</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="form-label text-muted small fw-bold">PILIH ROLE / JABATAN</label>
                <select name="role_staff" class="form-select" required>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                </select>
            </div>
            <button type="submit" name="register" class="btn-jo mb-3">Buat Akun</button>
            <div class="text-center">
                <a href="login.php" class="text-muted text-decoration-none small">Sudah punya akun? Login di sini</a>
            </div>
        </form>
    </div>
</body>
</html>