    <?php
    error_reporting(0);
    include "../rawatjalan/api_satusehat.php";

    $tgl_mulai = date('Y-m-d', strtotime('2024-03-28'));
    $query = "SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE status_antri != 'Belum Datang' and DATE_FORMAT(jadwal, '%Y-%m-%d') >= '$tgl_mulai'  ORDER BY jadwal DESC";
    $urlPage = 'index.php?halaman=pendaftaranall';
    if (isset($_GET['status'])) {
        $statusWhere = "";
        if ($_GET['status'] == 'All') {
            $statusWhere = "";
        } elseif ($_GET['status'] == 'Kosong') {
            $statusWhere = "AND status_sinc = '' ";
        } elseif ($_GET['status'] == 'Berhasil') {
            $statusWhere = "AND status_sinc = 'Berhasil' ";
        } elseif ($_GET['status'] == 'Gagal') {
            $statusWhere = "AND status_sinc = 'Gagal' ";
        }
        $urlPage = 'index.php?halaman=pendaftaranall&date_start=' . $_GET['date_start'] . '&date_end=' . $_GET['date_end'] . '&status=' . $_GET['status'] . '';

        $query = "SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE status_antri != 'Belum Datang' and DATE_FORMAT(jadwal, '%Y-%m-%d') >= '$tgl_mulai' AND DATE_FORMAT(jadwal, '%Y-%m-%d') >= '$_GET[date_start]' AND DATE_FORMAT(jadwal, '%Y-%m-%d') <= '$_GET[date_end]' " . $statusWhere . " ORDER BY jadwal DESC";
    }

    //   Pagination
    // Parameters for pagination
    $limit = 100; // Number of entries to show in a page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    // Get the total number of records
    $result = $koneksi->query($query);
    $total_records = $result->num_rows;

    // Calculate total pages
    $total_pages = ceil($total_records / $limit);

    $cekPage = '';
    if (isset($_GET['page'])) {
        $cekPage = $_GET['page'];
    } else {
        $cekPage = '1';
    }
    // End Pagination

    // $tgl_mulai = date('2024-03-28');
    $pasien = $koneksi->query($query . " LIMIT $start, $limit;");
    ?>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link src="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                search: true,
                pagination: true,
                order: [5, 'desc'],
                pageLength: 100
            });
        });
    </script>


    <h3>Pendaftaran All</h3>
    <div class="card shadow-sm p-2 mb-2">
        <form method="POST">
            <div class="row">
                <div class="col-md-3">
                    <label>Dari Tanggal :</label>
                    <input type="date" class="form-control mb-2" name="date_start">
                </div>
                <div class="col-md-3">
                    <label>Hingga Tanggal :</label>
                    <input type="date" class="form-control mb-2" name="date_end">
                </div>
                <div class="col-md-3">
                    <label>Status :</label>
                    <select name="status" class="form-select">
                        <option value="All">All</option>
                        <option value="Berhasil">Berhasil</option>
                        <option value="Gagal">Gagal</option>
                        <option value="Kosong">Kosong</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <br>
                    <button class="btn btn-primary" name="filter">Cari</button>
                </div>
            </div>
        </form>
    </div>
    <?php
    if (isset($_POST['filter'])) {
        echo "
                <script>
                    document.location.href='index.php?halaman=pendaftaranall&date_start=" . ($_POST['date_start'] == '' ? "2024-03-28" : $_POST['date_start']) . "&date_end=" . ($_POST['date_end'] == '' ? date('Y-m-d') : $_POST['date_end']) . "&status=" . ($_POST['status'] == 'Kosong' ? "" : $_POST['status']) . "';
                </script>
            ";
    }
    ?>


    <div class="card shadow p-3">
        <b class="mb-2">Jumlah Data Yang Muncul : <?= $koneksi->query($query)->num_rows ?></b>
        <form method="POST">
            <div class="table-responsive">
                <table id="" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nama</th>
                            <th>Perawatan</th>
                            <th>Dokter</th>
                            <th>NoRM</th>
                            <th>Jadwal</th>
                            <th>CaraBayar</th>
                            <th>Poli</th>
                            <th>Perawat</th>
                            <th>Status</th>
                            <th style="width:200px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($pasien as $data) {
                        ?>
                            <tr>
                                <td>
                                    <?php if ($data['status_sinc'] != 'Berhasil') { ?>
                                        <input type="checkbox" name="selectedIds[]" value="<?= $data['idrawat'] ?>">
                                    <?php } ?>
                                </td>
                                <td><?= $data['nama_pasien'] ?></td>
                                <td><?= $data['perawatan'] ?></td>
                                <td><?= $data['dokter_rawat'] ?></td>
                                <td><?= $data['no_rm'] ?></td>
                                <td><?= $data['jadwal'] ?></td>
                                <td><?= $data['carabayar'] ?></td>
                                <td><?= $data['pertugaspoli'] ?></td>
                                <td><?= $data['perawat'] ?></td>
                                <td>
                                    <?= $data['status_sinc'] ?>
                                </td>
                                <td style="width:200px">
                                    <a href="<?= $urlPage ?>&syncCek=<?= $data['idrawat'] ?>&jadwal=<?= $data['jadwal'] ?>&pasien=<?= $data['nama_pasien'] ?>" onclick="return confirm('Apakah Anda yakin ingin menyingkronkan data pasien yang di pilih ???')" class="btn btn-sm btn-primary" style="display: none;"><i class="bi bi-arrow-repeat"></i> Cek Sync</a>
                                    <?php if ($data['status_sinc'] != 'Berhasil') { ?>
                                        <a href="<?= $urlPage ?>&sync=<?= $data['idrawat'] ?>&jadwal=<?= $data['jadwal'] ?>&pasien=<?= $data['nama_pasien'] ?>&page=<?= $cekPage ?>" onclick="return confirm('Apakah Anda yakin ingin menyingkronkan data pasien yang di pilih ???')" class="btn btn-sm btn-warning"><i class="bi bi-arrow-repeat"></i>Sync</a>
                                        <a href="index.php?halaman=detailrm&pemeriksaan&id=<?= $data['no_rm'] ?>&all&tgl=<?= $data['tgl'] ?>&rawat=<?= $data['idrawat'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <br>
            <?php
            // Display pagination
            echo '<nav>';
            echo '<ul class="pagination justify-content-center">';

            // Back button
            if ($page > 1) {
                echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page - 1) . '">Back</a></li>';
            }

            // Determine the start and end page
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);

            if ($start_page > 1) {
                echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=1">1</a></li>';
                if ($start_page > 2) {
                    echo '<li class="page-item"><span class="page-link">...</span></li>';
                }
            }

            for ($i = $start_page; $i <= $end_page; $i++) {
                if ($i == $page) {
                    echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                } else {
                    echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $i . '">' . $i . '</a></li>';
                }
            }

            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                    echo '<li class="page-item"><span class="page-link">...</span></li>';
                }
                echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
            }

            // Next button
            if ($page < $total_pages) {
                echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page + 1) . '">Next</a></li>';
            }

            echo '</ul>';
            echo '</nav>';
            ?>
            <br>
            <button type="submit" name="SyncSelect" class="btn btn-sm btn-warning" onclick="return confirm('Proses ini akan membutuhkan waktu yang lebih lama, pastikan koneksi internet baik dan jangan menutup browser anda, Apakah anda yakin ingin menyingkronkan semua data pasien yang di select ???')"><i class="bi bi-arrow-repeat"></i> Sync Selected Data</button>
            <button type="submit" name="SyncSelectBPJS" class="btn btn-sm btn-success" onclick="return confirm('Proses ini akan membutuhkan waktu yang lebih lama, pastikan koneksi internet baik dan jangan menutup browser anda, Apakah anda yakin ingin menyingkronkan semua data pasien BPJS ???')"><i class="bi bi-arrow-repeat"></i> Sync All BPJS Patient</button>
            <br><br>
        </form>
    </div>

    <?php
    // Singkron Semua Yang Di Select
    if (isset($_POST['SyncSelect'])) {
        $selectedIds = [];

        if (isset($_POST['selectedIds'])) {
            $selectedIds = $_POST['selectedIds'];
        }

        if (empty($selectedIds)) {
            echo "
                    <script>
                        alert('Tidak ada Data Yang Di Pilih');
                        document.location.href='index.php?halaman=pendaftaranall';
                    </script>
                ";
            exit;
        }

        $queryString = "SELECT *, date_format(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE idrawat IN (" . implode(',', $selectedIds) . ")";
        $result = $koneksi->query($queryString);

        if ($result->num_rows > 0) {
            // Pembuatan Token
            $getToken = curl_init();
            curl_setopt_array($getToken, array(
                CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'client_id=' . $clientKey . '&client_secret=' . $secretKey . '',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));
            $responseToken = curl_exec($getToken);

            curl_close($getToken);
            $pecahToken = json_decode($responseToken, true);
            $token = $pecahToken['access_token'];
            // Selesai Membuat Token
            // Process the results as needed (e.g., display in a table, etc.)
            while ($SData = $result->fetch_assoc()) {
                // Ambil Data Dari Rekam Medis dan Kajian Awal (rekam_medis dan kajian_awal)
                $SData2 = $koneksi->query("SELECT * FROM rekam_medis INNER JOIN kajian_awal WHERE rekam_medis.nama_pasien = kajian_awal.nama_pasien and rekam_medis.tgl_rm = kajian_awal.tgl_rm and rekam_medis.tgl_rm = '$SData[tgl]' and rekam_medis.nama_pasien = '$SData[nama_pasien]' order by kajian_awal.id_rm DESC LIMIT 1")->fetch_assoc();

                // Ambil Data Dari Lab (lab)
                $SData3 = $koneksi->query("SELECT *, COUNT(*) as JumlahDataLab FROM lab WHERE tgl = '$SData[tgl]' and pasienlab = '$SData[nama_pasien]' LIMIT 1")->fetch_assoc();

                // Ambil Data Dari Obbat Yang dimasukan pada saat mengisi rekam medis (obat_rm) dan proses pembuatan Array Untuk nanti di API
                $getObat = $koneksi->query("SELECT * FROM obat_rm WHERE idrm ='$SData[no_rm]' and  DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = '$SData[tgl]' and jenis_obat = 'Jadi'");
                $arryOb = [];
                $arryNameOb = [];
                foreach ($getObat as $obat) {
                    $arryNameOb[] = $obat['nama_obat'];
                    $arryOb[] = '
                        "Coding" :[
                            {
                                "system": "http://sys-ids.kemkes.go.id/kfa",
                                "code": "' . $obat['kode_obat'] . '",
                                "display": "' . $obat['nama_obat'] . '"
                            }
                        ]';
                }
                $stringNameObat = implode(' / ', $arryNameOb);
                $stringObat = implode(',', $arryOb);

                // echo $token;
                // echo '<pre>';
                // print_r($SData);
                // echo '</pre>';

                // Pengecekan IHS Pasien Ada Atau Tidak
                $dataPasien = $koneksi->query("SELECT * FROM pasien WHERE nama_lengkap = '$SData[nama_pasien]' LIMIT 1")->fetch_assoc();
                if ($dataPasien['ihs_id'] == '') {
                    // CREATE IHS PASIEN
                    $getIHSPasien = curl_init();
                    curl_setopt_array($getIHSPasien, array(
                        CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Patient?identifier=https%3A%2F%2Ffhir.kemkes.go.id%2Fid%2Fnik%7C' . $dataPasien['no_identitas'],
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Authorization: Bearer ' . $token,
                        ),
                    ));

                    $responseGetIHSPasien = curl_exec($getIHSPasien);
                    curl_close($getIHSPasien);
                    $getIHS = json_decode($responseGetIHSPasien, true);

                    $IHSPasien = $getIHS['entry'][0]['resource']['id'];
                    $koneksi->query("UPDATE pasien SET ihs_id='$IHSPasien' WHERE no_rm = '$SData[no_rm]' AND nama_lengkap = '$SData[nama_pasien]' LIMIT 1");
                } else {
                    $IHSPasien = $dataPasien['ihs_id'];
                }
                // Selesai Pengecekan IHS Pasien Ada Atau Tidak

                // Pengecekan IHS Dokter Ada Atau Tidak
                $dataDokter = $koneksi->query("SELECT * FROM admin WHERE namalengkap = '$SData[dokter_rawat]' AND level = 'dokter' LIMIT 1")->fetch_assoc();
                if ($dataDokter['ihs_id'] == '') {
                    $getIHSDokter = curl_init();
                    curl_setopt_array($getIHSDokter, array(
                        CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Practitioner?identifier=https%3A%2F%2Ffhir.kemkes.go.id%2Fid%2Fnik%7C' . $dataDokter['NIK'],
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Authorization: Bearer ' . $token
                        ),
                    ));

                    $responseGetIHSDokter = curl_exec($getIHSDokter);
                    curl_close($getIHSDokter);
                    $getIHSD = json_decode($responseGetIHSDokter, true);

                    $IHSDokter = $getIHSD['entry'][0]['resource']['id'];
                    $koneksi->query("UPDATE admin SET ihs_id = '$IHSDokter' WHERE namalengkap = '$SData[dokter_rawat]' AND level = 'dokter' LIMIT 1");
                } else {
                    $IHSDokter = $dataDokter['ihs_id'];
                }
                // Selesai Pengecekan IHS Dokter

                // Bundle 2
                // Buat UUID untuk Ecounter, Condition, Observation, Procedure, Composition
                $ecounterBundle2UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $conditionBundle2UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(17)), 4));
                $observationBundle2UUID1 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(21)), 4));
                $observationBundle2UUID2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(22)), 4));
                $observationBundle2UUID3 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(23)), 4));
                $observationBundle2UUID4 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(24)), 4));
                $observationBundle2UUID5 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
                $procedureBundle2UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(19)), 4));
                $compositionBundle2UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(20)), 4));

                $uuide = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuid4o = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuid4o2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuid4o3 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuid4o4 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuid4o5 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuid4o6 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuid4sr = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuid4dr = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuid4sp = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));

                $uuidc = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuidp = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuidm = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuidmr = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
                $uuidmd = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));

                $alergyBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(24)), 4));
                $clinicalBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
                $diagnoticReportBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
                $diagnoticReportResultBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
                $diagnoticReportBaseOnBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
                // Selesai Buat UUID untuk Ecounter, Condition, Observation, Procedure, Composition

                // Contion Lab
                if ($SData3['JumlahDataLab'] != 0) {
                    $conditionLab = ',{
                            "fullUrl": "urn:uuid:' . $uuid4sr . '",
                            "resource": {
                                "resourceType": "ServiceRequest",
                                "identifier": [
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/servicerequest/' . $organizationId . '",
                                        "value": "' . $SData['antrian'] . '"
                                    }
                                ],
                                "status": "active",
                                "intent": "original-order",
                                "priority": "routine",
                                "category": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://snomed.info/sct",
                                                "code": "108252007",
                                                "display": "Laboratory procedure"
                                            }
                                        ]
                                    }
                                ],
                                "code": {
                                    "coding": [
                                        {
                                            "system": "http://loinc.org",
                                            "code": "20570-8",
                                            "display": "Hematocrit [Volume Fraction] of Blood"
                                        }
                                    ],
                                    "text": "Pemeriksaan ' . $SData3['tipe_lab'] . '"
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '"
                                },
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                    "display": "Permintaan Periksa ' . $SData3['tipe_lab'] . '' . $SData3['pasienlab'] . ' di tanggal ' . $SData3['tgl'] . '"
                                },
                                "occurrenceDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "authoredOn": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "requester": {
                                    "reference": "Practitioner/' . $IHSDokter . '",
                                    "display": "' . $SData3['dokter_lab'] . '"
                                },
                                "performer": [
                                    {
                                        "reference": "Practitioner/' . $IHSDokter . '",
                                        "display": "' . $SData3['dokter_lab'] . '"
                                    }
                                ],
                                "reasonCode": [
                                    {
                                        "text": "Periksa ' . $SData3['tipe_lab'] . '"
                                    }
                                ]
                            },
                            "request": {
                                "method": "POST",
                                "url": "ServiceRequest"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $uuid4sp . '",
                            "resource": {
                                "resourceType": "Specimen",
                                "identifier": [
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/specimen/' . $organizationId . '",
                                        "value": "' . $SData['antrian'] . '",
                                        "assigner": {
                                            "reference": "Organization/' . $organizationId . '"
                                        }
                                    }
                                ],
                                "status": "available",
                                "type": {
                                    "coding": [
                                        {
                                            "system": "http://snomed.info/sct",
                                            "code": "119297000",
                                            "display": "Blood specimen"
                                        }
                                    ]
                                },
                                "collection": {
                                    "method": {
                                        "coding": [
                                            {
                                                "system": "http://snomed.info/sct",
                                                "code": "713143008",
                                                "display": "Collection of arterial blood specimen"
                                            }
                                        ]
                                    },
                                    "collectedDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '",
                                    "display": "' . $SData['nama_pasien'] . '"
                                },
                                "request": [
                                    {
                                        "reference": "urn:uuid:' . $uuid4sr . '"
                                    }
                                ],
                                "receivedTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                            },
                            "request": {
                                "method": "POST",
                                "url": "Specimen"
                            }
                        }';
                } else {
                    $conditionLab = '';
                }
                // SelesaiContion Lab


                $postBundle2 = curl_init();
                curl_setopt_array($postBundle2, array(
                    CURLOPT_URL => $baseUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
                        "resourceType": "Bundle",
                        "type": "transaction",
                        "entry": [
                            {
                                "fullUrl": "urn:uuid:' . $ecounterBundle2UUID . '",
                                "resource": {
                                    "resourceType": "Encounter",
                                    "identifier": [
                                        {
                                            "system": "http://sys-ids.kemkes.go.id/encounter/' . $organizationId . '",
                                            "value": "' . $SData['antrian'] . '"
                                        }
                                    ],
                                    "status": "finished",
                                    "class": {
                                        "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                                        "code": "AMB",
                                        "display": "ambulatory"
                                    },
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '",
                                        "display": "' . $SData['nama_pasien'] . '"
                                    },
                                    "participant": [
                                        {
                                            "type": [
                                                {
                                                    "coding": [
                                                        {
                                                            "system": "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                                            "code": "ATND",
                                                            "display": "attender"
                                                        }
                                                    ]
                                                }
                                            ],
                                            "individual": {
                                                "reference": "Practitioner/' . $IHSDokter . '",
                                                "display": "' . $SData['dokter_rawat'] . '"
                                            }
                                        }
                                    ],
                                    "period": {
                                        "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                        "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00"
                                    },
                                    "location": [
                                        {
                                            "location": {
                                                "reference": "Location/' . $locationId . '",
                                                "display": "Ruang 1A, Poliklinik Rawat Jalan, Lantai 1"
                                            }
                                        }
                                    ],
                                    "diagnosis": [
                                        {
                                            "condition": {
                                                "reference": "urn:uuid:' . $conditionBundle2UUID . '",
                                                "display": "' . $SData2['diagnosis'] . '"
                                            },
                                            "use": {
                                                "coding": [
                                                    {
                                                        "system": "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                                                        "code": "DD",
                                                        "display": "Discharge diagnosis"
                                                    }
                                                ]
                                            },
                                            "rank": 1
                                        }
                                    ],
                                    "statusHistory": [
                                        {
                                            "status": "arrived",
                                            "period": {
                                                "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                                "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00"
                                            }
                                        },
                                        {
                                            "status": "in-progress",
                                            "period": {
                                                "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                                "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00"
                                            }
                                        },
                                        {
                                            "status": "finished",
                                            "period": {
                                                "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00",
                                                "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00"
                                            }
                                        }
                                    ],
                                    "serviceProvider": {
                                        "reference": "Organization/' . $organizationId . '"
                                    }
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "Encounter"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $observationBundle2UUID1 . '",
                                "resource": {
                                    "resourceType": "Observation",
                                    "status": "final",
                                    "category": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                    "code": "vital-signs",
                                                    "display": "Vital Signs"
                                                }
                                            ]
                                        }
                                    ],
                                    "code": {
                                        "coding": [
                                            {
                                                "system": "http://loinc.org",
                                                "code": "8867-4",
                                                "display": "Heart rate"
                                            }
                                        ]
                                    },
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '"
                                    },
                                    "performer": [
                                        {
                                            "reference": "Practitioner/' . $IHSDokter . '"
                                        }
                                    ],
                                    "encounter": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                        "display": "Pemeriksaan Fisik Nadi Budi Santoso di ' . $SData['jadwal'] . '"
                                    },
                                    "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "valueQuantity": {
                                        "value": ' . $SData2['nadi'] . ',
                                        "unit": "beats/minute",
                                        "system": "http://unitsofmeasure.org",
                                        "code": "/min"
                                    }
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "Observation"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $observationBundle2UUID2 . '",
                                "resource": {
                                    "resourceType": "Observation",
                                    "status": "final",
                                    "category": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                    "code": "vital-signs",
                                                    "display": "Vital Signs"
                                                }
                                            ]
                                        }
                                    ],
                                    "code": {
                                        "coding": [
                                            {
                                                "system": "http://loinc.org",
                                                "code": "9279-1",
                                                "display": "Respiratory rate"
                                            }
                                        ]
                                    },
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '"
                                    },
                                    "performer": [
                                        {
                                            "reference": "Practitioner/' . $IHSDokter . '"
                                        }
                                    ],
                                    "encounter": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                        "display": "Pemeriksaan Fisik Pernafasan ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                    },
                                    "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "valueQuantity": {
                                        "value": ' . $SData2['frek_nafas'] . ',
                                        "unit": "breaths/minute",
                                        "system": "http://unitsofmeasure.org",
                                        "code": "/min"
                                    }
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "Observation"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $observationBundle2UUID3 . '",
                                "resource": {
                                    "resourceType": "Observation",
                                    "status": "final",
                                    "category": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                    "code": "vital-signs",
                                                    "display": "Vital Signs"
                                                }
                                            ]
                                        }
                                    ],
                                    "code": {
                                        "coding": [
                                            {
                                                "system": "http://loinc.org",
                                                "code": "8480-6",
                                                "display": "Systolic blood pressure"
                                            }
                                        ]
                                    },
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '"
                                    },
                                    "performer": [
                                        {
                                            "reference": "Practitioner/' . $IHSDokter . '"
                                        }
                                    ],
                                    "encounter": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                        "display": "Pemeriksaan Fisik Sistolik ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                    },
                                    "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "valueQuantity": {
                                        "value": ' . $SData2['sistole'] . ',
                                        "unit": "mm[Hg]",
                                        "system": "http://unitsofmeasure.org",
                                        "code": "mm[Hg]"
                                    }
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "Observation"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $observationBundle2UUID4 . '",
                                "resource": {
                                    "resourceType": "Observation",
                                    "status": "final",
                                    "category": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                    "code": "vital-signs",
                                                    "display": "Vital Signs"
                                                }
                                            ]
                                        }
                                    ],
                                    "code": {
                                        "coding": [
                                            {
                                                "system": "http://loinc.org",
                                                "code": "8462-4",
                                                "display": "Diastolic blood pressure"
                                            }
                                        ]
                                    },
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '",
                                        "display": "' . $SData['nama_pasien'] . '"
                                    },
                                    "performer": [
                                        {
                                            "reference": "Practitioner/' . $IHSDokter . '"
                                        }
                                    ],
                                    "encounter": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                        "display": "Pemeriksaan Fisik Diastolik ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                    },
                                    "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "bodySite": {
                                        "coding": [
                                            {
                                                "system": "http://snomed.info/sct",
                                                "code": "368209003",
                                                "display": "Right arm"
                                            }
                                        ]
                                    },
                                    "valueQuantity": {
                                        "value": ' . $SData2['distole'] . ',
                                        "unit": "mm[Hg]",
                                        "system": "http://unitsofmeasure.org",
                                        "code": "mm[Hg]"
                                    }
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "Observation"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $observationBundle2UUID5 . '",
                                "resource": {
                                    "resourceType": "Observation",
                                    "status": "final",
                                    "category": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                    "code": "vital-signs",
                                                    "display": "Vital Signs"
                                                }
                                            ]
                                        }
                                    ],
                                    "code": {
                                        "coding": [
                                            {
                                                "system": "http://loinc.org",
                                                "code": "8310-5",
                                                "display": "Body temperature"
                                            }
                                        ]
                                    },
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '"
                                    },
                                    "performer": [
                                        {
                                            "reference": "Practitioner/' . $IHSDokter . '"
                                        }
                                    ],
                                    "encounter": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                        "display": "Pemeriksaan Fisik Suhu ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                    },
                                    "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "valueQuantity": {
                                        "value": ' . $SData2['suhu_tubuh'] . ',
                                        "unit": "C",
                                        "system": "http://unitsofmeasure.org",
                                        "code": "Cel"
                                    }
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "Observation"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $conditionBundle2UUID . '",
                                "resource": {
                                    "resourceType": "Condition",
                                    "clinicalStatus": {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/condition-clinical",
                                                "code": "active",
                                                "display": "Active"
                                            }
                                        ]
                                    },
                                    "category": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.hl7.org/CodeSystem/condition-category",
                                                    "code": "encounter-diagnosis",
                                                    "display": "Encounter Diagnosis"
                                                }
                                            ]
                                        }
                                    ],
                                    "code": {
                                        "coding": [
                                            {
                                                "system": "http://hl7.org/fhir/sid/icd-10",
                                                "code": "' . $SData2['icd'] . '",
                                                "display": "' . $SData2['diagnosis'] . '"
                                            }
                                        ]
                                    },
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '",
                                        "display": "' . $SData['nama_pasien'] . '"
                                    },
                                    "encounter": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '"
                                    },
                                    "onsetDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "recordedDate": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "Condition"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $procedureBundle2UUID . '",
                                "resource": {
                                    "resourceType": "Procedure",
                                    "status": "completed",
                                    "category": {
                                        "coding": [
                                            {
                                                "system": "http://snomed.info/sct",
                                                "code": "103693007",
                                                "display": "Diagnostic procedure"
                                            }
                                        ],
                                        "text": "Diagnostic procedure"
                                    },
                                    "code": {
                                        "coding": [
                                            {
                                                "system": "http://hl7.org/fhir/sid/icd-9-cm",
                                                "code": "89.7",
                                                "display": "General physical examination"
                                            }
                                        ]
                                    },
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '",
                                        "display": "' . $SData['nama_pasien'] . '"
                                    },
                                    "encounter": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                        "display": "Tindakan Rontgen Dada ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                    },
                                    "performedPeriod": {
                                        "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                        "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                                    },
                                    "performer": [
                                        {
                                            "actor": {
                                                "reference": "Practitioner/' . $IHSDokter . '",
                                                "display": "' . $SData['dokter_rawat'] . '"
                                            }
                                        }
                                    ],
                                    "reasonCode": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://hl7.org/fhir/sid/icd-10",
                                                    "code": "' . $SData2['icd'] . '",
                                                    "display": "' . $SData2['diagnosis'] . '"
                                                }
                                            ]
                                        }
                                    ],
                                    "note": [
                                        {
                                            "text": "Pemeriksaan Medis dan Fisik Umum"
                                        }
                                    ]
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "Procedure"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $compositionBundle2UUID . '",
                                "resource": {
                                    "resourceType": "Composition",
                                    "identifier": {
                                        "system": "http://sys-ids.kemkes.go.id/composition/' . $organizationId . '",
                                        "value": "' . $SData['antrian'] . '"
                                    },
                                    "status": "final",
                                    "type": {
                                        "coding": [
                                            {
                                                "system": "http://loinc.org",
                                                "code": "18842-5",
                                                "display": "Discharge summary"
                                            }
                                        ]
                                    },
                                    "category": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://loinc.org",
                                                    "code": "LP173421-1",
                                                    "display": "Report"
                                                }
                                            ]
                                        }
                                    ],
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '",
                                        "display": "' . $SData['nama_pasien'] . '"
                                    },
                                    "encounter": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                        "display": "Kunjungan ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                    },
                                    "date": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "author": [
                                        {
                                            "reference": "Practitioner/' . $IHSDokter . '",
                                            "display": "' . $SData['dokter_rawat'] . '"
                                        }
                                    ],
                                    "title": "Resume Medis Rawat Jalan",
                                    "custodian": {
                                        "reference": "Organization/' . $organizationId . '"
                                    },
                                    "section": [
                                        {
                                            "code": {
                                                "coding": [
                                                    {
                                                        "system": "http://loinc.org",
                                                        "code": "42344-2",
                                                        "display": "Discharge diet (narrative)"
                                                    }
                                                ]
                                            },
                                            "text": {
                                                "status": "additional",
                                                "div": "Rekomendasi diet rendah lemak, rendah kalori"
                                            }
                                        }
                                    ]
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "Composition"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $alergyBundle5UUID . '",
                                "resource": {
                                    "resourceType": "AllergyIntolerance",
                                    "identifier": [
                                        {
                                            "system": "http://sys-ids.kemkes.go.id/allergy/' . $organizationId . '",
                                            "use": "official",
                                            "value": "98457729' . $alergyBundle5UUID . '"
                                        }
                                    ],
                                    "clinicalStatus": {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical",
                                                "code": "active",
                                                "display": "Active"
                                            }
                                        ]
                                    },
                                    "verificationStatus": {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/allergyintolerance-verification",
                                                "code": "confirmed",
                                                "display": "Confirmed"
                                            }
                                        ]
                                    },
                                    "category": [
                                        "food"
                                    ],
                                    "code": {
                                        "coding": [
                                            {
                                                "system": "http://snomed.info/sct",
                                                "code": "716186003",
                                                "display": "No known allergy"
                                            }
                                        ],
                                        "text": "Alergi Tidak Diketahui"
                                    },
                                    "patient": {
                                        "reference": "Patient/' . $IHSPasien . '",
                                        "display": "' . $SData['nama_pasien'] . '"
                                    },
                                    "encounter": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                        "display": "Kunjungan ' . $SData['nama_pasien'] . ' di tanggal ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                    },
                                    "recordedDate": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "recorder": {
                                        "reference": "Practitioner/' . $IHSDokter . '"
                                    }
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "AllergyIntolerance"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $clinicalBundle5UUID . '",
                                "resource": {
                                    "resourceType": "ClinicalImpression",
                                    "identifier": [
                                        {
                                            "system": "http://sys-ids.kemkes.go.id/clinicalimpression/' . $organizationId . '",
                                            "use": "official",
                                            "value": "Prognosis_' . $clinicalBundle5UUID . '"
                                        }
                                    ],
                                    "status": "completed",
                                    "description": "' . $SData['nama_pasien'] . ' terdiagnosa ' . $SData2['diagnosis'] . '",
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '",
                                        "display": "' . $SData['nama_pasien'] . '"
                                    },
                                    "encounter": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                        "display": "Kunjungan ' . $SData['nama_pasien'] . ' di tanggal ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                    },
                                    "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "date": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "assessor": {
                                        "reference": "Practitioner/' . $IHSDokter . '"
                                    },
                                    "problem": [
                                        {
                                            "reference": "urn:uuid:' . $uuidc . '"
                                        }
                                    ],
                                    "investigation": [
                                        {
                                            "code": {
                                                "text": "Pemeriksaan"
                                            },
                                            "item": [
                                                {
                                                    "reference": "urn:uuid:' . $uuid4dr . '"
                                                }
                                            ]
                                        }
                                    ],
                                    "summary": "Prognosis terhadap gejala klinis",
                                    "finding": [
                                        {
                                            "itemCodeableConcept": {
                                                "coding": [
                                                    {
                                                        "system": "http://hl7.org/fhir/sid/icd-10",
                                                        "code": "' . $SData2['icd'] . '",
                                                        "display": "' . $SData2['prognosa'] . '"
                                                    }
                                                ]
                                            },
                                            "itemReference": {
                                                "reference": "urn:uuid:' . $uuidc . '"
                                            }
                                        }
                                    ],
                                    "prognosisCodeableConcept": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://snomed.info/sct",
                                                    "code": "' . $SData2['kode_prognosa'] . '",
                                                    "display": "' . $SData2['prognosa'] . '"
                                                }
                                            ]
                                        }
                                    ]
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "ClinicalImpression"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $uuidm . '",
                                "resource": {
                                    "resourceType": "Medication",
                                    "meta": {
                                        "profile": [
                                            "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
                                        ]
                                    },
                                    "identifier": [
                                        {
                                            "system": "http://sys-ids.kemkes.go.id/medication/' . $organizationId . '",
                                            "use": "official",
                                            "value": "123456789-AB"
                                        }
                                    ],
                                    "code": {
                                        ' . $stringObat . '
                                    },
                                    "status": "active",
                                    "manufacturer": {
                                        "reference": "Organization/' . $organizationId . '"
                                    },
                                    "extension": [
                                        {
                                            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
                                            "valueCodeableConcept": {
                                                "coding": [
                                                    {
                                                        "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                                                        "code": "NC",
                                                        "display": "Non-compound"
                                                    }
                                                ]
                                            }
                                        }
                                    ]
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "Medication"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $uuidmr . '",
                                "resource": {
                                    "resourceType": "MedicationRequest",
                                    "identifier": [
                                        {
                                            "system": "http://sys-ids.kemkes.go.id/prescription/' . $organizationId . '",
                                            "use": "official",
                                            "value": "123456788-A"
                                        },
                                        {
                                            "system": "http://sys-ids.kemkes.go.id/prescription-item/' . $organizationId . '",
                                            "use": "official",
                                            "value": "123456788-1"
                                        }
                                    ],
                                    "status": "completed",
                                    "intent": "order",
                                    "category": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-category",
                                                    "code": "outpatient",
                                                    "display": "Outpatient"
                                                }
                                            ]
                                        }
                                    ],
                                    "priority": "routine",
                                    "medicationReference": {
                                        "reference": "Medication/' . $uuidm . '",
                                        "display": "' . $stringNameObat . '"
                                    },
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '",
                                        "display": "' . $SData['nama_pasien'] . '"
                                    },
                                    "encounter": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '"
                                    },
                                    "authoredOn": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "requester": {
                                        "reference": "Practitioner/' . $IHSDokter . '",
                                        "display": "' . $SData['dokter_rawat'] . '"
                                    },
                                    "reasonCode": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://hl7.org/fhir/sid/icd-10",
                                                    "code": "' . $SData2['icd'] . '",
                                                    "display": "' . $SData2['diagnosis'] . '"
                                                }
                                            ]
                                        }
                                    ],
                                    "courseOfTherapyType": {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-course-of-therapy",
                                                "code": "continuous",
                                                "display": "Continuing long term therapy"
                                            }
                                        ]
                                    },
                                    "dispenseRequest": {
                                        "dispenseInterval": {
                                            "value": 1,
                                            "unit": "days",
                                            "system": "http://unitsofmeasure.org",
                                            "code": "d"
                                        },
                                        "validityPeriod": {
                                            "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T00:00:00+00:00",
                                            "end": "' . date("Y-m-d", strtotime('+14 days', strtotime($SData['jadwal']))) . 'T00:00:00+00:00"
                                            
                                        },
                                        "performer": {
                                            "reference": "Organization/' . $organizationId . '"
                                        }
                                    }
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "MedicationRequest"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $uuidmd . '",
                                "resource": {
                                    "resourceType": "MedicationDispense",
                                    "identifier": [
                                        {
                                            "system": "http://sys-ids.kemkes.go.id/prescription/' . $organizationId . '",
                                            "use": "official",
                                            "value": "123456788-AB"
                                        },
                                        {
                                            "system": "http://sys-ids.kemkes.go.id/prescription-item/' . $organizationId . '",
                                            "use": "official",
                                            "value": "123456788-1"
                                        }
                                    ],
                                    "status": "completed",
                                    "category": {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category",
                                                "code": "outpatient",
                                                "display": "Outpatient"
                                            }
                                        ]
                                    },
                                    "medicationReference": {
                                        "reference": "Medication/' . $uuidm . '",
                                        "display": "' . $stringNameObat . '"
                                    },
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '",
                                        "display": "' . $SData['nama_pasien'] . '"
                                    },
                                    "context": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '"
                                    },
                                    "performer": [
                                        {
                                            "actor": {
                                                "reference": "Practitioner/' . $IHSDokter . '",
                                                "display": "' . $SData['dokter_rawat'] . '"
                                            }
                                        }
                                    ],
                                    "location": {
                                        "reference": "Location/' . $locationId . '",
                                        "display": "Apotek KHM Wonorejo"
                                    },
                                    "authorizingPrescription": [
                                        {
                                            "reference": "MedicationRequest/' . $uuidmr . '"
                                        }
                                    ],
                                    "whenPrepared": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "whenHandedOver": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "MedicationDispense"
                                }
                            },
                            {
                                "fullUrl": "urn:uuid:' . $diagnoticReportBundle5UUID . '",
                                "resource": {
                                    "resourceType": "DiagnosticReport",
                                    "identifier": [
                                        {
                                            "system": "http://sys-ids.kemkes.go.id/diagnostic/' . $organizationId . '/lab",
                                            "use": "official",
                                            "value": "5234342' . $diagnoticReportBundle5UUID . '"
                                        }
                                    ],
                                    "status": "final",
                                    "category": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.hl7.org/CodeSystem/v2-0074",
                                                    "code": "MB",
                                                    "display": "Microbiology"
                                                }
                                            ]
                                        }
                                    ],
                                    "code": {
                                        "coding": [
                                            {
                                                "system": "http://loinc.org",
                                                "code": "20570-8",
                                                "display": "Hematocrit [Volume Fraction] of Blood"
                                            }
                                        ]
                                    },
                                    "subject": {
                                        "reference": "Patient/' . $IHSPasien . '"
                                    },
                                    "encounter": {
                                        "reference": "urn:uuid:' . $ecounterBundle2UUID . '"
                                    },
                                    "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "performer": [
                                        {
                                            "reference": "Practitioner/' . $IHSDokter . '"
                                        },
                                        {
                                            "reference": "Organization/' . $organizationId . '"
                                        }
                                    ],
                                    "result": [
                                        {
                                            "reference": "urn:uuid:' . $diagnoticReportResultBundle5UUID . '"
                                        }
                                    ],
                                    "specimen": [
                                        {
                                            "reference": "urn:uuid:' . $uuid4sp . '"
                                        }
                                    ],
                                    "basedOn": [
                                        {
                                            "reference": "urn:uuid:' . $diagnoticReportBaseOnBundle5UUID . '"
                                        }
                                    ],
                                    "conclusionCode": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://snomed.info/sct",
                                                    "code": "260347006",
                                                    "display": "+"
                                                }
                                            ]
                                        }
                                    ]
                                },
                                "request": {
                                    "method": "POST",
                                    "url": "DiagnosticReport"
                                }
                            }' . $conditionLab . '
                        ]
                    }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token . ''
                    ),
                ));

                $responsePostBundle2 = curl_exec($postBundle2);

                curl_close($postBundle2);
                echo "<pre>";
                print_r($responsePostBundle2);
                $responseJSONBundle2 = json_decode($responsePostBundle2, true);
                echo "<pre>";
                // Selesai Bundle 2


                if ($responseJSONBundle2['resourceType'] === "OperationOutcome") {
                    $koneksi->query("UPDATE registrasi_rawat SET status_sinc = 'Gagal' WHERE idrawat = '$SData[idrawat]'");
                } elseif ($responseJSONBundle2['resourceType'] === "Bundle" && $responseJSONBundle2['type'] === "transaction-response") {
                    $koneksi->query("UPDATE registrasi_rawat SET status_sinc = 'Berhasil' WHERE idrawat = '$SData[idrawat]'");
                }
            }
            if (!isset($_GET['page'])) {
                echo "
                        <script>
                            alert('Berhasil Menyingkronkan Pasien, Silahkan Cek Kembali Apakah Ada Data Yang Gagal Terkirim !');
                            document.location.href='$urlPage';
                        </script>
                    ";
            } else {
                echo "
                        <script>
                            alert('Berhasil Menyingkronkan Pasien, Silahkan Cek Kembali Apakah Ada Data Yang Gagal Terkirim !');
                            document.location.href='$urlPage&page=$_GET[page]';
                        </script>
                    ";
            }
        } else {
            echo 'No records found.';
        }
    }

    // Singkron Semua yang carabayar BPJS
    if (isset($_POST['SyncSelectBPJS'])) {
        $getPatientBPJS = $koneksi->query("SELECT *, date_format(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE carabayar = 'bpjs' AND status_sinc != 'Berhasil' AND  DATE_FORMAT(jadwal, '%Y-%m-%d') >= '$tgl_mulai' ORDER BY jadwal DESC");
        // Pembuatan Token
        $getToken = curl_init();
        curl_setopt_array($getToken, array(
            CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id=' . $clientKey . '&client_secret=' . $secretKey . '',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $responseToken = curl_exec($getToken);

        curl_close($getToken);
        $pecahToken = json_decode($responseToken, true);
        $token = $pecahToken['access_token'];
        // Selesai Membuat Token
        foreach ($getPatientBPJS as $SData) {
            // Ambil Data Dari Rekam Medis dan Kajian Awal (rekam_medis dan kajian_awal)
            $SData2 = $koneksi->query("SELECT * FROM rekam_medis INNER JOIN kajian_awal WHERE rekam_medis.nama_pasien = kajian_awal.nama_pasien and rekam_medis.tgl_rm = kajian_awal.tgl_rm and rekam_medis.tgl_rm = '$SData[tgl]' and rekam_medis.nama_pasien = '$SData[nama_pasien]' order by kajian_awal.id_rm DESC LIMIT 1")->fetch_assoc();

            // Ambil Data Dari Lab (lab)
            $SData3 = $koneksi->query("SELECT *, COUNT(*) as JumlahDataLab FROM lab WHERE tgl = '$SData[tgl]' and pasienlab = '$SData[nama_pasien]' LIMIT 1")->fetch_assoc();

            // Ambil Data Dari Obbat Yang dimasukan pada saat mengisi rekam medis (obat_rm) dan proses pembuatan Array Untuk nanti di API
            $getObat = $koneksi->query("SELECT * FROM obat_rm WHERE idrm ='$SData[no_rm]' and  DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = '$SData[tgl]' and jenis_obat = 'Jadi'");
            $arryOb = [];
            $arryNameOb = [];
            foreach ($getObat as $obat) {
                $arryNameOb[] = $obat['nama_obat'];
                $arryOb[] = '
                    "Coding" :[
                        {
                            "system": "http://sys-ids.kemkes.go.id/kfa",
                            "code": "' . $obat['kode_obat'] . '",
                            "display": "' . $obat['nama_obat'] . '"
                        }
                    ]';
            }
            $stringNameObat = implode(' / ', $arryNameOb);
            $stringObat = implode(',', $arryOb);

            // echo $token;
            // echo '<pre>';
            // print_r($SData);
            // echo '</pre>';

            // Pengecekan IHS Pasien Ada Atau Tidak
            $dataPasien = $koneksi->query("SELECT * FROM pasien WHERE nama_lengkap = '$SData[nama_pasien]' LIMIT 1")->fetch_assoc();
            if ($dataPasien['ihs_id'] == '') {
                // CREATE IHS PASIEN
                $getIHSPasien = curl_init();
                curl_setopt_array($getIHSPasien, array(
                    CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Patient?identifier=https%3A%2F%2Ffhir.kemkes.go.id%2Fid%2Fnik%7C' . $dataPasien['no_identitas'],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token,
                    ),
                ));

                $responseGetIHSPasien = curl_exec($getIHSPasien);
                curl_close($getIHSPasien);
                $getIHS = json_decode($responseGetIHSPasien, true);

                $IHSPasien = $getIHS['entry'][0]['resource']['id'];
                $koneksi->query("UPDATE pasien SET ihs_id='$IHSPasien' WHERE no_rm = '$SData[no_rm]' AND nama_lengkap = '$SData[nama_pasien]' LIMIT 1");
            } else {
                $IHSPasien = $dataPasien['ihs_id'];
            }
            // Selesai Pengecekan IHS Pasien Ada Atau Tidak

            // Pengecekan IHS Dokter Ada Atau Tidak
            $dataDokter = $koneksi->query("SELECT * FROM admin WHERE namalengkap = '$SData[dokter_rawat]' AND level = 'dokter' LIMIT 1")->fetch_assoc();
            if ($dataDokter['ihs_id'] == '') {
                $getIHSDokter = curl_init();
                curl_setopt_array($getIHSDokter, array(
                    CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Practitioner?identifier=https%3A%2F%2Ffhir.kemkes.go.id%2Fid%2Fnik%7C' . $dataDokter['NIK'],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token
                    ),
                ));

                $responseGetIHSDokter = curl_exec($getIHSDokter);
                curl_close($getIHSDokter);
                $getIHSD = json_decode($responseGetIHSDokter, true);

                $IHSDokter = $getIHSD['entry'][0]['resource']['id'];
                $koneksi->query("UPDATE admin SET ihs_id = '$IHSDokter' WHERE namalengkap = '$SData[dokter_rawat]' AND level = 'dokter' LIMIT 1");
            } else {
                $IHSDokter = $dataDokter['ihs_id'];
            }
            // Selesai Pengecekan IHS Dokter

            // Bundle 2
            // Buat UUID untuk Ecounter, Condition, Observation, Procedure, Composition
            $ecounterBundle2UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $conditionBundle2UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(17)), 4));
            $observationBundle2UUID1 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(21)), 4));
            $observationBundle2UUID2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(22)), 4));
            $observationBundle2UUID3 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(23)), 4));
            $observationBundle2UUID4 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(24)), 4));
            $observationBundle2UUID5 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
            $procedureBundle2UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(19)), 4));
            $compositionBundle2UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(20)), 4));

            $uuide = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuid4o = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuid4o2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuid4o3 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuid4o4 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuid4o5 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuid4o6 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuid4sr = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuid4dr = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuid4sp = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));

            $uuidc = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuidp = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuidm = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuidmr = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
            $uuidmd = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));

            $alergyBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(24)), 4));
            $clinicalBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
            $diagnoticReportBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
            $diagnoticReportResultBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
            $diagnoticReportBaseOnBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
            // Selesai Buat UUID untuk Ecounter, Condition, Observation, Procedure, Composition

            // Contion Lab
            if ($SData3['JumlahDataLab'] != 0) {
                $conditionLab = ',{
                        "fullUrl": "urn:uuid:' . $uuid4sr . '",
                        "resource": {
                            "resourceType": "ServiceRequest",
                            "identifier": [
                                {
                                    "system": "http://sys-ids.kemkes.go.id/servicerequest/' . $organizationId . '",
                                    "value": "' . $SData['antrian'] . '"
                                }
                            ],
                            "status": "active",
                            "intent": "original-order",
                            "priority": "routine",
                            "category": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://snomed.info/sct",
                                            "code": "108252007",
                                            "display": "Laboratory procedure"
                                        }
                                    ]
                                }
                            ],
                            "code": {
                                "coding": [
                                    {
                                        "system": "http://loinc.org",
                                        "code": "20570-8",
                                        "display": "Hematocrit [Volume Fraction] of Blood"
                                    }
                                ],
                                "text": "Pemeriksaan ' . $SData3['tipe_lab'] . '"
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '"
                            },
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                "display": "Permintaan Periksa ' . $SData3['tipe_lab'] . '' . $SData3['pasienlab'] . ' di tanggal ' . $SData3['tgl'] . '"
                            },
                            "occurrenceDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "authoredOn": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "requester": {
                                "reference": "Practitioner/' . $IHSDokter . '",
                                "display": "' . $SData3['dokter_lab'] . '"
                            },
                            "performer": [
                                {
                                    "reference": "Practitioner/' . $IHSDokter . '",
                                    "display": "' . $SData3['dokter_lab'] . '"
                                }
                            ],
                            "reasonCode": [
                                {
                                    "text": "Periksa ' . $SData3['tipe_lab'] . '"
                                }
                            ]
                        },
                        "request": {
                            "method": "POST",
                            "url": "ServiceRequest"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $uuid4sp . '",
                        "resource": {
                            "resourceType": "Specimen",
                            "identifier": [
                                {
                                    "system": "http://sys-ids.kemkes.go.id/specimen/' . $organizationId . '",
                                    "value": "' . $SData['antrian'] . '",
                                    "assigner": {
                                        "reference": "Organization/' . $organizationId . '"
                                    }
                                }
                            ],
                            "status": "available",
                            "type": {
                                "coding": [
                                    {
                                        "system": "http://snomed.info/sct",
                                        "code": "119297000",
                                        "display": "Blood specimen"
                                    }
                                ]
                            },
                            "collection": {
                                "method": {
                                    "coding": [
                                        {
                                            "system": "http://snomed.info/sct",
                                            "code": "713143008",
                                            "display": "Collection of arterial blood specimen"
                                        }
                                    ]
                                },
                                "collectedDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '",
                                "display": "' . $SData['nama_pasien'] . '"
                            },
                            "request": [
                                {
                                    "reference": "urn:uuid:' . $uuid4sr . '"
                                }
                            ],
                            "receivedTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                        },
                        "request": {
                            "method": "POST",
                            "url": "Specimen"
                        }
                    }';
            } else {
                $conditionLab = '';
            }
            // SelesaiContion Lab


            $postBundle2 = curl_init();
            curl_setopt_array($postBundle2, array(
                CURLOPT_URL => $baseUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "resourceType": "Bundle",
                    "type": "transaction",
                    "entry": [
                        {
                            "fullUrl": "urn:uuid:' . $ecounterBundle2UUID . '",
                            "resource": {
                                "resourceType": "Encounter",
                                "identifier": [
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/encounter/' . $organizationId . '",
                                        "value": "' . $SData['antrian'] . '"
                                    }
                                ],
                                "status": "finished",
                                "class": {
                                    "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                                    "code": "AMB",
                                    "display": "ambulatory"
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '",
                                    "display": "' . $SData['nama_pasien'] . '"
                                },
                                "participant": [
                                    {
                                        "type": [
                                            {
                                                "coding": [
                                                    {
                                                        "system": "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                                        "code": "ATND",
                                                        "display": "attender"
                                                    }
                                                ]
                                            }
                                        ],
                                        "individual": {
                                            "reference": "Practitioner/' . $IHSDokter . '",
                                            "display": "' . $SData['dokter_rawat'] . '"
                                        }
                                    }
                                ],
                                "period": {
                                    "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00"
                                },
                                "location": [
                                    {
                                        "location": {
                                            "reference": "Location/' . $locationId . '",
                                            "display": "Ruang 1A, Poliklinik Rawat Jalan, Lantai 1"
                                        }
                                    }
                                ],
                                "diagnosis": [
                                    {
                                        "condition": {
                                            "reference": "urn:uuid:' . $conditionBundle2UUID . '",
                                            "display": "' . $SData2['diagnosis'] . '"
                                        },
                                        "use": {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                                                    "code": "DD",
                                                    "display": "Discharge diagnosis"
                                                }
                                            ]
                                        },
                                        "rank": 1
                                    }
                                ],
                                "statusHistory": [
                                    {
                                        "status": "arrived",
                                        "period": {
                                            "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                            "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00"
                                        }
                                    },
                                    {
                                        "status": "in-progress",
                                        "period": {
                                            "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                            "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00"
                                        }
                                    },
                                    {
                                        "status": "finished",
                                        "period": {
                                            "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00",
                                            "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00"
                                        }
                                    }
                                ],
                                "serviceProvider": {
                                    "reference": "Organization/' . $organizationId . '"
                                }
                            },
                            "request": {
                                "method": "POST",
                                "url": "Encounter"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $observationBundle2UUID1 . '",
                            "resource": {
                                "resourceType": "Observation",
                                "status": "final",
                                "category": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                "code": "vital-signs",
                                                "display": "Vital Signs"
                                            }
                                        ]
                                    }
                                ],
                                "code": {
                                    "coding": [
                                        {
                                            "system": "http://loinc.org",
                                            "code": "8867-4",
                                            "display": "Heart rate"
                                        }
                                    ]
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '"
                                },
                                "performer": [
                                    {
                                        "reference": "Practitioner/' . $IHSDokter . '"
                                    }
                                ],
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                    "display": "Pemeriksaan Fisik Nadi Budi Santoso di ' . $SData['jadwal'] . '"
                                },
                                "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "valueQuantity": {
                                    "value": ' . $SData2['nadi'] . ',
                                    "unit": "beats/minute",
                                    "system": "http://unitsofmeasure.org",
                                    "code": "/min"
                                }
                            },
                            "request": {
                                "method": "POST",
                                "url": "Observation"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $observationBundle2UUID2 . '",
                            "resource": {
                                "resourceType": "Observation",
                                "status": "final",
                                "category": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                "code": "vital-signs",
                                                "display": "Vital Signs"
                                            }
                                        ]
                                    }
                                ],
                                "code": {
                                    "coding": [
                                        {
                                            "system": "http://loinc.org",
                                            "code": "9279-1",
                                            "display": "Respiratory rate"
                                        }
                                    ]
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '"
                                },
                                "performer": [
                                    {
                                        "reference": "Practitioner/' . $IHSDokter . '"
                                    }
                                ],
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                    "display": "Pemeriksaan Fisik Pernafasan ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                },
                                "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "valueQuantity": {
                                    "value": ' . $SData2['frek_nafas'] . ',
                                    "unit": "breaths/minute",
                                    "system": "http://unitsofmeasure.org",
                                    "code": "/min"
                                }
                            },
                            "request": {
                                "method": "POST",
                                "url": "Observation"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $observationBundle2UUID3 . '",
                            "resource": {
                                "resourceType": "Observation",
                                "status": "final",
                                "category": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                "code": "vital-signs",
                                                "display": "Vital Signs"
                                            }
                                        ]
                                    }
                                ],
                                "code": {
                                    "coding": [
                                        {
                                            "system": "http://loinc.org",
                                            "code": "8480-6",
                                            "display": "Systolic blood pressure"
                                        }
                                    ]
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '"
                                },
                                "performer": [
                                    {
                                        "reference": "Practitioner/' . $IHSDokter . '"
                                    }
                                ],
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                    "display": "Pemeriksaan Fisik Sistolik ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                },
                                "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "valueQuantity": {
                                    "value": ' . $SData2['sistole'] . ',
                                    "unit": "mm[Hg]",
                                    "system": "http://unitsofmeasure.org",
                                    "code": "mm[Hg]"
                                }
                            },
                            "request": {
                                "method": "POST",
                                "url": "Observation"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $observationBundle2UUID4 . '",
                            "resource": {
                                "resourceType": "Observation",
                                "status": "final",
                                "category": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                "code": "vital-signs",
                                                "display": "Vital Signs"
                                            }
                                        ]
                                    }
                                ],
                                "code": {
                                    "coding": [
                                        {
                                            "system": "http://loinc.org",
                                            "code": "8462-4",
                                            "display": "Diastolic blood pressure"
                                        }
                                    ]
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '",
                                    "display": "' . $SData['nama_pasien'] . '"
                                },
                                "performer": [
                                    {
                                        "reference": "Practitioner/' . $IHSDokter . '"
                                    }
                                ],
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                    "display": "Pemeriksaan Fisik Diastolik ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                },
                                "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "bodySite": {
                                    "coding": [
                                        {
                                            "system": "http://snomed.info/sct",
                                            "code": "368209003",
                                            "display": "Right arm"
                                        }
                                    ]
                                },
                                "valueQuantity": {
                                    "value": ' . $SData2['distole'] . ',
                                    "unit": "mm[Hg]",
                                    "system": "http://unitsofmeasure.org",
                                    "code": "mm[Hg]"
                                }
                            },
                            "request": {
                                "method": "POST",
                                "url": "Observation"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $observationBundle2UUID5 . '",
                            "resource": {
                                "resourceType": "Observation",
                                "status": "final",
                                "category": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                "code": "vital-signs",
                                                "display": "Vital Signs"
                                            }
                                        ]
                                    }
                                ],
                                "code": {
                                    "coding": [
                                        {
                                            "system": "http://loinc.org",
                                            "code": "8310-5",
                                            "display": "Body temperature"
                                        }
                                    ]
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '"
                                },
                                "performer": [
                                    {
                                        "reference": "Practitioner/' . $IHSDokter . '"
                                    }
                                ],
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                    "display": "Pemeriksaan Fisik Suhu ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                },
                                "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "valueQuantity": {
                                    "value": ' . $SData2['suhu_tubuh'] . ',
                                    "unit": "C",
                                    "system": "http://unitsofmeasure.org",
                                    "code": "Cel"
                                }
                            },
                            "request": {
                                "method": "POST",
                                "url": "Observation"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $conditionBundle2UUID . '",
                            "resource": {
                                "resourceType": "Condition",
                                "clinicalStatus": {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/condition-clinical",
                                            "code": "active",
                                            "display": "Active"
                                        }
                                    ]
                                },
                                "category": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/condition-category",
                                                "code": "encounter-diagnosis",
                                                "display": "Encounter Diagnosis"
                                            }
                                        ]
                                    }
                                ],
                                "code": {
                                    "coding": [
                                        {
                                            "system": "http://hl7.org/fhir/sid/icd-10",
                                            "code": "' . $SData2['icd'] . '",
                                            "display": "' . $SData2['diagnosis'] . '"
                                        }
                                    ]
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '",
                                    "display": "' . $SData['nama_pasien'] . '"
                                },
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '"
                                },
                                "onsetDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "recordedDate": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                            },
                            "request": {
                                "method": "POST",
                                "url": "Condition"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $procedureBundle2UUID . '",
                            "resource": {
                                "resourceType": "Procedure",
                                "status": "completed",
                                "category": {
                                    "coding": [
                                        {
                                            "system": "http://snomed.info/sct",
                                            "code": "103693007",
                                            "display": "Diagnostic procedure"
                                        }
                                    ],
                                    "text": "Diagnostic procedure"
                                },
                                "code": {
                                    "coding": [
                                        {
                                            "system": "http://hl7.org/fhir/sid/icd-9-cm",
                                            "code": "89.7",
                                            "display": "General physical examination"
                                        }
                                    ]
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '",
                                    "display": "' . $SData['nama_pasien'] . '"
                                },
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                    "display": "Tindakan Rontgen Dada ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                },
                                "performedPeriod": {
                                    "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                    "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                                },
                                "performer": [
                                    {
                                        "actor": {
                                            "reference": "Practitioner/' . $IHSDokter . '",
                                            "display": "' . $SData['dokter_rawat'] . '"
                                        }
                                    }
                                ],
                                "reasonCode": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://hl7.org/fhir/sid/icd-10",
                                                "code": "' . $SData2['icd'] . '",
                                                "display": "' . $SData2['diagnosis'] . '"
                                            }
                                        ]
                                    }
                                ],
                                "note": [
                                    {
                                        "text": "Pemeriksaan Medis dan Fisik Umum"
                                    }
                                ]
                            },
                            "request": {
                                "method": "POST",
                                "url": "Procedure"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $compositionBundle2UUID . '",
                            "resource": {
                                "resourceType": "Composition",
                                "identifier": {
                                    "system": "http://sys-ids.kemkes.go.id/composition/' . $organizationId . '",
                                    "value": "' . $SData['antrian'] . '"
                                },
                                "status": "final",
                                "type": {
                                    "coding": [
                                        {
                                            "system": "http://loinc.org",
                                            "code": "18842-5",
                                            "display": "Discharge summary"
                                        }
                                    ]
                                },
                                "category": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://loinc.org",
                                                "code": "LP173421-1",
                                                "display": "Report"
                                            }
                                        ]
                                    }
                                ],
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '",
                                    "display": "' . $SData['nama_pasien'] . '"
                                },
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                    "display": "Kunjungan ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                },
                                "date": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "author": [
                                    {
                                        "reference": "Practitioner/' . $IHSDokter . '",
                                        "display": "' . $SData['dokter_rawat'] . '"
                                    }
                                ],
                                "title": "Resume Medis Rawat Jalan",
                                "custodian": {
                                    "reference": "Organization/' . $organizationId . '"
                                },
                                "section": [
                                    {
                                        "code": {
                                            "coding": [
                                                {
                                                    "system": "http://loinc.org",
                                                    "code": "42344-2",
                                                    "display": "Discharge diet (narrative)"
                                                }
                                            ]
                                        },
                                        "text": {
                                            "status": "additional",
                                            "div": "Rekomendasi diet rendah lemak, rendah kalori"
                                        }
                                    }
                                ]
                            },
                            "request": {
                                "method": "POST",
                                "url": "Composition"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $alergyBundle5UUID . '",
                            "resource": {
                                "resourceType": "AllergyIntolerance",
                                "identifier": [
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/allergy/' . $organizationId . '",
                                        "use": "official",
                                        "value": "98457729' . $alergyBundle5UUID . '"
                                    }
                                ],
                                "clinicalStatus": {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical",
                                            "code": "active",
                                            "display": "Active"
                                        }
                                    ]
                                },
                                "verificationStatus": {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/allergyintolerance-verification",
                                            "code": "confirmed",
                                            "display": "Confirmed"
                                        }
                                    ]
                                },
                                "category": [
                                    "food"
                                ],
                                "code": {
                                    "coding": [
                                        {
                                            "system": "http://snomed.info/sct",
                                            "code": "716186003",
                                            "display": "No known allergy"
                                        }
                                    ],
                                    "text": "Alergi Tidak Diketahui"
                                },
                                "patient": {
                                    "reference": "Patient/' . $IHSPasien . '",
                                    "display": "' . $SData['nama_pasien'] . '"
                                },
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                    "display": "Kunjungan ' . $SData['nama_pasien'] . ' di tanggal ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                },
                                "recordedDate": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "recorder": {
                                    "reference": "Practitioner/' . $IHSDokter . '"
                                }
                            },
                            "request": {
                                "method": "POST",
                                "url": "AllergyIntolerance"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $clinicalBundle5UUID . '",
                            "resource": {
                                "resourceType": "ClinicalImpression",
                                "identifier": [
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/clinicalimpression/' . $organizationId . '",
                                        "use": "official",
                                        "value": "Prognosis_' . $clinicalBundle5UUID . '"
                                    }
                                ],
                                "status": "completed",
                                "description": "' . $SData['nama_pasien'] . ' terdiagnosa ' . $SData2['diagnosis'] . '",
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '",
                                    "display": "' . $SData['nama_pasien'] . '"
                                },
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                    "display": "Kunjungan ' . $SData['nama_pasien'] . ' di tanggal ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                                },
                                "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "date": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "assessor": {
                                    "reference": "Practitioner/' . $IHSDokter . '"
                                },
                                "problem": [
                                    {
                                        "reference": "urn:uuid:' . $uuidc . '"
                                    }
                                ],
                                "investigation": [
                                    {
                                        "code": {
                                            "text": "Pemeriksaan"
                                        },
                                        "item": [
                                            {
                                                "reference": "urn:uuid:' . $uuid4dr . '"
                                            }
                                        ]
                                    }
                                ],
                                "summary": "Prognosis terhadap gejala klinis",
                                "finding": [
                                    {
                                        "itemCodeableConcept": {
                                            "coding": [
                                                {
                                                    "system": "http://hl7.org/fhir/sid/icd-10",
                                                    "code": "' . $SData2['icd'] . '",
                                                    "display": "' . $SData2['prognosa'] . '"
                                                }
                                            ]
                                        },
                                        "itemReference": {
                                            "reference": "urn:uuid:' . $uuidc . '"
                                        }
                                    }
                                ],
                                "prognosisCodeableConcept": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://snomed.info/sct",
                                                "code": "' . $SData2['kode_prognosa'] . '",
                                                "display": "' . $SData2['prognosa'] . '"
                                            }
                                        ]
                                    }
                                ]
                            },
                            "request": {
                                "method": "POST",
                                "url": "ClinicalImpression"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $uuidm . '",
                            "resource": {
                                "resourceType": "Medication",
                                "meta": {
                                    "profile": [
                                        "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
                                    ]
                                },
                                "identifier": [
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/medication/' . $organizationId . '",
                                        "use": "official",
                                        "value": "123456789-AB"
                                    }
                                ],
                                "code": {
                                    ' . $stringObat . '
                                },
                                "status": "active",
                                "manufacturer": {
                                    "reference": "Organization/' . $organizationId . '"
                                },
                                "extension": [
                                    {
                                        "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
                                        "valueCodeableConcept": {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                                                    "code": "NC",
                                                    "display": "Non-compound"
                                                }
                                            ]
                                        }
                                    }
                                ]
                            },
                            "request": {
                                "method": "POST",
                                "url": "Medication"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $uuidmr . '",
                            "resource": {
                                "resourceType": "MedicationRequest",
                                "identifier": [
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/prescription/' . $organizationId . '",
                                        "use": "official",
                                        "value": "123456788-A"
                                    },
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/prescription-item/' . $organizationId . '",
                                        "use": "official",
                                        "value": "123456788-1"
                                    }
                                ],
                                "status": "completed",
                                "intent": "order",
                                "category": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-category",
                                                "code": "outpatient",
                                                "display": "Outpatient"
                                            }
                                        ]
                                    }
                                ],
                                "priority": "routine",
                                "medicationReference": {
                                    "reference": "Medication/' . $uuidm . '",
                                    "display": "' . $stringNameObat . '"
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '",
                                    "display": "' . $SData['nama_pasien'] . '"
                                },
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '"
                                },
                                "authoredOn": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "requester": {
                                    "reference": "Practitioner/' . $IHSDokter . '",
                                    "display": "' . $SData['dokter_rawat'] . '"
                                },
                                "reasonCode": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://hl7.org/fhir/sid/icd-10",
                                                "code": "' . $SData2['icd'] . '",
                                                "display": "' . $SData2['diagnosis'] . '"
                                            }
                                        ]
                                    }
                                ],
                                "courseOfTherapyType": {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-course-of-therapy",
                                            "code": "continuous",
                                            "display": "Continuing long term therapy"
                                        }
                                    ]
                                },
                                "dispenseRequest": {
                                    "dispenseInterval": {
                                        "value": 1,
                                        "unit": "days",
                                        "system": "http://unitsofmeasure.org",
                                        "code": "d"
                                    },
                                    "validityPeriod": {
                                        "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T00:00:00+00:00",
                                        "end": "' . date("Y-m-d", strtotime('+14 days', strtotime($SData['jadwal']))) . 'T00:00:00+00:00"
                                        
                                    },
                                    "performer": {
                                        "reference": "Organization/' . $organizationId . '"
                                    }
                                }
                            },
                            "request": {
                                "method": "POST",
                                "url": "MedicationRequest"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $uuidmd . '",
                            "resource": {
                                "resourceType": "MedicationDispense",
                                "identifier": [
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/prescription/' . $organizationId . '",
                                        "use": "official",
                                        "value": "123456788-AB"
                                    },
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/prescription-item/' . $organizationId . '",
                                        "use": "official",
                                        "value": "123456788-1"
                                    }
                                ],
                                "status": "completed",
                                "category": {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category",
                                            "code": "outpatient",
                                            "display": "Outpatient"
                                        }
                                    ]
                                },
                                "medicationReference": {
                                    "reference": "Medication/' . $uuidm . '",
                                    "display": "' . $stringNameObat . '"
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '",
                                    "display": "' . $SData['nama_pasien'] . '"
                                },
                                "context": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '"
                                },
                                "performer": [
                                    {
                                        "actor": {
                                            "reference": "Practitioner/' . $IHSDokter . '",
                                            "display": "' . $SData['dokter_rawat'] . '"
                                        }
                                    }
                                ],
                                "location": {
                                    "reference": "Location/' . $locationId . '",
                                    "display": "Apotek KHM Wonorejo"
                                },
                                "authorizingPrescription": [
                                    {
                                        "reference": "MedicationRequest/' . $uuidmr . '"
                                    }
                                ],
                                "whenPrepared": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "whenHandedOver": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                            },
                            "request": {
                                "method": "POST",
                                "url": "MedicationDispense"
                            }
                        },
                        {
                            "fullUrl": "urn:uuid:' . $diagnoticReportBundle5UUID . '",
                            "resource": {
                                "resourceType": "DiagnosticReport",
                                "identifier": [
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/diagnostic/' . $organizationId . '/lab",
                                        "use": "official",
                                        "value": "5234342' . $diagnoticReportBundle5UUID . '"
                                    }
                                ],
                                "status": "final",
                                "category": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/v2-0074",
                                                "code": "MB",
                                                "display": "Microbiology"
                                            }
                                        ]
                                    }
                                ],
                                "code": {
                                    "coding": [
                                        {
                                            "system": "http://loinc.org",
                                            "code": "20570-8",
                                            "display": "Hematocrit [Volume Fraction] of Blood"
                                        }
                                    ]
                                },
                                "subject": {
                                    "reference": "Patient/' . $IHSPasien . '"
                                },
                                "encounter": {
                                    "reference": "urn:uuid:' . $ecounterBundle2UUID . '"
                                },
                                "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "performer": [
                                    {
                                        "reference": "Practitioner/' . $IHSDokter . '"
                                    },
                                    {
                                        "reference": "Organization/' . $organizationId . '"
                                    }
                                ],
                                "result": [
                                    {
                                        "reference": "urn:uuid:' . $diagnoticReportResultBundle5UUID . '"
                                    }
                                ],
                                "specimen": [
                                    {
                                        "reference": "urn:uuid:' . $uuid4sp . '"
                                    }
                                ],
                                "basedOn": [
                                    {
                                        "reference": "urn:uuid:' . $diagnoticReportBaseOnBundle5UUID . '"
                                    }
                                ],
                                "conclusionCode": [
                                    {
                                        "coding": [
                                            {
                                                "system": "http://snomed.info/sct",
                                                "code": "260347006",
                                                "display": "+"
                                            }
                                        ]
                                    }
                                ]
                            },
                            "request": {
                                "method": "POST",
                                "url": "DiagnosticReport"
                            }
                        }' . $conditionLab . '
                    ]
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token . ''
                ),
            ));

            $responsePostBundle2 = curl_exec($postBundle2);

            curl_close($postBundle2);
            echo "<pre>";
            print_r($responsePostBundle2);
            $responseJSONBundle2 = json_decode($responsePostBundle2, true);
            echo "<pre>";
            // Selesai Bundle 2


            if ($responseJSONBundle2['resourceType'] === "OperationOutcome") {
                $koneksi->query("UPDATE registrasi_rawat SET status_sinc = 'Gagal' WHERE idrawat = '$SData[idrawat]'");
            } elseif ($responseJSONBundle2['resourceType'] === "Bundle" && $responseJSONBundle2['type'] === "transaction-response") {
                $koneksi->query("UPDATE registrasi_rawat SET status_sinc = 'Berhasil' WHERE idrawat = '$SData[idrawat]'");
            }
        }
        if (!isset($_GET['page'])) {
            echo "
                    <script>
                        alert('Berhasil Menyingkronkan Pasien, Silahkan Cek Kembali Apakah Ada Data Yang Gagal Terkirim !');
                        document.location.href='$urlPage';
                    </script>
                ";
        } else {
            echo "
                    <script>
                        alert('Berhasil Menyingkronkan Pasien, Silahkan Cek Kembali Apakah Ada Data Yang Gagal Terkirim !');
                        document.location.href='$urlPage&page=$_GET[page]';
                    </script>
                ";
        }
    }

    // Check Data Synchrone
    if (isset($_GET['syncCek'])) {
        // $koneksi->query("UPDATE registrasi_rawat SET end = '09:00:00' WHERE idrawat = '$_GET[syncCek]'");
        $SData = $koneksi->query("SELECT *, date_format(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE idrawat = '$_GET[syncCek]'")->fetch_assoc();

        $SData2 = $koneksi->query("SELECT * FROM rekam_medis INNER JOIN kajian_awal WHERE rekam_medis.nama_pasien = kajian_awal.nama_pasien and rekam_medis.tgl_rm = kajian_awal.tgl_rm and rekam_medis.tgl_rm = '$SData[tgl]' and rekam_medis.nama_pasien='$_GET[pasien]' ORDER BY kajian_awal.id_rm DESC LIMIT 1")->fetch_assoc();

        $getObat = $koneksi->query("SELECT * FROM obat_rm WHERE idrm ='$SData[no_rm]' and  DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = '$SData[tgl]' and jenis_obat = 'Jadi'");

        $SData3 = $koneksi->query("SELECT * FROM lab WHERE tgl = '$SData[tgl]' and pasienlab = '$SData[nama_pasien]' LIMIT 1")->fetch_assoc();
        echo date('Y-m-d', strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00 <br>';
        echo date('Y-m-d', strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00 <br>';
        echo date('Y-m-d', strtotime($SData['jadwal'])) . 'T' . date('h:i:s', strtotime($SData['jadwal'])) . '+00:00';
        echo "<pre>";
        print_r($SData);
        echo "<pre>";
        echo "<pre>";
        print_r($SData2);
        echo "<pre>";
        echo "<pre>";
        print_r($SData3);
        echo "<pre>";
        echo "<pre>";
        $arryOb = [];
        $arryNameOb = [];
        foreach ($getObat as $obat) {
            $arryNameOb[] = $obat['nama_obat'];
            $arryOb[] = '{
                    "system": "http://sys-ids.kemkes.go.id/kfa",
                    "code": "' . $obat['kode_obat'] . '",
                    "display": "' . $obat['nama_obat'] . '"
                }';
        }
        $stringNameObat = implode(' / ', $arryNameOb);
        $stringObat = implode(',', $arryOb);
        echo $stringObat;
        echo "<pre>";
        echo $stringNameObat;
        echo "<pre>";
    }

    // Singkron Per Row
    if (isset($_GET['sync'])) {
        // Ambil Data Dari Pendaftaran (registrasi_rawat)
        $SData = $koneksi->query("SELECT *, date_format(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE idrawat = '$_GET[sync]'")->fetch_assoc();

        // Ambil Data Dari Rekam Medis dan Kajian Awal (rekam_medis dan kajian_awal)
        $SData2 = $koneksi->query("SELECT * FROM rekam_medis INNER JOIN kajian_awal WHERE rekam_medis.nama_pasien = kajian_awal.nama_pasien and rekam_medis.tgl_rm = kajian_awal.tgl_rm and rekam_medis.tgl_rm = '$SData[tgl]' and rekam_medis.nama_pasien = '$SData[nama_pasien]' order by kajian_awal.id_rm DESC LIMIT 1")->fetch_assoc();

        // Ambil Data Dari Lab (lab)
        $SData3 = $koneksi->query("SELECT *, COUNT(*) as JumlahDataLab FROM lab WHERE tgl = '$SData[tgl]' and pasienlab = '$SData[nama_pasien]' LIMIT 1")->fetch_assoc();

        // Ambil Data Dari Obbat Yang dimasukan pada saat mengisi rekam medis (obat_rm) dan proses pembuatan Array Untuk nanti di API
        $getObat = $koneksi->query("SELECT * FROM obat_rm WHERE idrm ='$SData[no_rm]' and  DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = '$SData[tgl]' and jenis_obat = 'Jadi'");
        $arryOb = [];
        $arryNameOb = [];
        foreach ($getObat as $obat) {
            $arryNameOb[] = $obat['nama_obat'];
            $arryOb[] = '
                "Coding" :[
                    {
                        "system": "http://sys-ids.kemkes.go.id/kfa",
                        "code": "' . $obat['kode_obat'] . '",
                        "display": "' . $obat['nama_obat'] . '"
                    }
                ]';
        }
        $stringNameObat = implode(' / ', $arryNameOb);
        $stringObat = implode(',', $arryOb);

        // Pembuatan Token
        $getToken = curl_init();
        curl_setopt_array($getToken, array(
            CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id=' . $clientKey . '&client_secret=' . $secretKey . '',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $responseToken = curl_exec($getToken);

        curl_close($getToken);
        $pecahToken = json_decode($responseToken, true);
        $token = $pecahToken['access_token'];
        // Selesai Membuat Token

        // echo $token;
        // echo '<pre>';
        // print_r($SData);
        // echo '</pre>';

        // Pengecekan IHS Pasien Ada Atau Tidak
        $dataPasien = $koneksi->query("SELECT * FROM pasien WHERE nama_lengkap = '$SData[nama_pasien]' LIMIT 1")->fetch_assoc();
        if ($dataPasien['ihs_id'] == '') {
            // CREATE IHS PASIEN
            $getIHSPasien = curl_init();
            curl_setopt_array($getIHSPasien, array(
                CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Patient?identifier=https%3A%2F%2Ffhir.kemkes.go.id%2Fid%2Fnik%7C' . $dataPasien['no_identitas'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token,
                ),
            ));

            $responseGetIHSPasien = curl_exec($getIHSPasien);
            curl_close($getIHSPasien);
            $getIHS = json_decode($responseGetIHSPasien, true);

            $IHSPasien = $getIHS['entry'][0]['resource']['id'];
            $koneksi->query("UPDATE pasien SET ihs_id='$IHSPasien' WHERE no_rm = '$SData[no_rm]' AND nama_lengkap = '$SData[nama_pasien]' LIMIT 1");
        } else {
            $IHSPasien = $dataPasien['ihs_id'];
        }
        // Selesai Pengecekan IHS Pasien Ada Atau Tidak

        // Pengecekan IHS Dokter Ada Atau Tidak
        $dataDokter = $koneksi->query("SELECT * FROM admin WHERE namalengkap = '$SData[dokter_rawat]' AND level = 'dokter' LIMIT 1")->fetch_assoc();
        if ($dataDokter['ihs_id'] == '') {
            $getIHSDokter = curl_init();
            curl_setopt_array($getIHSDokter, array(
                CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Practitioner?identifier=https%3A%2F%2Ffhir.kemkes.go.id%2Fid%2Fnik%7C' . $dataDokter['NIK'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token
                ),
            ));

            $responseGetIHSDokter = curl_exec($getIHSDokter);
            curl_close($getIHSDokter);
            $getIHSD = json_decode($responseGetIHSDokter, true);

            $IHSDokter = $getIHSD['entry'][0]['resource']['id'];
            $koneksi->query("UPDATE admin SET ihs_id = '$IHSDokter' WHERE namalengkap = '$SData[dokter_rawat]' AND level = 'dokter' LIMIT 1");
        } else {
            $IHSDokter = $dataDokter['ihs_id'];
        }
        // Selesai Pengecekan IHS Dokter

        // Bundle 2
        // Buat UUID untuk Ecounter, Condition, Observation, Procedure, Composition
        $ecounterBundle2UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $conditionBundle2UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(17)), 4));
        $observationBundle2UUID1 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(21)), 4));
        $observationBundle2UUID2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(22)), 4));
        $observationBundle2UUID3 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(23)), 4));
        $observationBundle2UUID4 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(24)), 4));
        $observationBundle2UUID5 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
        $procedureBundle2UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(19)), 4));
        $compositionBundle2UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(20)), 4));

        $uuide = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuid4o = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuid4o2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuid4o3 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuid4o4 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuid4o5 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuid4o6 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuid4sr = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuid4dr = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuid4sp = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));

        $uuidc = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuidp = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuidm = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuidmr = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $uuidmd = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));

        $alergyBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(24)), 4));
        $clinicalBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
        $diagnoticReportBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
        $diagnoticReportResultBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
        $diagnoticReportBaseOnBundle5UUID = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(25)), 4));
        // Selesai Buat UUID untuk Ecounter, Condition, Observation, Procedure, Composition

        // Contion Lab
        if ($SData3['JumlahDataLab'] != 0) {
            $conditionLab = ',{
                    "fullUrl": "urn:uuid:' . $uuid4sr . '",
                    "resource": {
                        "resourceType": "ServiceRequest",
                        "identifier": [
                            {
                                "system": "http://sys-ids.kemkes.go.id/servicerequest/' . $organizationId . '",
                                "value": "' . $SData['antrian'] . '"
                            }
                        ],
                        "status": "active",
                        "intent": "original-order",
                        "priority": "routine",
                        "category": [
                            {
                                "coding": [
                                    {
                                        "system": "http://snomed.info/sct",
                                        "code": "108252007",
                                        "display": "Laboratory procedure"
                                    }
                                ]
                            }
                        ],
                        "code": {
                            "coding": [
                                {
                                    "system": "http://loinc.org",
                                    "code": "20570-8",
                                    "display": "Hematocrit [Volume Fraction] of Blood"
                                }
                            ],
                            "text": "Pemeriksaan ' . $SData3['tipe_lab'] . '"
                        },
                        "subject": {
                            "reference": "Patient/' . $IHSPasien . '"
                        },
                        "encounter": {
                            "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                            "display": "Permintaan Periksa ' . $SData3['tipe_lab'] . '' . $SData3['pasienlab'] . ' di tanggal ' . $SData3['tgl'] . '"
                        },
                        "occurrenceDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                        "authoredOn": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                        "requester": {
                            "reference": "Practitioner/' . $IHSDokter . '",
                            "display": "' . $SData3['dokter_lab'] . '"
                        },
                        "performer": [
                            {
                                "reference": "Practitioner/' . $IHSDokter . '",
                                "display": "' . $SData3['dokter_lab'] . '"
                            }
                        ],
                        "reasonCode": [
                            {
                                "text": "Periksa ' . $SData3['tipe_lab'] . '"
                            }
                        ]
                    },
                    "request": {
                        "method": "POST",
                        "url": "ServiceRequest"
                    }
                },
                {
                    "fullUrl": "urn:uuid:' . $uuid4sp . '",
                    "resource": {
                        "resourceType": "Specimen",
                        "identifier": [
                            {
                                "system": "http://sys-ids.kemkes.go.id/specimen/' . $organizationId . '",
                                "value": "' . $SData['antrian'] . '",
                                "assigner": {
                                    "reference": "Organization/' . $organizationId . '"
                                }
                            }
                        ],
                        "status": "available",
                        "type": {
                            "coding": [
                                {
                                    "system": "http://snomed.info/sct",
                                    "code": "119297000",
                                    "display": "Blood specimen"
                                }
                            ]
                        },
                        "collection": {
                            "method": {
                                "coding": [
                                    {
                                        "system": "http://snomed.info/sct",
                                        "code": "713143008",
                                        "display": "Collection of arterial blood specimen"
                                    }
                                ]
                            },
                            "collectedDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                        },
                        "subject": {
                            "reference": "Patient/' . $IHSPasien . '",
                            "display": "' . $SData['nama_pasien'] . '"
                        },
                        "request": [
                            {
                                "reference": "ServiceRequest/' . $uuid4sr . '"
                            }
                        ],
                        "receivedTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                    },
                    "request": {
                        "method": "POST",
                        "url": "Specimen"
                    }
                }';
        } else {
            $conditionLab = '';
        }
        // SelesaiContion Lab


        $postBundle2 = curl_init();
        curl_setopt_array($postBundle2, array(
            CURLOPT_URL => $baseUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "resourceType": "Bundle",
                "type": "transaction",
                "entry": [
                    {
                        "fullUrl": "urn:uuid:' . $ecounterBundle2UUID . '",
                        "resource": {
                            "resourceType": "Encounter",
                            "identifier": [
                                {
                                    "system": "http://sys-ids.kemkes.go.id/encounter/' . $organizationId . '",
                                    "value": "' . $SData['antrian'] . '"
                                }
                            ],
                            "status": "finished",
                            "class": {
                                "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                                "code": "AMB",
                                "display": "ambulatory"
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '",
                                "display": "' . $SData['nama_pasien'] . '"
                            },
                            "participant": [
                                {
                                    "type": [
                                        {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                                    "code": "ATND",
                                                    "display": "attender"
                                                }
                                            ]
                                        }
                                    ],
                                    "individual": {
                                        "reference": "Practitioner/' . $IHSDokter . '",
                                        "display": "' . $SData['dokter_rawat'] . '"
                                    }
                                }
                            ],
                            "period": {
                                "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00"
                            },
                            "location": [
                                {
                                    "location": {
                                        "reference": "Location/' . $locationId . '",
                                        "display": "Ruang 1A, Poliklinik Rawat Jalan, Lantai 1"
                                    }
                                }
                            ],
                            "diagnosis": [
                                {
                                    "condition": {
                                        "reference": "urn:uuid:' . $conditionBundle2UUID . '",
                                        "display": "' . $SData2['diagnosis'] . '"
                                    },
                                    "use": {
                                        "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                                                "code": "DD",
                                                "display": "Discharge diagnosis"
                                            }
                                        ]
                                    },
                                    "rank": 1
                                }
                            ],
                            "statusHistory": [
                                {
                                    "status": "arrived",
                                    "period": {
                                        "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                        "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00"
                                    }
                                },
                                {
                                    "status": "in-progress",
                                    "period": {
                                        "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                        "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00"
                                    }
                                },
                                {
                                    "status": "finished",
                                    "period": {
                                        "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00",
                                        "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . $SData['end'] . '+00:00"
                                    }
                                }
                            ],
                            "serviceProvider": {
                                "reference": "Organization/' . $organizationId . '"
                            }
                        },
                        "request": {
                            "method": "POST",
                            "url": "Encounter"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $observationBundle2UUID1 . '",
                        "resource": {
                            "resourceType": "Observation",
                            "status": "final",
                            "category": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                            "code": "vital-signs",
                                            "display": "Vital Signs"
                                        }
                                    ]
                                }
                            ],
                            "code": {
                                "coding": [
                                    {
                                        "system": "http://loinc.org",
                                        "code": "8867-4",
                                        "display": "Heart rate"
                                    }
                                ]
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '"
                            },
                            "performer": [
                                {
                                    "reference": "Practitioner/' . $IHSDokter . '"
                                }
                            ],
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                "display": "Pemeriksaan Fisik Nadi Budi Santoso di ' . $SData['jadwal'] . '"
                            },
                            "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "valueQuantity": {
                                "value": ' . $SData2['nadi'] . ',
                                "unit": "beats/minute",
                                "system": "http://unitsofmeasure.org",
                                "code": "/min"
                            }
                        },
                        "request": {
                            "method": "POST",
                            "url": "Observation"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $observationBundle2UUID2 . '",
                        "resource": {
                            "resourceType": "Observation",
                            "status": "final",
                            "category": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                            "code": "vital-signs",
                                            "display": "Vital Signs"
                                        }
                                    ]
                                }
                            ],
                            "code": {
                                "coding": [
                                    {
                                        "system": "http://loinc.org",
                                        "code": "9279-1",
                                        "display": "Respiratory rate"
                                    }
                                ]
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '"
                            },
                            "performer": [
                                {
                                    "reference": "Practitioner/' . $IHSDokter . '"
                                }
                            ],
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                "display": "Pemeriksaan Fisik Pernafasan ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                            },
                            "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "valueQuantity": {
                                "value": ' . $SData2['frek_nafas'] . ',
                                "unit": "breaths/minute",
                                "system": "http://unitsofmeasure.org",
                                "code": "/min"
                            }
                        },
                        "request": {
                            "method": "POST",
                            "url": "Observation"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $observationBundle2UUID3 . '",
                        "resource": {
                            "resourceType": "Observation",
                            "status": "final",
                            "category": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                            "code": "vital-signs",
                                            "display": "Vital Signs"
                                        }
                                    ]
                                }
                            ],
                            "code": {
                                "coding": [
                                    {
                                        "system": "http://loinc.org",
                                        "code": "8480-6",
                                        "display": "Systolic blood pressure"
                                    }
                                ]
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '"
                            },
                            "performer": [
                                {
                                    "reference": "Practitioner/' . $IHSDokter . '"
                                }
                            ],
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                "display": "Pemeriksaan Fisik Sistolik ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                            },
                            "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "valueQuantity": {
                                "value": ' . $SData2['sistole'] . ',
                                "unit": "mm[Hg]",
                                "system": "http://unitsofmeasure.org",
                                "code": "mm[Hg]"
                            }
                        },
                        "request": {
                            "method": "POST",
                            "url": "Observation"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $observationBundle2UUID4 . '",
                        "resource": {
                            "resourceType": "Observation",
                            "status": "final",
                            "category": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                            "code": "vital-signs",
                                            "display": "Vital Signs"
                                        }
                                    ]
                                }
                            ],
                            "code": {
                                "coding": [
                                    {
                                        "system": "http://loinc.org",
                                        "code": "8462-4",
                                        "display": "Diastolic blood pressure"
                                    }
                                ]
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '",
                                "display": "' . $SData['nama_pasien'] . '"
                            },
                            "performer": [
                                {
                                    "reference": "Practitioner/' . $IHSDokter . '"
                                }
                            ],
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                "display": "Pemeriksaan Fisik Diastolik ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                            },
                            "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "bodySite": {
                                "coding": [
                                    {
                                        "system": "http://snomed.info/sct",
                                        "code": "368209003",
                                        "display": "Right arm"
                                    }
                                ]
                            },
                            "valueQuantity": {
                                "value": ' . $SData2['distole'] . ',
                                "unit": "mm[Hg]",
                                "system": "http://unitsofmeasure.org",
                                "code": "mm[Hg]"
                            }
                        },
                        "request": {
                            "method": "POST",
                            "url": "Observation"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $observationBundle2UUID5 . '",
                        "resource": {
                            "resourceType": "Observation",
                            "status": "final",
                            "category": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                            "code": "vital-signs",
                                            "display": "Vital Signs"
                                        }
                                    ]
                                }
                            ],
                            "code": {
                                "coding": [
                                    {
                                        "system": "http://loinc.org",
                                        "code": "8310-5",
                                        "display": "Body temperature"
                                    }
                                ]
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '"
                            },
                            "performer": [
                                {
                                    "reference": "Practitioner/' . $IHSDokter . '"
                                }
                            ],
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                "display": "Pemeriksaan Fisik Suhu ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                            },
                            "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "valueQuantity": {
                                "value": ' . $SData2['suhu_tubuh'] . ',
                                "unit": "C",
                                "system": "http://unitsofmeasure.org",
                                "code": "Cel"
                            }
                        },
                        "request": {
                            "method": "POST",
                            "url": "Observation"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $conditionBundle2UUID . '",
                        "resource": {
                            "resourceType": "Condition",
                            "clinicalStatus": {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/condition-clinical",
                                        "code": "active",
                                        "display": "Active"
                                    }
                                ]
                            },
                            "category": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/condition-category",
                                            "code": "encounter-diagnosis",
                                            "display": "Encounter Diagnosis"
                                        }
                                    ]
                                }
                            ],
                            "code": {
                                "coding": [
                                    {
                                        "system": "http://hl7.org/fhir/sid/icd-10",
                                        "code": "' . $SData2['icd'] . '",
                                        "display": "' . $SData2['diagnosis'] . '"
                                    }
                                ]
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '",
                                "display": "' . $SData['nama_pasien'] . '"
                            },
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '"
                            },
                            "onsetDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "recordedDate": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                        },
                        "request": {
                            "method": "POST",
                            "url": "Condition"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $procedureBundle2UUID . '",
                        "resource": {
                            "resourceType": "Procedure",
                            "status": "completed",
                            "category": {
                                "coding": [
                                    {
                                        "system": "http://snomed.info/sct",
                                        "code": "103693007",
                                        "display": "Diagnostic procedure"
                                    }
                                ],
                                "text": "Diagnostic procedure"
                            },
                            "code": {
                                "coding": [
                                    {
                                        "system": "http://hl7.org/fhir/sid/icd-9-cm",
                                        "code": "89.7",
                                        "display": "General physical examination"
                                    }
                                ]
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '",
                                "display": "' . $SData['nama_pasien'] . '"
                            },
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                "display": "Tindakan Rontgen Dada ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                            },
                            "performedPeriod": {
                                "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                                "end": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                            },
                            "performer": [
                                {
                                    "actor": {
                                        "reference": "Practitioner/' . $IHSDokter . '",
                                        "display": "' . $SData['dokter_rawat'] . '"
                                    }
                                }
                            ],
                            "reasonCode": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://hl7.org/fhir/sid/icd-10",
                                            "code": "' . $SData2['icd'] . '",
                                            "display": "' . $SData2['diagnosis'] . '"
                                        }
                                    ]
                                }
                            ],
                            "note": [
                                {
                                    "text": "Pemeriksaan Medis dan Fisik Umum"
                                }
                            ]
                        },
                        "request": {
                            "method": "POST",
                            "url": "Procedure"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $compositionBundle2UUID . '",
                        "resource": {
                            "resourceType": "Composition",
                            "identifier": {
                                "system": "http://sys-ids.kemkes.go.id/composition/' . $organizationId . '",
                                "value": "' . $SData['antrian'] . '"
                            },
                            "status": "final",
                            "type": {
                                "coding": [
                                    {
                                        "system": "http://loinc.org",
                                        "code": "18842-5",
                                        "display": "Discharge summary"
                                    }
                                ]
                            },
                            "category": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://loinc.org",
                                            "code": "LP173421-1",
                                            "display": "Report"
                                        }
                                    ]
                                }
                            ],
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '",
                                "display": "' . $SData['nama_pasien'] . '"
                            },
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                "display": "Kunjungan ' . $SData['nama_pasien'] . ' di ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                            },
                            "date": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "author": [
                                {
                                    "reference": "Practitioner/' . $IHSDokter . '",
                                    "display": "' . $SData['dokter_rawat'] . '"
                                }
                            ],
                            "title": "Resume Medis Rawat Jalan",
                            "custodian": {
                                "reference": "Organization/' . $organizationId . '"
                            },
                            "section": [
                                {
                                    "code": {
                                        "coding": [
                                            {
                                                "system": "http://loinc.org",
                                                "code": "42344-2",
                                                "display": "Discharge diet (narrative)"
                                            }
                                        ]
                                    },
                                    "text": {
                                        "status": "additional",
                                        "div": "Rekomendasi diet rendah lemak, rendah kalori"
                                    }
                                }
                            ]
                        },
                        "request": {
                            "method": "POST",
                            "url": "Composition"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $alergyBundle5UUID . '",
                        "resource": {
                            "resourceType": "AllergyIntolerance",
                            "identifier": [
                                {
                                    "system": "http://sys-ids.kemkes.go.id/allergy/' . $organizationId . '",
                                    "use": "official",
                                    "value": "98457729' . $alergyBundle5UUID . '"
                                }
                            ],
                            "clinicalStatus": {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical",
                                        "code": "active",
                                        "display": "Active"
                                    }
                                ]
                            },
                            "verificationStatus": {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/allergyintolerance-verification",
                                        "code": "confirmed",
                                        "display": "Confirmed"
                                    }
                                ]
                            },
                            "category": [
                                "food"
                            ],
                            "code": {
                                "coding": [
                                    {
                                        "system": "http://snomed.info/sct",
                                        "code": "716186003",
                                        "display": "No known allergy"
                                    }
                                ],
                                "text": "Alergi Tidak Diketahui"
                            },
                            "patient": {
                                "reference": "Patient/' . $IHSPasien . '",
                                "display": "' . $SData['nama_pasien'] . '"
                            },
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                "display": "Kunjungan ' . $SData['nama_pasien'] . ' di tanggal ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                            },
                            "recordedDate": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "recorder": {
                                "reference": "Practitioner/' . $IHSDokter . '"
                            }
                        },
                        "request": {
                            "method": "POST",
                            "url": "AllergyIntolerance"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $clinicalBundle5UUID . '",
                        "resource": {
                            "resourceType": "ClinicalImpression",
                            "identifier": [
                                {
                                    "system": "http://sys-ids.kemkes.go.id/clinicalimpression/' . $organizationId . '",
                                    "use": "official",
                                    "value": "Prognosis_' . $clinicalBundle5UUID . '"
                                }
                            ],
                            "status": "completed",
                            "description": "' . $SData['nama_pasien'] . ' terdiagnosa ' . $SData2['diagnosis'] . '",
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '",
                                "display": "' . $SData['nama_pasien'] . '"
                            },
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '",
                                "display": "Kunjungan ' . $SData['nama_pasien'] . ' di tanggal ' . date("d J Y", strtotime($SData['jadwal'])) . '"
                            },
                            "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "date": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "assessor": {
                                "reference": "Practitioner/' . $IHSDokter . '"
                            },
                            "problem": [
                                {
                                    "reference": "urn:uuid:' . $uuidc . '"
                                }
                            ],
                            "investigation": [
                                {
                                    "code": {
                                        "text": "Pemeriksaan"
                                    },
                                    "item": [
                                        {
                                            "reference": "urn:uuid:' . $uuid4dr . '"
                                        }
                                    ]
                                }
                            ],
                            "summary": "Prognosis terhadap gejala klinis",
                            "finding": [
                                {
                                    "itemCodeableConcept": {
                                        "coding": [
                                            {
                                                "system": "http://hl7.org/fhir/sid/icd-10",
                                                "code": "' . $SData2['icd'] . '",
                                                "display": "' . $SData2['prognosa'] . '"
                                            }
                                        ]
                                    },
                                    "itemReference": {
                                        "reference": "urn:uuid:' . $uuidc . '"
                                    }
                                }
                            ],
                            "prognosisCodeableConcept": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://snomed.info/sct",
                                            "code": "' . $SData2['kode_prognosa'] . '",
                                            "display": "' . $SData2['prognosa'] . '"
                                        }
                                    ]
                                }
                            ]
                        },
                        "request": {
                            "method": "POST",
                            "url": "ClinicalImpression"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $uuidm . '",
                        "resource": {
                            "resourceType": "Medication",
                            "meta": {
                                "profile": [
                                    "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
                                ]
                            },
                            "identifier": [
                                {
                                    "system": "http://sys-ids.kemkes.go.id/medication/' . $organizationId . '",
                                    "use": "official",
                                    "value": "123456789-AB"
                                }
                            ],
                            "code": {
                                ' . $stringObat . '
                            },
                            "status": "active",
                            "manufacturer": {
                                "reference": "Organization/' . $organizationId . '"
                            },
                            "extension": [
                                {
                                    "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
                                    "valueCodeableConcept": {
                                        "coding": [
                                            {
                                                "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                                                "code": "NC",
                                                "display": "Non-compound"
                                            }
                                        ]
                                    }
                                }
                            ]
                        },
                        "request": {
                            "method": "POST",
                            "url": "Medication"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $uuidmr . '",
                        "resource": {
                            "resourceType": "MedicationRequest",
                            "identifier": [
                                {
                                    "system": "http://sys-ids.kemkes.go.id/prescription/' . $organizationId . '",
                                    "use": "official",
                                    "value": "123456788-A"
                                },
                                {
                                    "system": "http://sys-ids.kemkes.go.id/prescription-item/' . $organizationId . '",
                                    "use": "official",
                                    "value": "123456788-1"
                                }
                            ],
                            "status": "completed",
                            "intent": "order",
                            "category": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-category",
                                            "code": "outpatient",
                                            "display": "Outpatient"
                                        }
                                    ]
                                }
                            ],
                            "priority": "routine",
                            "medicationReference": {
                                "reference": "Medication/' . $uuidm . '",
                                "display": "' . $stringNameObat . '"
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '",
                                "display": "' . $SData['nama_pasien'] . '"
                            },
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '"
                            },
                            "authoredOn": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "requester": {
                                "reference": "Practitioner/' . $IHSDokter . '",
                                "display": "' . $SData['dokter_rawat'] . '"
                            },
                            "reasonCode": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://hl7.org/fhir/sid/icd-10",
                                            "code": "' . $SData2['icd'] . '",
                                            "display": "' . $SData2['diagnosis'] . '"
                                        }
                                    ]
                                }
                            ],
                            "courseOfTherapyType": {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-course-of-therapy",
                                        "code": "continuous",
                                        "display": "Continuing long term therapy"
                                    }
                                ]
                            },
                            "dispenseRequest": {
                                "dispenseInterval": {
                                    "value": 1,
                                    "unit": "days",
                                    "system": "http://unitsofmeasure.org",
                                    "code": "d"
                                },
                                "validityPeriod": {
                                    "start": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T00:00:00+00:00",
                                    "end": "' . date("Y-m-d", strtotime('+14 days', strtotime($SData['jadwal']))) . 'T00:00:00+00:00"
                                    
                                },
                                "performer": {
                                    "reference": "Organization/' . $organizationId . '"
                                }
                            }
                        },
                        "request": {
                            "method": "POST",
                            "url": "MedicationRequest"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $uuidmd . '",
                        "resource": {
                            "resourceType": "MedicationDispense",
                            "identifier": [
                                {
                                    "system": "http://sys-ids.kemkes.go.id/prescription/' . $organizationId . '",
                                    "use": "official",
                                    "value": "123456788-AB"
                                },
                                {
                                    "system": "http://sys-ids.kemkes.go.id/prescription-item/' . $organizationId . '",
                                    "use": "official",
                                    "value": "123456788-1"
                                }
                            ],
                            "status": "completed",
                            "category": {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category",
                                        "code": "outpatient",
                                        "display": "Outpatient"
                                    }
                                ]
                            },
                            "medicationReference": {
                                "reference": "Medication/' . $uuidm . '",
                                "display": "' . $stringNameObat . '"
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '",
                                "display": "' . $SData['nama_pasien'] . '"
                            },
                            "context": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '"
                            },
                            "performer": [
                                {
                                    "actor": {
                                        "reference": "Practitioner/' . $IHSDokter . '",
                                        "display": "' . $SData['dokter_rawat'] . '"
                                    }
                                }
                            ],
                            "location": {
                                "reference": "Location/' . $locationId . '",
                                "display": "Apotek KHM Wonorejo"
                            },
                            "authorizingPrescription": [
                                {
                                    "reference": "MedicationRequest/' . $uuidmr . '"
                                }
                            ],
                            "whenPrepared": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "whenHandedOver": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00"
                        },
                        "request": {
                            "method": "POST",
                            "url": "MedicationDispense"
                        }
                    },
                    {
                        "fullUrl": "urn:uuid:' . $diagnoticReportBundle5UUID . '",
                        "resource": {
                            "resourceType": "DiagnosticReport",
                            "identifier": [
                                {
                                    "system": "http://sys-ids.kemkes.go.id/diagnostic/' . $organizationId . '/lab",
                                    "use": "official",
                                    "value": "5234342' . $diagnoticReportBundle5UUID . '"
                                }
                            ],
                            "status": "final",
                            "category": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/v2-0074",
                                            "code": "MB",
                                            "display": "Microbiology"
                                        }
                                    ]
                                }
                            ],
                            "code": {
                                "coding": [
                                    {
                                        "system": "http://loinc.org",
                                        "code": "20570-8",
                                        "display": "Hematocrit [Volume Fraction] of Blood"
                                    }
                                ]
                            },
                            "subject": {
                                "reference": "Patient/' . $IHSPasien . '"
                            },
                            "encounter": {
                                "reference": "urn:uuid:' . $ecounterBundle2UUID . '"
                            },
                            "effectiveDateTime": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "issued": "' . date("Y-m-d", strtotime($SData['jadwal'])) . 'T' . date("h:i:s", strtotime($SData['jadwal'])) . '+00:00",
                            "performer": [
                                {
                                    "reference": "Practitioner/' . $IHSDokter . '"
                                },
                                {
                                    "reference": "Organization/' . $organizationId . '"
                                }
                            ],
                            "result": [
                                {
                                    "reference": "urn:uuid:' . $diagnoticReportResultBundle5UUID . '"
                                }
                            ],
                            "specimen": [
                                {
                                    "reference": "urn:uuid:' . $uuid4sp . '"
                                }
                            ],
                            "basedOn": [
                                {
                                    "reference": "urn:uuid:' . $diagnoticReportBaseOnBundle5UUID . '"
                                }
                            ],
                            "conclusionCode": [
                                {
                                    "coding": [
                                        {
                                            "system": "http://snomed.info/sct",
                                            "code": "260347006",
                                            "display": "+"
                                        }
                                    ]
                                }
                            ]
                        },
                        "request": {
                            "method": "POST",
                            "url": "DiagnosticReport"
                        }
                    }' . $conditionLab . '
                ]
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token . ''
            ),
        ));

        $responsePostBundle2 = curl_exec($postBundle2);

        curl_close($postBundle2);
        echo "<pre>";
        print_r($responsePostBundle2);
        $responseJSONBundle2 = json_decode($responsePostBundle2, true);
        echo "<pre>";
        // Selesai Bundle 2


        if ($responseJSONBundle2['resourceType'] === "OperationOutcome") {
            $koneksi->query("UPDATE registrasi_rawat SET status_sinc = 'Gagal' WHERE idrawat = '$SData[idrawat]'");
            if (!isset($_GET['page'])) {
                echo "
                        <script>
                            alert('Sinkronisasi Gagal! Mohon cek NIK pasien kembali sengan benar.');
                            document.location.href='$urlPage';
                        </script>
                    ";
            } else {
                echo "
                        <script>
                            alert('Sinkronisasi Gagal! Mohon cek NIK pasien kembali sengan benar.');
                            document.location.href='$urlPage&page=$_GET[page]';
                        </script>
                    ";
            }
        } elseif ($responseJSONBundle2['resourceType'] === "Bundle" && $responseJSONBundle2['type'] === "transaction-response") {
            $koneksi->query("UPDATE registrasi_rawat SET status_sinc = 'Berhasil' WHERE idrawat = '$SData[idrawat]'");
            if (!isset($_GET['page'])) {
                echo "
                        <script>
                            alert('Sinkronisasi Berhasil');
                            document.location.href='$urlPage';
                        </script>
                    ";
            } else {
                echo "
                        <script>
                            alert('Sinkronisasi Berhasil');
                            document.location.href='$urlPage&page=$_GET[page]';
                        </script>
                    ";
            }
        }
    }
    ?>