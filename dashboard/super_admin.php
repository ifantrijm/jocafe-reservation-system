<?php
session_start();
require '../config/koneksi.php';

// ==========================================
// 1. SATPAM SUPER ADMIN
// ==========================================
// Catatan: Pastikan Pak Cik sudah menambahkan 'superadmin' di ENUM role_staff pada database
if (!isset($_SESSION['role_staff']) || $_SESSION['role_staff'] !== 'superadmin') {
    header("Location: ../auth/login.php");
    exit;
}

$error = "";
$success = "";

// ==========================================
// 2. LOGIKA DAFTAR STAFF BARU
// ==========================================
if (isset($_POST['tambah_staff'])) {
    $username   = trim($_POST['username']);
    $password   = trim($_POST['password']);
    $role       = trim($_POST['role_staff']);

    if (empty($username) || empty($password) || empty($role)) {
        $error = "Semua field wajib diisi!";
    } else {
        $username_clean = mysqli_real_escape_string($conn, $username);
        
        // Cek apakah username sudah dipakai
        $cek = mysqli_query($conn, "SELECT * FROM staff WHERE username='$username_clean'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Username sudah terdaftar!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert ke tabel staff
            $query = "INSERT INTO staff (username, password, role_staff) VALUES ('$username_clean', '$hashed_password', '$role')";
            if (mysqli_query($conn, $query)) {
                $id_staff_baru = mysqli_insert_id($conn);

                // Otomatis masukkan ke tabel anak sesuai rolenya
                if ($role == 'admin') {
                    mysqli_query($conn, "INSERT INTO admin (id_staff) VALUES ('$id_staff_baru')");
                } elseif ($role == 'manager') {
                    mysqli_query($conn, "INSERT INTO manager (id_staff) VALUES ('$id_staff_baru')");
                }
                $success = "Staff baru berhasil didaftarkan!";
            }
        }
    }
}

// ==========================================
// 3. LOGIKA RESET PASSWORD (Set default: 123456)
// ==========================================
if (isset($_GET['reset'])) {
    $id_reset = $_GET['reset'];
    $password_default = password_hash("123456", PASSWORD_DEFAULT);

    $query_reset = mysqli_query($conn, "UPDATE staff SET password='$password_default' WHERE id_staff='$id_reset'");
    if ($query_reset) {
        $success = "Password berhasil direset menjadi: 123456";
    } else {
        $error = "Gagal mereset password.";
    }
}

// ==========================================
// 4. LOGIKA HAPUS STAFF
// ==========================================
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];

    // Cek role terlebih dahulu sebelum dihapus untuk membersihkan tabel anak
    $cek_role = mysqli_query($conn, "SELECT role_staff FROM staff WHERE id_staff='$id_hapus'");
    $data_role = mysqli_fetch_assoc($cek_role);

    if ($data_role) {
        $role = $data_role['role_staff'];

        // Hapus di tabel anak dulu agar tidak melanggar Foreign Key Constraints
        if ($role == 'admin') {
            mysqli_query($conn, "DELETE FROM admin WHERE id_staff='$id_hapus'");
        } elseif ($role == 'manager') {
            mysqli_query($conn, "DELETE FROM manager WHERE id_staff='$id_hapus'");
        }

        // Baru hapus di tabel induk (staff)
        $hapus_induk = mysqli_query($conn, "DELETE FROM staff WHERE id_staff='$id_hapus'");
        if ($hapus_induk) {
            $success = "Data staff berhasil dihapus dari sistem.";
        } else {
            $error = "Gagal menghapus staff.";
        }
    }
}

// Ambil semua data staff untuk ditampilkan di tabel (Kecuali akun superadmin itu sendiri)
$tampil_staff = mysqli_query($conn, "SELECT * FROM staff WHERE role_staff != 'superadmin' ORDER BY id_staff DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Staff | Kontrol Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #13171c; color: white; font-family: 'sans-serif'; padding-top: 40px; }
        .card-custom { background-color: #1c2128; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 25px; }
        .table-custom { color: white; background-color: #1c2128; }
        .table-custom th { background-color: #242b35; color: #f89d13; border-bottom: 2px solid rgba(255,255,255,0.1); }
        .table-custom td { border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }
        .btn-gold { background-color: #f89d13; color: white; font-weight: bold; border: none; }
        .btn-gold:hover { background-color: #e08c0f; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-shield text-warning me-2"></i> Otoritas <span style="color: #f89d13;">Super Admin</span></h2>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Log Out <i class="fas fa-sign-out-alt"></i></a>
    </div>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger bg-transparent text-danger border-danger"><?= $error; ?></div>
    <?php endif; ?>
    <?php if(!empty($success)): ?>
        <div class="alert alert-success bg-transparent text-success border-success"><?= $success; ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-custom">
                <h5 class="mb-3 text-warning fw-bold">Tambah Akun Staff</h5>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label small text-muted">Username</label>
                        <input type="text" name="username" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Password Awal</label>
                        <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small text-muted">Akses Peran (Role)</label>
                        <select name="role_staff" class="form-select bg-dark text-white border-secondary" required>
                            <option value="admin">Admin (Operational)</option>
                            <option value="manager">Manager (Statistics)</option>
                        </select>
                    </div>
                    <button type="submit" name="tambah_staff" class="btn btn-gold w-100">Daftarkan Staff</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-custom">
                <h5 class="mb-3 text-warning fw-bold">Daftar Manajemen Staff Active</h5>
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Hak Akses</th>
                                <th class="text-center">Aksi Otoritas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($tampil_staff)): ?>
                            <tr>
                                <td><?= $row['id_staff']; ?></td>
                                <td class="fw-bold"><?= $row['username']; ?></td>
                                <td>
                                    <span class="badge <?= $row['role_staff'] == 'admin' ? 'bg-primary' : 'bg-success'; ?>">
                                        <?= strtoupper($row['role_staff']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="kelola_staff.php?reset=<?= $row['id_staff']; ?>" 
                                       class="btn btn-sm btn-outline-warning me-1" 
                                       onclick="return confirm('Reset password akun ini menjadi \'123456\'?')">
                                        <i class="fas fa-undo"></i> Reset Pwd
                                    </a>
                                    <a href="kelola_staff.php?hapus=<?= $row['id_staff']; ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Hapus permanen staff ini beserta relasi datanya?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>