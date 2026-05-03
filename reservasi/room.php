<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Data Reservasi | Jo Cafe</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #13171c; 
            --bg-card: #1c2128; 
            --text-main: #ffffff;
            --text-muted: #a0aab5;
            --accent-gold: #f89d13; 
            --accent-gold-hover: #e08c0f;
            --border-dark: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            padding-top: 80px;
        }

        .navbar-custom {
            background-color: rgba(19, 23, 28, 0.95);
            border-bottom: 1px solid var(--border-dark);
        }
        .navbar-brand { font-weight: 800; color: var(--accent-gold) !important; }

        .form-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }

        .form-label { color: var(--text-muted); font-size: 0.9rem; font-weight: 600; }
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 8px;
        }
        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: var(--accent-gold);
            color: white;
            box-shadow: 0 0 0 3px rgba(248, 157, 19, 0.2);
        }
        .form-control[readonly] {
            background-color: rgba(0, 0, 0, 0.2);
            color: var(--accent-gold);
            font-weight: bold;
        }

        .btn-gold {
            background-color: var(--accent-gold);
            color: white;
            font-weight: 700;
            padding: 14px;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-gold:hover {
            background-color: var(--accent-gold-hover);
            transform: translateY(-2px);
        }

        .section-title {
            font-weight: 800;
            border-bottom: 2px solid var(--border-dark);
            padding-bottom: 10px;
            margin-bottom: 25px;
            color: var(--accent-gold);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand fs-4" href="#"><i class="fas fa-receipt me-2 text-white"></i>JO <span class="text-white">CAFE.</span></a>
            <a href="denah.html" class="btn btn-outline-light btn-sm rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i>Kembali ke Denah</a>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Formulir Reservasi</h2>
                    <p class="text-muted">Silakan lengkapi data di bawah ini untuk mengonfirmasi pesanan Anda.</p>
                </div>

                <div class="form-card">
                    <form id="formReservasi">
                        
                        <div class="mb-4 p-3 rounded" style="background: rgba(248, 157, 19, 0.1); border: 1px solid var(--accent-gold);">
                            <label class="form-label text-warning mb-1"><i class="fas fa-map-marker-alt me-2"></i>Area / Meja Terpilih</label>
                            <input type="text" class="form-control border-0 bg-transparent fs-5 p-0" value="VIP Room 1 (Kapasitas 10 Orang)" readonly>
                            <input type="hidden" name="id_room" value="1"> 
                        </div>

                        <h5 class="section-title">1. Data Pemesan</h5>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama sesuai identitas" required>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Email Aktif</label>
                                <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. WhatsApp</label>
                                <input type="number" name="telepon" class="form-control" placeholder="0812xxxxxx" required>
                            </div>
                        </div>

                        <h5 class="section-title">2. Detail Acara & Waktu</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Reservasi</label>
                                <select name="jenis" id="jenis_reservasi" class="form-select" required>
                                    <option value="" disabled selected>Pilih...</option>
                                    <option value="room">Nongkrong / Makan Biasa</option>
                                    <option value="event">Acara Spesial (Event/Prewed)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kedatangan</label>
                                <input type="date" name="tanggal_reservasi" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3" id="input_nama_event" style="display: none;">
                            <label class="form-label">Nama Acara <span class="text-danger">*</span></label>
                            <input type="text" name="nama_event" id="field_event" class="form-control" placeholder="Cth: Ulang Tahun, Rapat Kantor, Prewedding">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Catatan Tambahan (Opsional)</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Ada permintaan khusus? Tulis di sini..."></textarea>
                        </div>

                        <h5 class="section-title">3. Konfirmasi Pembayaran</h5>
                        <div class="mb-4">
                            <p class="small text-muted mb-2">Silakan transfer DP (Uang Muka) ke rekening <strong>BCA 123456789 a.n Jo Cafe</strong>. Upload bukti transfer di bawah ini.</p>
                            <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                        </div>

                        <button type="submit" class="btn btn-gold w-100 fs-5 mt-2">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Form Reservasi
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Logika memunculkan input "Nama Event" jika pilih event
        document.getElementById('jenis_reservasi').addEventListener('change', function() {
            var eventDiv = document.getElementById('input_nama_event');
            var eventField = document.getElementById('field_event');
            
            if (this.value === 'event') {
                eventDiv.style.display = 'block';
                eventField.setAttribute('required', 'required');
            } else {
                eventDiv.style.display = 'none';
                eventField.removeAttribute('required');
                eventField.value = ''; 
            }
        });

        // Dummy Submit untuk MVP (Biar tidak reload saat dipresentasikan)
        document.getElementById('formReservasi').addEventListener('submit', function(e) {
            e.preventDefault(); 
            alert('MVP Mode: Form Reservasi berhasil dikirim! Nanti data ini akan masuk ke tabel pelanggan, detail_reservasi, dan form_reservasi.');
            // Mengarahkan pura-pura kembali ke home
            window.location.href = '#sukses'; 
        });
    </script>
</body>
</html>