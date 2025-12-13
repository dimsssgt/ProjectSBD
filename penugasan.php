<?php
require_once 'koneksi.php';

// Proses Tambah
if(isset($_POST['tambah'])) {
    $id_karyawan = $_POST['id_karyawan'];
    $id_proyek = $_POST['id_proyek'];
    $peran = $_POST['peran'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    
    $query = "INSERT INTO penugasan (id_karyawan, id_proyek, peran, tanggal_mulai, tanggal_selesai) 
              VALUES ('$id_karyawan', '$id_proyek', '$peran', '$tanggal_mulai', '$tanggal_selesai')";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil ditambahkan!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Proses Edit
if(isset($_POST['edit'])) {
    $id_penugasan = $_POST['id_penugasan'];
    $id_karyawan = $_POST['id_karyawan'];
    $id_proyek = $_POST['id_proyek'];
    $peran = $_POST['peran'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    
    $query = "UPDATE penugasan SET id_karyawan='$id_karyawan', id_proyek='$id_proyek', peran='$peran',
              tanggal_mulai='$tanggal_mulai', tanggal_selesai='$tanggal_selesai' 
              WHERE id_penugasan=$id_penugasan";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil diupdate!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Proses Hapus
if(isset($_GET['hapus'])) {
    $id_penugasan = $_GET['hapus'];
    $query = "DELETE FROM penugasan WHERE id_penugasan=$id_penugasan";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil dihapus!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Ambil data penugasan dengan join
$query = "SELECT pg.*, k.nama as nama_karyawan, pr.nama_proyek 
          FROM penugasan pg 
          LEFT JOIN karyawan k ON pg.id_karyawan = k.id_karyawan 
          LEFT JOIN proyek pr ON pg.id_proyek = pr.id_proyek 
          ORDER BY pg.id_penugasan DESC";
$result = mysqli_query($koneksi, $query);

// Ambil data karyawan untuk dropdown
$query_karyawan = "SELECT * FROM karyawan";
$result_karyawan = mysqli_query($koneksi, $query_karyawan);

// Ambil data proyek untuk dropdown
$query_proyek = "SELECT * FROM proyek";
$result_proyek = mysqli_query($koneksi, $query_proyek);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penugasan - Sistem Penilaian Pekerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --sidebar-width: 260px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
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
        
        .table-container {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        
        .btn-action {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h4>Sistem Penilaian Pekerja</h4>
            <small class="text-muted">Berbasis Proyek</small>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="dashboard.php">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="departemen.php">
                    <i class="bi bi-building"></i>
                    Departemen
                </a>
            </li>
            <li>
                <a href="karyawan.php">
                    <i class="bi bi-people"></i>
                    Karyawan
                </a>
            </li>
            <li>
                <a href="proyek.php">
                    <i class="bi bi-briefcase"></i>
                    Proyek
                </a>
            </li>
            <li>
                <a href="penugasan.php" class="active">
                    <i class="bi bi-clipboard-check"></i>
                    Penugasan
                </a>
            </li>
            <li>
                <a href="penilaian.php">
                    <i class="bi bi-star"></i>
                    Penilaian Kinerja
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Daftar Penugasan</h2>
                <p class="text-muted">Kelola penugasan karyawan pada proyek</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-2"></i>Tambah Penugasan
            </button>
        </div>

        <?php if(isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Karyawan</th>
                            <th>Proyek</th>
                            <th>Peran</th>
                            <th>Periode</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id_penugasan']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle text-success me-2"></i>
                                    <?php echo $row['nama_karyawan']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-briefcase text-primary me-2"></i>
                                    <?php echo $row['nama_proyek']; ?>
                                </div>
                            </td>
                            <td><span class="badge bg-primary"><?php echo $row['peran']; ?></span></td>
                            <td>
                                <div>
                                    <i class="bi bi-calendar me-1"></i>
                                    <small>
                                        <?php echo date('d/m/Y', strtotime($row['tanggal_mulai'])); ?><br>
                                        <span class="text-muted">s/d <?php echo date('d/m/Y', strtotime($row['tanggal_selesai'])); ?></span>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-action" onclick="editData(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="?hapus=<?php echo $row['id_penugasan']; ?>" 
                                   class="btn btn-sm btn-danger btn-action" 
                                   onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Penugasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Karyawan</label>
                                <select class="form-select" name="id_karyawan" required>
                                    <?php 
                                    mysqli_data_seek($result_karyawan, 0);
                                    while($karyawan = mysqli_fetch_assoc($result_karyawan)): 
                                    ?>
                                    <option value="<?php echo $karyawan['id_karyawan']; ?>">
                                        <?php echo $karyawan['nama']; ?> - <?php echo $karyawan['jabatan']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Proyek</label>
                                <select class="form-select" name="id_proyek" required>
                                    <?php 
                                    mysqli_data_seek($result_proyek, 0);
                                    while($proyek = mysqli_fetch_assoc($result_proyek)): 
                                    ?>
                                    <option value="<?php echo $proyek['id_proyek']; ?>">
                                        <?php echo $proyek['nama_proyek']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Peran</label>
                            <input type="text" class="form-control" name="peran" 
                                   placeholder="Contoh: Frontend Developer, Project Lead, Designer" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="tanggal_mulai" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" name="tanggal_selesai" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Penugasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id_penugasan" id="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Karyawan</label>
                                <select class="form-select" name="id_karyawan" id="edit_karyawan" required>
                                    <?php 
                                    mysqli_data_seek($result_karyawan, 0);
                                    while($karyawan = mysqli_fetch_assoc($result_karyawan)): 
                                    ?>
                                    <option value="<?php echo $karyawan['id_karyawan']; ?>">
                                        <?php echo $karyawan['nama']; ?> - <?php echo $karyawan['jabatan']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Proyek</label>
                                <select class="form-select" name="id_proyek" id="edit_proyek" required>
                                    <?php 
                                    mysqli_data_seek($result_proyek, 0);
                                    while($proyek = mysqli_fetch_assoc($result_proyek)): 
                                    ?>
                                    <option value="<?php echo $proyek['id_proyek']; ?>">
                                        <?php echo $proyek['nama_proyek']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Peran</label>
                            <input type="text" class="form-control" name="peran" id="edit_peran" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="tanggal_mulai" id="edit_mulai" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" name="tanggal_selesai" id="edit_selesai" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editData(data) {
            document.getElementById('edit_id').value = data.id_penugasan;
            document.getElementById('edit_karyawan').value = data.id_karyawan;
            document.getElementById('edit_proyek').value = data.id_proyek;
            document.getElementById('edit_peran').value = data.peran;
            document.getElementById('edit_mulai').value = data.tanggal_mulai;
            document.getElementById('edit_selesai').value = data.tanggal_selesai;
            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        }
    </script>
</body>
</html>
