<?php
header('Content-Type: application/json');

// Koneksi database
include '../dist/function.php';

if (isset($_GET['getData'])) {
    // Parameter dari DataTables
    $draw = $_POST['draw'];
    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];
    $order_column = $_POST['order'][0]['column']; // Index kolom
    $order_dir = $_POST['order'][0]['dir']; // ASC/DESC

    // Mapping kolom ke field database
    $columns = [
        null, // Checkbox (no database field)
        null, // No (generated)
        'nama_obat',
        'id_obat',
        'jumlah_beli',
        'harga_beli',
        'aktif_ranap',
        'aktif_poli',
        null // Aksi (no database field)
    ];

    // Query dasar
    $query = "SELECT
    a.id_obat,
    a.nama_obat,
    a.aktif_ranap,
    a.aktif_poli,
    SUM(b.jml_obat) as jumlah_beli,
    (SELECT harga_beli FROM apotek WHERE id_obat = a.id_obat ORDER BY idapotek DESC LIMIT 1) as harga_beli
    FROM apotek a
    JOIN apotek b ON a.id_obat = b.id_obat
    WHERE 1=1";

    // Pencarian
    if (!empty($search)) {
        $query .= " AND (
    a.nama_obat LIKE '%$search%' OR
    a.id_obat LIKE '%$search%' OR
    a.aktif_ranap LIKE '%$search%' OR
    a.aktif_poli LIKE '%$search%'
    )";
    }

    // Group by dan sorting
    $query .= " GROUP BY a.id_obat";

    // Sorting
    if (isset($columns[$order_column])) {
        $order_by = $columns[$order_column] . " " . $order_dir;
        $query .= " ORDER BY $order_by";
    } else {
        $query .= " ORDER BY a.nama_obat ASC";
    }

    // Pagination
    $query .= " LIMIT $start, $length";

    // Eksekusi query
    $result = $koneksi->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Hitung total records
    $total_records = $koneksi->query("SELECT COUNT(DISTINCT id_obat) FROM apotek")->fetch_row()[0];

    // Response JSON
    echo json_encode([
        "draw" => intval($draw),
        "recordsTotal" => $total_records,
        "recordsFiltered" => $total_records, // Jika ada filter, sesuaikan
        "data" => $data
    ]);
}
