
<?php
require "../config/koneksi.php";

// Logika Approve/Tolak Event
if (isset($_GET['status']) && isset($_GET['id'])) {
    $status = $_GET['status']; // 'confirmed' atau 'cancelled'
    $id = $_GET['id'];
    mysqli_query($conn, "UPDATE reservasi_event SET status_booking = '$status' WHERE id_event_res = '$id'");
    echo "<script>window.location='admin.php?page=event';</script>";
}

// LOGIKA HAPUS MASSAL (BULK DELETE)
if (isset($_POST['hapus_pilihan'])) {
    if (!empty($_POST['id_hapus'])) {
        foreach ($_POST['id_hapus'] as $id) {
            mysqli_query($conn, "DELETE FROM reservasi_event WHERE id_event_res = '$id'");
        }
        echo "<script>alert('Data event terpilih berhasil dihapus!'); window.location='admin.php?page=event';</script>";
    }
}
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-6">

            <h2 class="fw-bold mb-4">Manajemen  <span style="color: #f89d13;">Reservasi Event</span> </h2>
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
<form action="" method="POST">
    <div class="card bg-dark border-secondary p-4">
        <button type="submit" name="hapus_pilihan" class="btn btn-danger mb-3" onclick="return confirm('Yakin ingin menghapus data yang dipilih?')">
            <i class="fas fa-trash"></i> Hapus Terpilih
        </button>

        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th><input type="checkbox" onclick="toggle(this)"></th>
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
                                          JOIN pelanggan ON reservasi_event.id_pelanggan = pelanggan.id_pelanggan");
                while ($r = mysqli_fetch_assoc($q)) {
                ?>
                <tr>
                    <td><input type="checkbox" name="id_hapus[]" value="<?= $r['id_event_res']; ?>"></td>
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
</form>

<script>
    function toggle(source) {
        checkboxes = document.getElementsByName('id_hapus[]');
        for(var i=0, n=checkboxes.length; i<n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>
    </div>
</div>