<?php
session_start();
require '../config/koneksi.php';

if (isset($_POST['register'])) {
    $username   = trim($_POST['username']);
    $password   = trim($_POST['password']);
    $konfirmasi = trim($_POST['konfirmasi']);
    $role       = trim($_POST['role_staff']);

    // --- VALIDASI ---
    if (empty($username) || empty($password) || empty($konfirmasi) || empty($role)) {
        $error = "Semua field wajib diisi!";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $username_clean = mysqli_real_escape_string($conn, $username);
        $cek = mysqli_query($conn, "SELECT * FROM staff WHERE username='$username_clean'");

        if (mysqli_num_rows($cek) > 0) {
            $error = "Username sudah terdaftar!";
        } else {
            // 1. Hash Password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // 2. SIMPAN KE TABEL UTAMA (staff)
            // status_akun akan otomatis jadi 'Pending' karena settingan database
            $query = "INSERT INTO staff (username, password, role_staff) VALUES ('$username_clean', '$hashed_password', '$role')";
            $simpan = mysqli_query($conn, $query);

            if ($simpan) {
                $id_staff_baru = mysqli_insert_id($conn);

                if ($role == 'admin') {
                    mysqli_query($conn, "INSERT INTO admin (id_staff) VALUES ('$id_staff_baru')");
                } elseif ($role == 'manager') {
                    mysqli_query($conn, "INSERT INTO manager (id_staff) VALUES ('$id_staff_baru')");
                }

                // NOTIFIKASI DIUBAH: Kasih tau kalau akunnya masuk antrean
                $success = "Registrasi berhasil! Akun Anda berstatus <b>Pending</b>. Tunggu persetujuan Manager untuk bisa Login.";
            } else {
                $error = "Gagal menyimpan data: " . mysqli_error($conn);
            }
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
        :root{
            --bg: #0f121a;
            --card: #1a1f2b;
            --accent: #f39c12;
            --border: #2b3445;
            --input: #11151f;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background-color: var(--bg);
            font-family: 'Poppins', sans-serif;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            color:white;
        }

        .auth-card{
            background-color: var(--card);
            width:100%;
            max-width:450px;
            padding:40px;
            border-radius:14px;
            border:1px solid var(--border);
            box-shadow:0 15px 35px rgba(0,0,0,0.5);
        }

        .logo{
            text-align:center;
            margin-bottom:25px;
        }

        .logo h2{
            font-weight:800;
            color:var(--accent);
            margin-bottom:5px;
        }

        .logo p{
            color:#bfc9d4;
            font-size:14px;
        }

        .form-label{
            font-size:13px;
            font-weight:600;
            color:#d6dce5;
        }

        .form-control,
        .form-select{
            background-color: var(--input);
            border:1px solid var(--border);
            color:white;
            padding:12px;
        }

        .form-control:focus,
        .form-select:focus{
            background-color: var(--input);
            border-color: var(--accent);
            color:white;
            box-shadow:none;
        }

        .btn-jo{
            background-color: var(--accent);
            color:black;
            font-weight:700;
            border:none;
            width:100%;
            padding:12px;
            border-radius:8px;
            transition:0.3s;
        }

        .btn-jo:hover{
            background-color:#d68910;
        }

        .small-text{
            font-size:12px;
            color:#aab3be;
            margin-top:-10px;
            margin-bottom:15px;
        }

        .link-login{
            text-align:center;
            margin-top:18px;
        }

        .link-login a{
            color:var(--accent);
            text-decoration:none;
            font-size:13px;
        }

        .link-login a:hover{
            text-decoration:underline;
        }
    </style>
</head>

<body>

<div class="auth-card">

    <div class="logo">
        <h2>JO CAFE.</h2>
        <p>Pendaftaran Akun Sistem Baru</p>
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger py-2 small">
            ⚠️ <?= $error; ?>
        </div>
    <?php endif; ?>

    <?php if(isset($success)): ?>
        <div class="alert alert-warning py-2 small text-dark">
            ⏳ <?= $success; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">USERNAME</label>

            <input
                type="text"
                name="username"
                class="form-control"
                value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                required
            >

            <div class="small-text">
                Minimal 4 karakter
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">PASSWORD</label>

            <input
                type="password"
                name="password"
                class="form-control"
                required
            >

            <div class="small-text">
                Minimal 6 karakter
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">KONFIRMASI PASSWORD</label>

            <input
                type="password"
                name="konfirmasi"
                class="form-control"
                required
            >
        </div>

        <div class="mb-4">
            <label class="form-label">PILIH ROLE / JABATAN</label>

            <select name="role_staff" class="form-select" required>
                <option value="admin">Admin</option>                
            </select>
        </div>

        <button type="submit" name="register" class="btn-jo">
            Buat Akun
        </button>

        <div class="link-login">
            <a href="login.php">
                Sudah punya akun? Login di sini
            </a>
        </div>

    </form>

</div>

</body>
</html>