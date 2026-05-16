<?php
// Jangan ada session_start() di sini karena ini akan di-include ke manager.php
include_once "../config/koneksi.php";

// ==========================================
// --- LOGIKA TOMBOL SETUJUI (APPROVE) ---
// ==========================================
if (isset($_GET['approve'])) {
    $id_acc = $_GET['approve'];
    mysqli_query($conn, "UPDATE staff SET status_akun = 'Aktif' WHERE id_staff = '$id_acc'");
    echo "<script>alert('Akun berhasil disetujui!'); window.location='manager.php?page=approval';</script>";
    exit;
}

// ==========================================
// --- LOGIKA TOMBOL TOLAK / HAPUS PERMANEN ---
// ==========================================
// Logika ini dipakai buat nolak yang pending, sekaligus menghapus akun yang udah aktif
if (isset($_GET['reject'])) {
    $id_tolak = $_GET['reject'];
    mysqli_query($conn, "DELETE FROM staff WHERE id_staff = '$id_tolak'");
    echo "<script>alert('Akun berhasil dihapus dari sistem!'); window.location='manager.php?page=approval';</script>";
    exit;
}
?>

<style>
    .approval-content { font-family: 'Plus Jakarta Sans', sans-serif; color: white; padding: 20px;}
    .stat-card {
        background-color: #1c2128;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 25px;
    }
    .badge-pending { background-color: #f39c12; color: #000; font-weight: 600; padding: 5px 10px; border-radius: 6px; font-size: 12px; }
    .badge-aktif { background-color: #2ecc71; color: #000; font-weight: 600; padding: 5px 10px; border-radius: 6px; font-size: 12px; }
</style>

<div class="approval-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0">Manajemen <span style="color: #f89b1c;">Akun Staff</span></h2>
            <p class="text-muted small mt-1">Kelola persetujuan dan daftar akun yang aktif di sistem</p>
        </div>
    </div>

    <div class="stat-card">
        <h5 class="mb-4"><i class="fas fa-users-cog me-2 text-warning"></i> Daftar Semua Staff (Admin & Manager)</h5>
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID Staff</th>
                        <th>Username</th>
                        <th>Posisi / Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil SEMUA data staff tanpa filter 'Pending'
                    // Pakai ORDER BY FIELD biar yang 'Pending' selalu muncul di urutan paling atas!
                    $query_staff = mysqli_query($conn, "SELECT * FROM staff ORDER BY FIELD(status_akun, 'Pending', 'Aktif'), id_staff DESC");

                    if (mysqli_num_rows($query_staff) > 0) {
                        while ($row = mysqli_fetch_assoc($query_staff)) {
                    ?>
                        <tr>
                            <td>#<?php echo $row['id_staff']; ?></td>
                            <td class="fw-bold"><?php echo $row['username']; ?></td>
                            <td><span class="text-info fw-bold text-uppercase"><?php echo $row['role_staff']; ?></span></td>
                            
                            <td>
                                <?php if($row['status_akun'] == 'Pending'): ?>
                                    <span class="badge-pending"><i class="fas fa-hourglass-half"></i> Menunggu</span>
                                <?php else: ?>
                                    <span class="badge-aktif"><i class="fas fa-check-circle"></i> Aktif</span>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <div class="d-flex gap-2">
                                    <?php if($row['status_akun'] == 'Pending'): ?>
                                        <a href="manager.php?page=approval&approve=<?php echo $row['id_staff']; ?>" 
                                           class="btn btn-sm btn-success fw-bold" onclick="return confirm('Setujui admin ini untuk masuk ke sistem?')">
                                            <i class="fas fa-check me-1"></i> Setujui
                                        </a>
                                        
                                        <a href="manager.php?page=approval&reject=<?php echo $row['id_staff']; ?>" 
                                           class="btn btn-sm btn-danger fw-bold" onclick="return confirm('Tolak dan hapus data pendaftaran ini?')">
                                            <i class="fas fa-times me-1"></i> Tolak
                                        </a>
                                    <?php else: ?>
                                        <?php if($row['id_staff'] == $_SESSION['id_staff']): ?>
                                            <button class="btn btn-sm btn-secondary fw-bold" disabled>Akun Anda</button>
                                        <?php else: ?>
                                            <a href="manager.php?page=approval&reject=<?php echo $row['id_staff']; ?>" 
                                               class="btn btn-sm btn-outline-danger fw-bold" onclick="return confirm('Yakin ingin MENGHAPUS akun aktif ini secara permanen?')">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data staff di sistem.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>