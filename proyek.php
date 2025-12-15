<?php
require_once 'koneksi.php';

// Proses Tambah
if(isset($_POST['tambah'])) {
    $id_manager = $_POST['id_manager'];
    $nama_proyek = $_POST['nama_proyek'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    
    $query = "INSERT INTO proyek (id_manager, nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai) 
              VALUES ('$id_manager', '$nama_proyek', '$deskripsi', '$tanggal_mulai', '$tanggal_selesai')";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil ditambahkan!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Proses Edit
if(isset($_POST['edit'])) {
    $id_proyek = $_POST['id_proyek'];
    $id_manager = $_POST['id_manager'];
    $nama_proyek = $_POST['nama_proyek'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    
    $query = "UPDATE proyek SET id_manager='$id_manager', nama_proyek='$nama_proyek', 
              deskripsi='$deskripsi', tanggal_mulai='$tanggal_mulai', tanggal_selesai='$tanggal_selesai' 
              WHERE id_proyek=$id_proyek";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil diupdate!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Proses Hapus
if(isset($_GET['hapus'])) {
    $id_proyek = $_GET['hapus'];
    $query = "DELETE FROM proyek WHERE id_proyek=$id_proyek";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil dihapus!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Ambil data proyek dengan join manager
$query = "SELECT p.*, m.nama as nama_manager 
          FROM proyek p 
          LEFT JOIN manager m ON p.id_manager = m.id_manager 
          ORDER BY p.id_proyek DESC";
$result = mysqli_query($koneksi, $query);

// Ambil data manager untuk dropdown
$query_manager = "SELECT * FROM manager";
$result_manager = mysqli_query($koneksi, $query_manager);

// Function untuk status proyek
function getStatusProyek($tanggal_mulai, $tanggal_selesai) {
    $today = date('Y-m-d');
    if($today < $tanggal_mulai) {
        return ['status' => 'Belum Mulai', 'class' => 'secondary'];
    } elseif($today > $tanggal_selesai) {
        return ['status' => 'Selesai', 'class' => 'success'];
    } else {
        return ['status' => 'Berjalan', 'class' => 'primary'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Proyek - Sistem Penilaian Pekerja</title>
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
        
        .project-card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
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
                <a href="proyek.php" class="active">
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

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Daftar Proyek</h2>
                <p class="text-muted">Kelola proyek perusahaan</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-2"></i>Tambah Proyek
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

        <div class="row g-4">
            <?php while($row = mysqli_fetch_assoc($result)): 
                $status = getStatusProyek($row['tanggal_mulai'], $row['tanggal_selesai']);
            ?>
            <div class="col-md-6">
                <div class="project-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-start flex-grow-1">
                            <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                <i class="bi bi-briefcase text-primary fs-5"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1"><?php echo $row['nama_proyek']; ?></h5>
                                <p class="text-muted mb-0"><?php echo $row['deskripsi']; ?></p>
                            </div>
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-warning" onclick="editData(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <a href="?hapus=<?php echo $row['id_proyek']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Manager:</small>
                            <span><?php echo $row['nama_manager']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Status:</small>
                            <span class="badge bg-<?php echo $status['class']; ?>"><?php echo $status['status']; ?></span>
                        </div>
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-calendar me-2"></i>
                            <small>
                                <?php echo date('d/m/Y', strtotime($row['tanggal_mulai'])); ?> - 
                                <?php echo date('d/m/Y', strtotime($row['tanggal_selesai'])); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Proyek</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Proyek</label>
                            <input type="text" class="form-control" name="nama_proyek" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Manager</label>
                            <select class="form-select" name="id_manager" required>
                                <?php 
                                mysqli_data_seek($result_manager, 0);
                                while($manager = mysqli_fetch_assoc($result_manager)): 
                                ?>
                                <option value="<?php echo $manager['id_manager']; ?>">
                                    <?php echo $manager['nama']; ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
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

    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Proyek</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id_proyek" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Proyek</label>
                            <input type="text" class="form-control" name="nama_proyek" id="edit_nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Manager</label>
                            <select class="form-select" name="id_manager" id="edit_manager" required>
                                <?php 
                                mysqli_data_seek($result_manager, 0);
                                while($manager = mysqli_fetch_assoc($result_manager)): 
                                ?>
                                <option value="<?php echo $manager['id_manager']; ?>">
                                    <?php echo $manager['nama']; ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="edit_deskripsi" rows="3" required></textarea>
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
            document.getElementById('edit_id').value = data.id_proyek;
            document.getElementById('edit_nama').value = data.nama_proyek;
            document.getElementById('edit_manager').value = data.id_manager;
            document.getElementById('edit_deskripsi').value = data.deskripsi;
            document.getElementById('edit_mulai').value = data.tanggal_mulai;
            document.getElementById('edit_selesai').value = data.tanggal_selesai;
            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        }
    </script>
</body>
</html>
