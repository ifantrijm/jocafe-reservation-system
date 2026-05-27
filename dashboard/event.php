
<?php
include "../config/koneksi.php";

// Logika Approve/Tolak Event
if (isset($_GET['status']) && isset($_GET['id'])) {
    $status = $_GET['status']; // 'confirmed' atau 'cancelled'
    $id = $_GET['id'];
    mysqli_query($conn, "UPDATE reservasi_event SET status_booking = '$status' WHERE id_event_res = '$id'");
    echo "<script>window.location='admin.php?page=event';</script>";
}
?>

<div class="container-fluid mt-4">
    <h3 class="fw-bold mb-4 text-warning">Manajemen Reservasi Event</h3>
    <div class="card bg-dark border-secondary p-4">
        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Event</th>
                    <th>Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = mysqli_query($conn, "SELECT reservasi_event.*, pelanggan.nama 
                                          FROM reservasi_event 
                                          JOIN pelanggan ON reservasi_event.id_pelanggan = pelanggan.id_pelanggan") ;
                while ($r = mysqli_fetch_assoc($q)) {
                ?>
                <tr>
                    <td><?= $r['nama']; ?></td>
                    <td><?= $r['jenis_event']; ?></td>
                    <td><?= $r['tanggal_event'] . ' ' . $r['jam_event']; ?></td>
                    <td>
                        <span class="badge <?= ($r['status_booking'] == 'confirmed') ? 'bg-success' : 'bg-warning'; ?>">
                            <?= $r['status_booking']; ?>
                        </span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>