<?php
include "../config/koneksi.php";

// 1. Kunci zona waktu mutlak ke Jakarta
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

$log_file = __DIR__ . '/wa_terkirim_log.json';
$wa_terkirim = file_exists($log_file) ? json_decode(file_get_contents($log_file), true) : [];
if (!is_array($wa_terkirim)) { $wa_terkirim = []; }

while ($row = mysqli_fetch_assoc($query)) {
    // 2. Set Waktu Berakhir Menggunakan Objek DateTime yang Akurat
    if (empty($row['jam_selesai']) || $row['jam_selesai'] == '00:00:00') {
        $waktu_berakhir = new DateTime($row['tanggal_reservasi'] . ' ' . $row['jam_mulai'], new DateTimeZone('Asia/Jakarta'));
        $waktu_berakhir->modify('+6 hours'); // Tambah 6 jam
    } else {
        $waktu_berakhir = new DateTime($row['tanggal_reservasi'] . ' ' . $row['jam_selesai'], new DateTimeZone('Asia/Jakarta'));
    }

    // 3. JIKA Waktu Sekarang SUDAH LEWAT atau SAMA DENGAN Waktu Berakhir
    if ($sekarang >= $waktu_berakhir) {
        $id_res_ini = $row['id_reservasi_room'];
        
        // Simpan ID ini ke keranjang untuk bikin tombol merah di dasbor
        $overtime_ids[] = $id_res_ini;

        // 4. Eksekusi kirim WA JIKA BELUM PERNAH DIKIRIM (Cek di JSON)
        if (!in_array($id_res_ini, $wa_terkirim)) {
            $no_wa = $row['telepon'];
            if (substr($no_wa, 0, 1) == '0') { $no_wa = '62' . substr($no_wa, 1); }
            
            $nama_pelanggan = $row['nama'];
            $pesan = "Halo Kak *$nama_pelanggan*, \n\nMohon maaf, waktu reservasi meja Anda (Area: *{$row['nama_area']}*) di Jo Cafe telah *habis*. \n\nSilakan konfirmasi ke kasir jika ingin memperpanjang waktu. Terima kasih! 🙏";
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.fonnte.com/send',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array(
                'target' => $no_wa,
                'message' => $pesan
              ),
              CURLOPT_HTTPHEADER => array(
                'Authorization: cNAahbtZhHjtDdG6Tvjk' // <-- MASUKKAN TOKEN FONNTE
              ),
            ));
            curl_exec($curl);
            curl_close($curl);

            // Tulis ulang JSON biar sistem tahu WA sudah dikirim (Anti-Spam)
            $wa_terkirim[] = $id_res_ini;
            file_put_contents($log_file, json_encode($wa_terkirim));
        }
    }
}

// Kirim jawaban status ke home.php
header('Content-Type: application/json');
echo json_encode(['status' => 'sukses', 'data_overtime' => $overtime_ids]);
?>