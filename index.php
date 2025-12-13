<?php
require_once 'koneksi.php';

// Ambil statistik
$query_departemen = "SELECT COUNT(*) as total FROM departemen";
$result_departemen = mysqli_query($koneksi, $query_departemen);
$total_departemen = mysqli_fetch_assoc($result_departemen)['total'];

$query_karyawan = "SELECT COUNT(*) as total FROM karyawan";
$result_karyawan = mysqli_query($koneksi, $query_karyawan);
$total_karyawan = mysqli_fetch_assoc($result_karyawan)['total'];

$query_proyek = "SELECT COUNT(*) as total FROM proyek";
$result_proyek = mysqli_query($koneksi, $query_proyek);
$total_proyek = mysqli_fetch_assoc($result_proyek)['total'];

$query_penilaian = "SELECT COUNT(*) as total FROM nilai_kinerja_proyek";
$result_penilaian = mysqli_query($koneksi, $query_penilaian);
$total_penilaian = mysqli_fetch_assoc($result_penilaian)['total'];

// Ambil proyek terbaru
$query_proyek_terbaru = "SELECT p.*, m.nama as nama_manager 
                         FROM proyek p 
                         LEFT JOIN manager m ON p.id_manager = m.id_manager 
                         ORDER BY p.id_proyek DESC LIMIT 4";
$result_proyek_terbaru = mysqli_query($koneksi, $query_proyek_terbaru);

// Ambil penilaian terbaru
$query_penilaian_terbaru = "SELECT n.*, 
                            k.nama as nama_karyawan, 
                            pr.nama_proyek,
                            pg.peran
                            FROM nilai_kinerja_proyek n
                            LEFT JOIN penugasan pg ON n.id_penugasan = pg.id_penugasan
                            LEFT JOIN karyawan k ON pg.id_karyawan = k.id_karyawan
                            LEFT JOIN proyek pr ON pg.id_proyek = pr.id_proyek
                            ORDER BY n.tanggal_penilaian DESC LIMIT 5";
$result_penilaian_terbaru = mysqli_query($koneksi, $query_penilaian_terbaru);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penilaian Pekerja Berbasis Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --sidebar-width: 260px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: linear-gradient(180deg, var(--green-dark), var(--green-mid), #103823);
        }
        
        /* .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid #e5e7eb;
            padding: 1.5rem;
            overflow-y: auto;
        }
        
        .sidebar-brand {
            margin-bottom: 2rem;
        }
        
        .sidebar-brand h4 {
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #374151;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #eff6ff;
            color: var(--primary-color);
        }
        
        .sidebar-menu i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }
        
        .stat-card {
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
            background: white;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .card-custom {
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            background: white;
        }
        
        .table-container {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        } */
    </style>
</head>
<body>
    <!-- Main Content -->
    <div class="main-content">
        <div class="mb-4">
            <h2>Evalify</h2>
            <p class="text-muted">Hey, Hello!</p>
        </div>

        <!-- Statistik Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Departemen</p>
                            <h3 class="mb-0"><?php echo $total_departemen; ?></h3>
                        </div>
                        <div class="stat-icon" style="background-color: #2563eb;">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Karyawan</p>
                            <h3 class="mb-0"><?php echo $total_karyawan; ?></h3>
                        </div>
                        <div class="stat-icon" style="background-color: #10b981;">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Proyek Aktif</p>
                            <h3 class="mb-0"><?php echo $total_proyek; ?></h3>
                        </div>
                        <div class="stat-icon" style="background-color: #8b5cf6;">
                            <i class="bi bi-briefcase"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Penilaian Selesai</p>
                            <h3 class="mb-0"><?php echo $total_penilaian; ?></h3>
                        </div>
                        <div class="stat-icon" style="background-color: #f59e0b;">
                            <i class="bi bi-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Proyek Terbaru -->
            <div class="col-md-6">
                <div class="card-custom p-4">
                    <h5 class="mb-3">Proyek Terbaru</h5>
                    <?php while($proyek = mysqli_fetch_assoc($result_proyek_terbaru)): ?>
                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                        <div class="bg-light p-2 rounded me-3">
                            <i class="bi bi-briefcase text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?php echo $proyek['nama_proyek']; ?></h6>
                            <p class="text-muted mb-1 small"><?php echo $proyek['deskripsi']; ?></p>
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i>
                                <?php echo date('d/m/Y', strtotime($proyek['tanggal_mulai'])); ?> - 
                                <?php echo date('d/m/Y', strtotime($proyek['tanggal_selesai'])); ?>
                            </small>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Penilaian Terbaru -->
            <div class="col-md-6">
                <div class="card-custom p-4">
                    <h5 class="mb-3">Penilaian Terbaru</h5>
                    <?php while($nilai = mysqli_fetch_assoc($result_penilaian_terbaru)): ?>
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-0"><?php echo $nilai['nama_karyawan']; ?></h6>
                                <small class="text-muted"><?php echo $nilai['nama_proyek']; ?> - <?php echo $nilai['peran']; ?></small>
                            </div>
                            <span class="badge bg-warning rounded-pill"><?php echo number_format($nilai['total_nilai'], 2); ?></span>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted">Kualitas: <strong><?php echo $nilai['kualitas']; ?></strong></small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Ketepatan: <strong><?php echo $nilai['ketepatan_waktu']; ?></strong></small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Keaktifan: <strong><?php echo $nilai['keaktifan']; ?></strong></small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Kontribusi: <strong><?php echo $nilai['kontribusi']; ?></strong></small>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
