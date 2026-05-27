<?php
include "../config/koneksi.php";

// Logika Approve Reservasi Room
if (isset($_GET['status']) && isset($_GET['id'])) {
    $status = $_GET['status']; // 'Confirmed'
    $id = $_GET['id'];
    mysqli_query($conn, "UPDATE reservasi_room SET status_pesanan = '$status' WHERE id_reservasi_room = '$id'");
    echo "<script>window.location='admin.php?page=room';</script>";
}
?>

<div class="container-fluid mt-4">
    <h3 class="fw-bold mb-4 text-info">Manajemen Reservasi Room</h3>
    <div class="card bg-dark border-secondary p-4">
        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>No. Meja</th>
                    <th>Tanggal</th>
                    <th>Bukti</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = mysqli_query($conn, "SELECT reservasi_room.*, pelanggan.nama 
                                          FROM reservasi_room 
                                          JOIN pelanggan ON reservasi_room.id_pelanggan = pelanggan.id_pelanggan");
                while ($r = mysqli_fetch_assoc($q)) {
                ?>
                <tr>
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
</div>