<?php
// Set timezone Indonesia (WIB)
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk deteksi unit berdasarkan URL
function getUnitFromUrl()
{
    $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $url = strtolower($url);

    if (strpos($url, 'wonorejo') !== false) {
        return 'KHM 1';
    } elseif (strpos($url, 'klakah') !== false) {
        return 'KHM 2';
    } elseif (strpos($url, 'tunjung') !== false) {
        return 'KHM 3';
    } elseif (strpos($url, 'kunir') !== false) {
        return 'KHM 4';
    } else {
        // Default untuk localhost atau URL lain
        return 'KHM 1';
    }
}

// Ambil unit dari URL
$unit = getUnitFromUrl();

// Ambil data user yang login
$id_tenaga = $_SESSION['admin']['idadmin'];
$nama_dokter = $_SESSION['admin']['namalengkap'];

// Ambil parameter filter dari GET
$tanggal_dari = isset($_GET['tanggal_dari']) ? $_GET['tanggal_dari'] : '';
$tanggal_sampai = isset($_GET['tanggal_sampai']) ? $_GET['tanggal_sampai'] : '';
$halaman_sekarang = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Validasi halaman (tidak boleh kurang dari 1)
if ($halaman_sekarang < 1) {
    $halaman_sekarang = 1;
}

// Set default tanggal jika kosong (30 hari terakhir)
if (empty($tanggal_dari)) {
    $tanggal_dari = date('Y-m-d', strtotime('-30 days'));
}
if (empty($tanggal_sampai)) {
    $tanggal_sampai = date('Y-m-d');
}

// Pagination settings
$data_per_halaman = 20;
$offset = ($halaman_sekarang - 1) * $data_per_halaman;

// Query untuk hitung total data
$query_count = "SELECT COUNT(*) as total FROM absensi_dokter 
                WHERE id_tenaga='$id_tenaga' 
                AND tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai'";
$result_count = $koneksimaster->query($query_count);
$total_data = $result_count->fetch_assoc()['total'];
$total_halaman = ceil($total_data / $data_per_halaman);

// Validasi halaman tidak melebihi total halaman
if ($total_halaman > 0 && $halaman_sekarang > $total_halaman) {
    $halaman_sekarang = $total_halaman;
    $offset = ($halaman_sekarang - 1) * $data_per_halaman;
}

// Query untuk ambil data dengan pagination
$query_data = "SELECT * FROM absensi_dokter 
               WHERE id_tenaga='$id_tenaga' 
               AND tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai'
               ORDER BY created_at DESC 
               LIMIT $data_per_halaman OFFSET $offset";
$riwayat_absen = $koneksimaster->query($query_data);

// Fungsi untuk generate URL dengan parameter filter
function generateUrl($halaman, $tanggal_dari, $tanggal_sampai)
{
    return "index.php?halaman=absensidokter_history&tanggal_dari=$tanggal_dari&tanggal_sampai=$tanggal_sampai&page=$halaman";
}
?>

<style>
    .filter-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .filter-card h5 {
        color: white;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .filter-card label {
        color: white;
        font-weight: 500;
    }

    .filter-card .form-control {
        border-radius: 8px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.9);
    }

    .filter-card .form-control:focus {
        border-color: white;
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
    }

    .stats-card {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .stats-card h3 {
        font-size: 2.5rem;
        font-weight: bold;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 5px;
    }

    .stats-card p {
        color: #495057;
        margin: 0;
        font-weight: 500;
    }

    .tipe-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: bold;
    }

    .badge-masuk {
        background-color: #28a745;
        color: white;
    }

    .badge-pulang {
        background-color: #dc3545;
        color: white;
    }

    .foto-absen-preview {
        max-width: 80px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .foto-absen-preview:hover {
        transform: scale(1.1);
    }

    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
    }

    .pagination a,
    .pagination span {
        display: inline-block;
        padding: 8px 15px;
        margin: 2px;
        border: 2px solid #667eea;
        border-radius: 8px;
        text-decoration: none;
        color: #667eea;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .pagination a:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }

    .pagination span.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #764ba2;
        font-weight: bold;
    }

    .pagination span.disabled {
        border-color: #ccc;
        color: #ccc;
        cursor: not-allowed;
    }

    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table thead th {
        border: none;
        font-weight: 600;
        padding: 15px 10px;
    }

    .table tbody tr {
        transition: background-color 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-filter {
        background: white;
        color: #667eea;
        border: 2px solid white;
        font-weight: 600;
        padding: 10px 30px;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        background: #764ba2;
        color: white;
        border-color: #764ba2;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(118, 75, 162, 0.3);
    }

    .btn-reset {
        background: transparent;
        color: white;
        border: 2px solid white;
        font-weight: 600;
        padding: 10px 30px;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background: white;
        color: #667eea;
        transform: translateY(-2px);
    }

    .info-periode {
        background: #e3f2fd;
        border-left: 4px solid #2196f3;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .info-periode strong {
        color: #1976d2;
    }

    @media (max-width: 768px) {
        .filter-card {
            padding: 15px;
        }

        .stats-card h3 {
            font-size: 2rem;
        }

        .pagination a,
        .pagination span {
            padding: 6px 10px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="filter-card shadow p-2 mb-1 bg-primary text-white">
    <h4 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Absensi - <?= htmlspecialchars($nama_dokter) ?></h4>
</div>
<div class="filter-card mb-1">
    <h5><i class="bi bi-funnel-fill"></i> Filter Periode Absensi</h5>
    <form method="GET" action="index.php">
        <input type="hidden" name="halaman" value="absensidokter_history">
        <div class="row g-1">
            <div class="col-5 mb-3">
                <label class="form-label">Tanggal Dari</label>
                <input type="date" class="form-control" name="tanggal_dari" value="<?= htmlspecialchars($tanggal_dari) ?>" required>
            </div>
            <div class="col-5 mb-3">
                <label class="form-label">Tanggal Sampai</label>
                <input type="date" class="form-control" name="tanggal_sampai" value="<?= htmlspecialchars($tanggal_sampai) ?>" required>
            </div>
            <div class="col-2 mb-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-filter px-2">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <a href="index.php?halaman=absensidokter_history" class="btn btn-reset">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                </a>
            </div>
        </div>
    </form>
</div>
<div class="info-periode mb-1">
    <i class="bi bi-info-circle-fill"></i>
    <strong>Periode:</strong> <?= date('d/m/Y', strtotime($tanggal_dari)) ?> - <?= date('d/m/Y', strtotime($tanggal_sampai)) ?>
    | <strong>Total Data:</strong> <?= $total_data ?> absensi
    | <strong>Halaman:</strong> <?= $halaman_sekarang ?> dari <?= $total_halaman ?>
</div>
<div class="row g-1 mb-1">
    <?php
    // Hitung statistik
    $query_stats = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN tipe_absen='masuk' THEN 1 ELSE 0 END) as total_masuk,
                    SUM(CASE WHEN tipe_absen='pulang' THEN 1 ELSE 0 END) as total_pulang
                    FROM absensi_dokter 
                    WHERE id_tenaga='$id_tenaga' 
                    AND tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai'";
    $result_stats = $koneksimaster->query($query_stats);
    $stats = $result_stats->fetch_assoc();
    ?>
    <div class="col-md-4">
        <div class="stats-card">
            <h3><?= $stats['total'] ?></h3>
            <p><i class="bi bi-list-check"></i> Total Absensi</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <h3 style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"><?= $stats['total_masuk'] ?></h3>
            <p><i class="bi bi-box-arrow-in-right"></i> Absen Masuk</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <h3 style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"><?= $stats['total_pulang'] ?></h3>
            <p><i class="bi bi-box-arrow-right"></i> Absen Pulang</p>
        </div>
    </div>
</div>
<div class="card shadow p-2 mb-1">
    <?php if ($riwayat_absen->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Unit</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Seharusnya</th>
                        <th>Status</th>
                        <th>Shift</th>
                        <th>Tipe</th>
                        <th>Lokasi</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $offset + 1;
                    while ($riwayat = $riwayat_absen->fetch_assoc()):
                        // Hitung selisih waktu
                        $waktu_aktual = strtotime($riwayat['waktu']);
                        $waktu_seharusnya = strtotime($riwayat['waktu_seharusnya']);
                        $selisih_detik = $waktu_aktual - $waktu_seharusnya;
                        $selisih_menit = round($selisih_detik / 60);

                        // Tentukan status
                        if ($riwayat['tipe_absen'] == 'masuk') {
                            if ($selisih_menit <= 0) {
                                $status = '<span class="badge bg-success">Tepat Waktu</span>';
                            } elseif ($selisih_menit <= 15) {
                                $status = '<span class="badge bg-warning">Terlambat ' . $selisih_menit . ' mnt</span>';
                            } else {
                                $status = '<span class="badge bg-danger">Terlambat ' . $selisih_menit . ' mnt</span>';
                            }
                        } else { // pulang
                            if ($selisih_menit >= 0) {
                                $status = '<span class="badge bg-success">Tepat Waktu</span>';
                            } elseif ($selisih_menit >= -15) {
                                $status = '<span class="badge bg-info">Pulang Cepat ' . abs($selisih_menit) . ' mnt</span>';
                            } else {
                                $status = '<span class="badge bg-warning">Pulang Cepat ' . abs($selisih_menit) . ' mnt</span>';
                            }
                        }
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <span class="badge bg-primary"><?= htmlspecialchars($riwayat['unit']) ?></span>
                            </td>
                            <td>
                                <strong><?= date('d/m/Y', strtotime($riwayat['tanggal'])) ?></strong><br>
                                <small class="text-muted"><?= date('l', strtotime($riwayat['tanggal'])) ?></small>
                            </td>
                            <td><strong class="text-primary"><?= $riwayat['waktu'] ?></strong></td>
                            <td class="text-muted"><?= $riwayat['waktu_seharusnya'] ?></td>
                            <td><?= $status ?></td>
                            <td style="font-size: 0.85rem;"><?= htmlspecialchars($riwayat['shift']) ?></td>
                            <td>
                                <span class="tipe-badge <?= ($riwayat['tipe_absen'] == 'masuk') ? 'badge-masuk' : 'badge-pulang' ?>">
                                    <?= strtoupper($riwayat['tipe_absen']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($riwayat['latitude'] && $riwayat['longitude']): ?>
                                    <a href="https://www.google.com/maps?q=<?= $riwayat['latitude'] ?>,<?= $riwayat['longitude'] ?>"
                                        target="_blank"
                                        class="btn btn-sm btn-warning"
                                        title="Lat: <?= $riwayat['latitude'] ?>, Lon: <?= $riwayat['longitude'] ?>">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($riwayat['foto']): ?>
                                    <a href="../dokter/foto_absen_dokter/<?= $riwayat['foto'] ?>" target="_blank">
                                        <img src="../dokter/foto_absen_dokter/<?= $riwayat['foto'] ?>"
                                            class="foto-absen-preview"
                                            alt="Foto Absen"
                                            title="Klik untuk memperbesar">
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_halaman > 1): ?>
            <div class="pagination">
                <!-- Tombol Previous -->
                <?php if ($halaman_sekarang > 1): ?>
                    <a href="<?= generateUrl(1, $tanggal_dari, $tanggal_sampai) ?>" title="Halaman Pertama">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>
                    <a href="<?= generateUrl($halaman_sekarang - 1, $tanggal_dari, $tanggal_sampai) ?>" title="Halaman Sebelumnya">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                <?php else: ?>
                    <span class="disabled"><i class="bi bi-chevron-double-left"></i></span>
                    <span class="disabled"><i class="bi bi-chevron-left"></i></span>
                <?php endif; ?>

                <!-- Nomor Halaman -->
                <?php
                // Logika untuk menampilkan nomor halaman
                $range = 2; // Jumlah halaman di kiri dan kanan halaman aktif
                $start = max(1, $halaman_sekarang - $range);
                $end = min($total_halaman, $halaman_sekarang + $range);

                // Tampilkan halaman pertama jika tidak termasuk dalam range
                if ($start > 1) {
                    echo '<a href="' . generateUrl(1, $tanggal_dari, $tanggal_sampai) . '">1</a>';
                    if ($start > 2) {
                        echo '<span class="disabled">...</span>';
                    }
                }

                // Tampilkan halaman dalam range
                for ($i = $start; $i <= $end; $i++) {
                    if ($i == $halaman_sekarang) {
                        echo '<span class="active">' . $i . '</span>';
                    } else {
                        echo '<a href="' . generateUrl($i, $tanggal_dari, $tanggal_sampai) . '">' . $i . '</a>';
                    }
                }

                // Tampilkan halaman terakhir jika tidak termasuk dalam range
                if ($end < $total_halaman) {
                    if ($end < $total_halaman - 1) {
                        echo '<span class="disabled">...</span>';
                    }
                    echo '<a href="' . generateUrl($total_halaman, $tanggal_dari, $tanggal_sampai) . '">' . $total_halaman . '</a>';
                }
                ?>

                <!-- Tombol Next -->
                <?php if ($halaman_sekarang < $total_halaman): ?>
                    <a href="<?= generateUrl($halaman_sekarang + 1, $tanggal_dari, $tanggal_sampai) ?>" title="Halaman Selanjutnya">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="<?= generateUrl($total_halaman, $tanggal_dari, $tanggal_sampai) ?>" title="Halaman Terakhir">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                <?php else: ?>
                    <span class="disabled"><i class="bi bi-chevron-right"></i></span>
                    <span class="disabled"><i class="bi bi-chevron-double-right"></i></span>
                <?php endif; ?>
            </div>

            <!-- Info Pagination -->
            <div class="text-center mt-3 text-muted">
                <small>
                    Menampilkan data <?= $offset + 1 ?> - <?= min($offset + $data_per_halaman, $total_data) ?> dari <?= $total_data ?> total absensi
                </small>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Tidak Ada Data -->
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle" style="font-size: 3rem;"></i>
            <h5 class="mt-3">Tidak Ada Data Absensi</h5>
            <p class="mb-0">Tidak ada riwayat absensi pada periode <?= date('d/m/Y', strtotime($tanggal_dari)) ?> - <?= date('d/m/Y', strtotime($tanggal_sampai)) ?></p>
            <a href="index.php?halaman=absensidokter_history" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
            </a>
        </div>
    <?php endif; ?>
</div>

<div class="card shadow p-2">
    <div class="card-body">
        <div class="text-center mt-4">
            <a href="index.php?halaman=absensidokter" class="btn btn-secondary btn-lg">
                <i class="bi bi-arrow-left-circle"></i> Kembali ke Absensi
            </a>
        </div>
    </div>
</div>

<script>
    // Auto set max date untuk tanggal sampai (tidak bisa lebih dari hari ini)
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const tanggalSampai = document.querySelector('input[name="tanggal_sampai"]');
        tanggalSampai.setAttribute('max', today);

        // Validasi tanggal
        const tanggalDari = document.querySelector('input[name="tanggal_dari"]');

        tanggalDari.addEventListener('change', function() {
            tanggalSampai.setAttribute('min', this.value);
        });

        tanggalSampai.addEventListener('change', function() {
            tanggalDari.setAttribute('max', this.value);
        });
    });
</script>