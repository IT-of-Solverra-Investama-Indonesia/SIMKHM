<?php

    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    // include 'function.php';

    $getToken = sani($_GET['token'] ?? $_POST['token']);
    $lengthToKeep = strlen($getToken) - 40;
    $token = sani(substr($getToken, 0, $lengthToKeep));

    if (isset($_GET['getKeranjangUser'])) {
        $user_id = sani($token);
        $stmt = $koneksi->prepare("SELECT * FROM cart_kosmetik JOIN produk_kosmetik ON cart_kosmetik.produk_id = produk_kosmetik.id_produk WHERE cart_kosmetik.user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = array();
            while ($hasil = $result->fetch_assoc()) {
                $data[] = $hasil;
            }
            $response = array(
                "status" => "Successfully",
                "data" => $data
            );
        } else {
            $response = array(
                "status" => "Gagal, hehehe",
                "data" => "Data Kosong"
            );
        }
        $stmt->close();
    }

    if (isset($_POST['addKeranjang'])) {
        $idProduk = sani($_POST['idProduk']);
        $stmtProd = $koneksi->prepare("SELECT * FROM produk_kosmetik WHERE id_produk = ?");
        $stmtProd->bind_param("s", $idProduk);
        $stmtProd->execute();
        $getProd = $stmtProd->get_result()->fetch_assoc();
        $stmtProd->close();

        $user_id = sani($token);
        $username = sani($_SESSION['kosmetik']['nama_lengkap']);
        $produk_id = sani($getProd['id_produk']);
        $produk = sani($getProd['nama_produk']);
        $harga = sani($getProd['harga']);
        $diskon = sani($getProd['diskon']);
        $jumlah = sani('1');
        $sub_harga = sani(1 * $getProd['harga']);

        $stmtInsert = $koneksi->prepare("INSERT INTO cart_kosmetik (user_id, username, produk_id, produk, harga, diskon, jumlah, sub_harga) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtInsert->bind_param("ssssssss", $user_id, $username, $produk_id, $produk, $harga, $diskon, $jumlah, $sub_harga);
        $stmtInsert->execute();
        $stmtInsert->close();

        $stmtKeranjang = $koneksi->prepare("SELECT * FROM cart_kosmetik WHERE user_id = ?");
        $stmtKeranjang->bind_param("s", $user_id);
        $stmtKeranjang->execute();
        $getKeranjang = $stmtKeranjang->get_result();
        $data = array();
        while ($hasil = $getKeranjang->fetch_assoc()) {
            $data[] = $hasil;
        }
        $response = array(
            "status" => "Successfully",
            "data" => $data
        );
        $stmtKeranjang->close();
    }

    if (isset($_POST['updateJumlah'])) {
        $jumlah = sani($_POST['jumlah']);
        $id = sani($_POST['id']);
        $user_id = sani($token);

        $stmtUpdate = $koneksi->prepare("UPDATE cart_kosmetik SET jumlah = ? WHERE id = ? AND user_id = ?");
        $stmtUpdate->bind_param("sss", $jumlah, $id, $user_id);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        $stmtSelect = $koneksi->prepare("SELECT * FROM cart_kosmetik WHERE id = ? AND user_id = ?");
        $stmtSelect->bind_param("ss", $id, $user_id);
        $stmtSelect->execute();
        $result = $stmtSelect->get_result();
        $data = array();
        while ($hasil = $result->fetch_assoc()) {
            $data[] = $hasil;
        }
        $response = array(
            "status" => "Successfully",
            "data" => $data
        );
        $stmtSelect->close();
    }

    if (isset($_GET['delKeranjang'])) {
        $id = sani($_POST['idCart']);
        $stmtDel = $koneksi->prepare("DELETE FROM cart_kosmetik WHERE id = ?");
        $stmtDel->bind_param("s", $id);
        $stmtDel->execute();
        $stmtDel->close();
        $response = array(
            "status" => "Successfully",
            "data" => ""
        );
    }

    if (isset($_POST['checkout'])) {
        $user_id = sani($token);
        $stmtRegist = $koneksi->prepare("SELECT * FROM pasien_kosmetik WHERE idpasien = ?");
        $stmtRegist->bind_param("s", $user_id);
        $stmtRegist->execute();
        $getRegist = $stmtRegist->get_result()->fetch_assoc();
        $stmtRegist->close();

        if (
            sani($getRegist['provinsi']) == '' ||
            sani($getRegist['kabupaten']) == '' ||
            sani($getRegist['kelurahan']) == '' ||
            sani($getRegist['alamat']) == ''
        ) {
            $status = "Unsuccessfully";
            $data = 'Silahlan Melengkapi Data Diri (Registrasi 3)';
        } else {
            $stmtCart = $koneksi->prepare("SELECT cart_kosmetik.*, cart_kosmetik.harga as hargaa, produk_kosmetik.* FROM cart_kosmetik JOIN produk_kosmetik ON cart_kosmetik.produk_id = produk_kosmetik.id_produk WHERE cart_kosmetik.user_id = ?");
            $stmtCart->bind_param("s", $user_id);
            $stmtCart->execute();
            $getCart = $stmtCart->get_result();

            $stmtUser = $koneksi->prepare("SELECT * FROM pasien_kosmetik WHERE idpasien = ? LIMIT 1");
            $stmtUser->bind_param("s", $user_id);
            $stmtUser->execute();
            $getUser = $stmtUser->get_result()->fetch_assoc();
            $stmtUser->close();

            $code_nota = sani(date('dmYHis') . $getUser['idpasien']);
            $username = sani($getUser['nama_lengkap']);
            $nama_lengkap = sani($_POST['nama_lengkap']);
            $alamat_lengkap = sani($_POST['alamat_lengkap']);
            $no_telp = sani($_POST['no_telp']);

            foreach ($getCart as $dataCart) {
                $produk_id = sani($dataCart['id_produk']);
                $produk = sani($dataCart['nama_produk']);
                $harga = sani($dataCart['hargaa']);
                $jumlah = sani($dataCart['jumlah']);
                $sub_harga = sani($dataCart['harga'] * $dataCart['jumlah'] - (($dataCart['harga'] * $dataCart['jumlah']) * $dataCart['diskon'] / 100));

                $stmtInsertOrder = $koneksi->prepare("INSERT INTO pemesanan (code_nota, user_id, username, nama_lengkap, alamat_lengkap, no_telp, produk_id, produk, harga, jumlah, sub_harga, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Menunggu_pembayaran')");
                $stmtInsertOrder->bind_param("sssssssssss", $code_nota, $user_id, $username, $nama_lengkap, $alamat_lengkap, $no_telp, $produk_id, $produk, $harga, $jumlah, $sub_harga);
                $stmtInsertOrder->execute();
                $stmtInsertOrder->close();
            }
            $stmtCart->close();

            $stmtDeleteCart = $koneksi->prepare("DELETE FROM cart_kosmetik WHERE user_id = ?");
            $stmtDeleteCart->bind_param("s", $user_id);
            $stmtDeleteCart->execute();
            $stmtDeleteCart->close();

            $status = "Successfully";
            $data = "Berhasil";
        }
        $response = array(
            "status" => $status,
            "data" => $data
        );
    }

    echo json_encode($response);
?>