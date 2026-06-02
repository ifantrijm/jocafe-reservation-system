<?php
require "../config/koneksi.php";

// Logika Approve Reservasi Room
if (isset($_GET['status']) && isset($_GET['id'])) {
    $status = $_GET['status']; // 'Confirmed'
    $id = $_GET['id'];
    mysqli_query($conn, "UPDATE reservasi_room SET status_pesanan = '$status' WHERE id_reservasi_room = '$id'");
    echo "<script>window.location='admin.php?page=room';</script>";
}

// LOGIKA HAPUS MASSAL (BULK DELETE)
if (isset($_POST['hapus_pilihan'])) {
    if (!empty($_POST['id_hapus'])) {
        foreach ($_POST['id_hapus'] as $id) {
            // Ambil nama file gambar untuk dihapus dari folder
            $cek = mysqli_query($conn, "SELECT bukti_pembayaran FROM reservasi_room WHERE id_reservasi_room = '$id'");
            $data = mysqli_fetch_assoc($cek);
            if (!empty($data['bukti_pembayaran']) && file_exists("../assets/img/bukti/" . $data['bukti_pembayaran'])) {
                unlink("../assets/img/bukti/" . $data['bukti_pembayaran']);
            }
            // Hapus dari database
            mysqli_query($conn, "DELETE FROM reservasi_room WHERE id_reservasi_room = '$id'");
        }
        echo "<script>alert('Data berhasil dihapus!'); window.location='admin.php?page=room';</script>";
    }
}
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-6">
            <h2 class="fw-bold mb-4">Manajemen  <span style="color: #f89d13;">Reservasi Room</span> </h2>
        </div>
        <div class="col-6 d-flex justify-content-end">
            <div class="mb-3">
                <a href="admin.php?page=home" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
    <div class="card bg-dark border-secondary p-4">
<form action="" method="POST"> <div class="card bg-dark border-secondary p-4">
        <button type="submit" name="hapus_pilihan" class="btn btn-danger mb-3" onclick="return confirm('Yakin ingin menghapus data yang dipilih?')">
            <i class="fas fa-trash"></i> Hapus Terpilih
        </button>

        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th><input type="checkbox" onclick="toggle(this)"></th> <th>Pelanggan</th>
                    <th>No. Meja</th>
                    <th>Tanggal</th>
                    <th>Bukti</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = mysqli_query($conn, "SELECT reservasi_room.*, pelanggan.nama FROM reservasi_room JOIN pelanggan ON reservasi_room.id_pelanggan = pelanggan.id_pelanggan");
                while ($r = mysqli_fetch_assoc($q)) {
                ?>
                <tr>
                    <td><input type="checkbox" name="id_hapus[]" value="<?= $r['id_reservasi_room']; ?>"></td>
                    <td><?= $r['nama']; ?></td>
                    <td><?= $r['id_room']; ?></td>
                    <td><?= $r['tanggal_reservasi']; ?></td>
                    <td>
                        <?php if($r['bukti_pembayaran']): ?>
                            <a href="../assets/img/bukti/<?= $r['bukti_pembayaran']; ?>" target="_blank" class="btn btn-sm btn-outline-info">Lihat</a>
                        <?php else: ?>
                            <span class="text-muted">Belum ada</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge <?= ($r['status_pesanan'] == 'Confirmed') ? 'bg-primary' : 'bg-warning'; ?>">
                            <?= $r['status_pesanan']; ?>
                        </span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</form>

<script>
    // Script sederhana untuk centang semua
    function toggle(source) {
        checkboxes = document.getElementsByName('id_hapus[]');
        for(var i=0, n=checkboxes.length; i<n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>
    </div>
</div>