<?php
// Mode 1: API Mode - Return JSON
if (isset($_GET['mode']) && $_GET['mode'] === 'api') {
    include '../dist/function.php';
    header('Content-Type: application/json');

    $search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 30;
    $offset = ($page - 1) * $limit;

    if (!empty($search)) {
        $query = "SELECT no_rm, nama_lengkap 
                  FROM pasien 
                  WHERE no_rm LIKE '%$search%' OR nama_lengkap LIKE '%$search%' 
                  ORDER BY no_rm ASC 
                  LIMIT $limit OFFSET $offset";

        $countQuery = "SELECT COUNT(*) as total 
                       FROM pasien 
                       WHERE no_rm LIKE '%$search%' OR nama_lengkap LIKE '%$search%'";
    } else {
        $query = "SELECT no_rm, nama_lengkap 
                  FROM pasien 
                  ORDER BY no_rm ASC 
                  LIMIT $limit OFFSET $offset";

        $countQuery = "SELECT COUNT(*) as total FROM pasien";
    }

    $result = mysqli_query($koneksi, $query);
    $countResult = mysqli_query($koneksi, $countQuery);
    $totalCount = mysqli_fetch_assoc($countResult)['total'];

    $data = [];
    while ($row = mysqli_fetch_array($result)) {
        $data[] = [
            'id' => $row['no_rm'],
            'text' => $row['no_rm'] . ' - ' . $row['nama_lengkap']
        ];
    }

    echo json_encode([
        'results' => $data,
        'pagination' => [
            'more' => ($offset + $limit) < $totalCount
        ]
    ]);
    exit;
}

// Mode 2: Display Mode - Show HTML Form
// include '../dist/function.php';

// Handle form submission
if (isset($_POST['gabung'])) {
    $no_rm_hapus = mysqli_real_escape_string($koneksi, $_POST['no_rm_hapus']);
    $no_rm_simpan = mysqli_real_escape_string($koneksi, $_POST['no_rm_simpan']);

    if (!empty($no_rm_hapus) && !empty($no_rm_simpan) && $no_rm_hapus != $no_rm_simpan) {
        $success_count = 0;
        $error_count = 0;
        $updated_tables = [];
        $log_messages = [];

        // Get all tables from database
        $tables_query = mysqli_query($koneksi, "SHOW TABLES");

        while ($table_row = mysqli_fetch_array($tables_query)) {
            $table_name = $table_row[0];

            // Get columns for this table
            $columns_query = mysqli_query($koneksi, "SHOW COLUMNS FROM `$table_name`");
            $columns = [];

            while ($col = mysqli_fetch_assoc($columns_query)) {
                $columns[] = $col['Field'];
            }

            // Check which columns exist and update them
            $columns_to_update = ['no_rm', 'norm', 'idrm'];

            foreach ($columns_to_update as $col_name) {
                if (in_array($col_name, $columns)) {
                    // Column exists, try to update
                    if($table_name != 'pasien') {
                        $update_query = "UPDATE `$table_name` SET `$col_name` = '$no_rm_simpan' WHERE `$col_name` = '$no_rm_hapus'";
    
                        if (mysqli_query($koneksi, $update_query)) {
                            $affected = mysqli_affected_rows($koneksi);
                            if ($affected > 0) {
                                $success_count++;
                                $updated_tables[] = "$table_name.$col_name ($affected rows)";
                                $log_messages[] = "✓ Updated $table_name.$col_name: $affected rows";
                            }
                        } else {
                            // Error occurred but we continue
                            $error_count++;
                            $log_messages[] = "✗ Error on $table_name.$col_name: " . mysqli_error($koneksi);
                        }
                    }
                }
            }
        }

        // Delete old patient record
        $delete_query = "DELETE FROM pasien WHERE no_rm = '$no_rm_hapus'";
        if (mysqli_query($koneksi, $delete_query)) {
            $log_messages[] = "✓ Deleted patient record: $no_rm_hapus";
        }

        echo "<script>alert('Gabung No RM selesai!\\n\\nBerhasil: $success_count update\\nError: $error_count\\n\\nTabel yang diupdate:\\n" . implode("\\n", $updated_tables) . "');</script>";
        echo "<script>window.location.href='index.php?halaman=gabungnorm';</script>";
    } else {
        echo "<script>alert('Pastikan memilih 2 No RM yang berbeda!');</script>";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
</head>

<body>
    <h3 class="card-title">Gabung No RM</h3>
    <div class="card shadow p-2">
        <form method="post">
            <div class="mb-3">
                <label for="no_rm_hapus" class="form-label">No RM 1 (Akan Hapus)</label>
                <select name="no_rm_hapus" id="no_rm_hapus" class="form-control form-control-sm" required>
                    <option value="">-- Pilih Pasien --</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="no_rm_simpan" class="form-label">No RM 2 (Akan Dipertahankan)</label>
                <select name="no_rm_simpan" id="no_rm_simpan" class="form-control form-control-sm" required>
                    <option value="">-- Pilih Pasien --</option>
                </select>
            </div>
            <button type="submit" onclick="return confirm('No RM 1 akan dihapus dan datanya akan di pindah ke nomor RM 2, data yang telah dipindah tidak akan bisa diselamatkan lagi, pastikan norm benar!')" class="btn btn-primary" name="gabung">Gabung</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for both dropdowns
            $('#no_rm_hapus, #no_rm_simpan').select2({
                theme: 'bootstrap-5',
                ajax: {
                    url: '../pasien/gabungnorm.php?mode=api',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
                placeholder: '-- Pilih Pasien --',
                allowClear: true
            });
        });
    </script>
</body>

</html>