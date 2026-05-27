<?php
include "../config/koneksi.php";

if(isset($_GET['telp'])) {
    $telp = mysqli_real_escape_string($conn, $_GET['telp']);
    
    // Cari data pelanggan berdasarkan nomor WA
    $query = mysqli_query($conn, "SELECT nama, email FROM pelanggan WHERE telepon = '$telp' LIMIT 1");
    
    if(mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        // Kembalikan data dalam format JSON
        echo json_encode([
            'status' => 'ketemu', 
            'nama' => $data['nama'], 
            'email' => $data['email']
        ]);
    } else {
        echo json_encode(['status' => 'kosong']);
    }
}
?>