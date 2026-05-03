<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Event | Jo Cafe</title>
    
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
            padding-bottom: 50px;
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
            padding: 12px;
        }
        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: var(--accent-gold);
            color: white;
            box-shadow: 0 0 0 3px rgba(248, 157, 19, 0.2);
        }

        .btn-gold {
            background-color: var(--accent-gold);
            color: white;
            font-weight: 700;
            padding: 14px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            transition: 0.3s;
        }
        .btn-gold:hover {
            background-color: var(--accent-gold-hover);
            transform: translateY(-2px);
        }

        .section-title {
            font-weight: 800;
            border-left: 4px solid var(--accent-gold);
            padding-left: 15px;
            margin-bottom: 25px;
            color: var(--text-main);
        }

        .info-box {
            background: rgba(248, 157, 19, 0.05);
            border: 1px dashed var(--accent-gold);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand fs-4" href="#"><i class="fas fa-calendar-star me-2 text-white"></i>JO <span class="text-white">EVENT.</span></a>
            <a href="pilih_reservasi.html" class="btn btn-outline-light btn-sm rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="text-center mb-5">
                    <h2 class="fw-bold">Reservasi Event Khusus</h2>
                    <p class="text-muted">Rencanakan momen spesialmu (Ulang tahun, Meeting, Wedding) di Jo Cafe.</p>
                </div>

                <div class="form-card">
                    <form id="formEventMVP">
                        
                        <div class="info-box">
                            <p class="small mb-0 text-warning">
                                <i class="fas fa-info-circle me-2"></i> 
                                Untuk reservasi event, tim kami akan menghubungi Anda kembali dalam 1x24 jam untuk detail teknis dan biaya.
                            </p>
                        </div>

                        <h5 class="section-title">Data Pendaftar</h5>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap / Instansi</label>
                            <input type="text" name="nama_pendaftar" class="form-control" placeholder="Masukkan nama penanggung jawab" required>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Email Aktif</label>
                                <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon (WhatsApp)</label>
                                <input type="text" name="no_telp" class="form-control" placeholder="0812xxxxxx" required>
                            </div>
                        </div>

                        <h5 class="section-title">Detail Acara</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Event</label>
                                <select name="jenis_event" class="form-select" required>
                                    <option value="" disabled selected>Pilih jenis acara...</option>
                                    <option value="Birthday">Ulang Tahun</option>
                                    <option value="Meeting">Rapat / Gathering</option>
                                    <option value="Prewedding">Pre-Wedding</option>
                                    <option value="Other">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Pelaksanaan</label>
                                <input type="date" name="tanggal_event" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Jam Pelaksanaan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted border-secondary opacity-25"><i class="fas fa-clock"></i></span>
                                <input type="time" name="jam_event" class="form-control border-start-0" required>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Deskripsi Tambahan / Pesan Khusus</label>
                            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan kebutuhan khusus Anda (Cth: Jumlah tamu, kebutuhan sound system, dll)"></textarea>
                        </div>

                        <button type="submit" class="btn btn-gold w-100 shadow">
                            <i class="fas fa-paper-plane me-2"></i> Ajukan Reservasi Event
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('formEventMVP').addEventListener('submit', function(e) {
            e.preventDefault(); 
            alert('Sukses! Data reservasi event Anda telah masuk ke sistem simulasi. Admin akan mengecek tabel reservasi_event.');
            this.reset();
        });
    </script>
</body>
</html>