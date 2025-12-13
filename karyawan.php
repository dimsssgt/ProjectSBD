<?php
require_once 'koneksi.php';

// Proses Tambah
if(isset($_POST['tambah'])) {
    $id_departemen = $_POST['id_departemen'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    
    $query = "INSERT INTO karyawan (id_departemen, nama, alamat, jabatan, email) 
              VALUES ('$id_departemen', '$nama', '$alamat', '$jabatan', '$email')";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil ditambahkan!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Proses Edit
if(isset($_POST['edit'])) {
    $id_karyawan = $_POST['id_karyawan'];
    $id_departemen = $_POST['id_departemen'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    
    $query = "UPDATE karyawan SET id_departemen='$id_departemen', nama='$nama', alamat='$alamat', 
              jabatan='$jabatan', email='$email' WHERE id_karyawan=$id_karyawan";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil diupdate!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Proses Hapus
if(isset($_GET['hapus'])) {
    $id_karyawan = $_GET['hapus'];
    $query = "DELETE FROM karyawan WHERE id_karyawan=$id_karyawan";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil dihapus!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Ambil data karyawan dengan join departemen
$query = "SELECT k.*, d.nama_departemen 
          FROM karyawan k 
          LEFT JOIN departemen d ON k.id_departemen = d.id_departemen 
          ORDER BY k.id_karyawan DESC";
$result = mysqli_query($koneksi, $query);

// Ambil data departemen untuk dropdown
$query_dept = "SELECT * FROM departemen";
$result_dept = mysqli_query($koneksi, $query_dept);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Karyawan - Sistem Penilaian Pekerja</title>
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
                <a href="karyawan.php" class="active">
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
                <a href="penugasan.php">
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
                <h2>Daftar Karyawan</h2>
                <p class="text-muted">Kelola data karyawan perusahaan</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-2"></i>Tambah Karyawan
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
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Email</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id_karyawan']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 p-2 rounded me-2">
                                        <i class="bi bi-person text-success"></i>
                                    </div>
                                    <div>
                                        <div><?php echo $row['nama']; ?></div>
                                        <small class="text-muted"><?php echo $row['alamat']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo $row['nama_departemen']; ?></td>
                            <td><span class="badge bg-primary"><?php echo $row['jabatan']; ?></span></td>
                            <td>
                                <small><i class="bi bi-envelope me-1"></i><?php echo $row['email']; ?></small>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-action" onclick="editData(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="?hapus=<?php echo $row['id_karyawan']; ?>" 
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
                    <h5 class="modal-title">Tambah Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Departemen</label>
                                <select class="form-select" name="id_departemen" required>
                                    <?php 
                                    mysqli_data_seek($result_dept, 0);
                                    while($dept = mysqli_fetch_assoc($result_dept)): 
                                    ?>
                                    <option value="<?php echo $dept['id_departemen']; ?>">
                                        <?php echo $dept['nama_departemen']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jabatan</label>
                                <input type="text" class="form-control" name="jabatan" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2" required></textarea>
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
                    <h5 class="modal-title">Edit Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id_karyawan" id="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" id="edit_nama" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Departemen</label>
                                <select class="form-select" name="id_departemen" id="edit_departemen" required>
                                    <?php 
                                    mysqli_data_seek($result_dept, 0);
                                    while($dept = mysqli_fetch_assoc($result_dept)): 
                                    ?>
                                    <option value="<?php echo $dept['id_departemen']; ?>">
                                        <?php echo $dept['nama_departemen']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jabatan</label>
                                <input type="text" class="form-control" name="jabatan" id="edit_jabatan" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="edit_email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" id="edit_alamat" rows="2" required></textarea>
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
            document.getElementById('edit_id').value = data.id_karyawan;
            document.getElementById('edit_nama').value = data.nama;
            document.getElementById('edit_departemen').value = data.id_departemen;
            document.getElementById('edit_jabatan').value = data.jabatan;
            document.getElementById('edit_email').value = data.email;
            document.getElementById('edit_alamat').value = data.alamat;
            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        }
    </script>
</body>
</html>
