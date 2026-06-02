<?php
require "../config/koneksi.php";

date_default_timezone_set('Asia/Jakarta');
$sekarang = new DateTime('now', new DateTimeZone('Asia/Jakarta'));

$overtime_ids = []; 

$query = mysqli_query($conn, "
    SELECT r_res.*, p.nama, p.telepon, room.nama_area 
    FROM reservasi_room r_res 
    JOIN pelanggan p ON r_res.id_pelanggan = p.id_pelanggan 
    JOIN room ON r_res.id_room = room.id_room 
    WHERE r_res.status_pesanan != 'Selesai'
");

while ($row = mysqli_fetch_assoc($query)) {
    // Tentukan waktu berakhir
    if (empty($row['jam_selesai']) || $row['jam_selesai'] == '00:00:00') {
        $waktu_berakhir = new DateTime($row['tanggal_reservasi'] . ' ' . $row['jam_mulai'], new DateTimeZone('Asia/Jakarta'));
        $waktu_berakhir->modify('+6 hours');
    } else {
        $waktu_berakhir = new DateTime($row['tanggal_reservasi'] . ' ' . $row['jam_selesai'], new DateTimeZone('Asia/Jakarta'));
    }

    if ($sekarang >= $waktu_berakhir) {
        $id_res_ini = $row['id_reservasi_room'];
        $overtime_ids[] = $id_res_ini;

        // Cek ke DB, apakah sudah pernah dikirim (is_notified_habis == 0 berarti belum)
        $is_notified = (int)$row['is_notified_habis'];

        if ($is_notified === 0) {
            $no_wa = $row['telepon'];
            if (substr($no_wa, 0, 1) == '0') { $no_wa = '62' . substr($no_wa, 1); }
            
            $nama_pelanggan = $row['nama'];
            $pesan = "Halo Kak *$nama_pelanggan*, \n\nMohon maaf, waktu reservasi meja Anda (Area: *{$row['nama_area']}*) di Jo Cafe telah *habis*. \n\nSilakan konfirmasi ke kasir jika ingin memperpanjang waktu. Terima kasih! 🙏";
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.fonnte.com/send',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_POST => true,
              CURLOPT_POSTFIELDS => array(
                'target' => $no_wa,
                'message' => $pesan
              ),
              CURLOPT_HTTPHEADER => array('Authorization: cNAahbtZhHjtDdG6Tvjk'), // Sesuaikan Token
            ));
            curl_exec($curl);
            curl_close($curl);

            // TANDAI DI DATABASE BAHWA SUDAH DIKIRIM (is_notified_habis = 1)
            mysqli_query($conn, "UPDATE reservasi_room SET is_notified_habis = 1 WHERE id_reservasi_room = '$id_res_ini'");
        }
    }
}

header('Content-Type: application/json');
echo json_encode(['status' => 'sukses', 'data_overtime' => $overtime_ids]);
?>