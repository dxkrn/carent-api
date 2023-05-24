<?php

require_once('../helper.php');

class RentDetail
{

    public  function get_rent_details()
    {
        global $mysqli;
        $query = "SELECT * FROM rent_details";
        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        $response = array(
            'status' => 1,
            'message' => 'Get List RentDetails Successfully.',
            'data' => $data
        );
        echo json_encode($response);
    }

    public function get_rent_detail($id)
    {
        global $mysqli;
        $query = "SELECT id_peminjaman, id_produk, nama_produk, harga_sewa, jumlah, durasi, (harga_sewa * jumlah * durasi) AS total
                    FROM rent_details
                    JOIN products
                    USING(id_produk)
                    WHERE id_peminjaman='$id'";

        $resultMinDurasi = mysqli_query($mysqli, "SELECT MIN(durasi) AS min_durasi FROM rent_details WHERE id_peminjaman='$id'");
        $min_durasi = mysqli_fetch_array($resultMinDurasi);

        $grandTotal = 0;

        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_array($result)) {
            $data[] = $row;
        }

        for ($i = 0; $i < sizeof($data); $i++) {
            $grandTotal += (int)$data[$i]['total'];
        }

        if (!empty($data)) {
            $response = array(
                'status' => 1,
                'message' => 'Get RentDetail ' . $id . ' Successfully.',
                'data' => $data,
                'grandTotal' => $grandTotal,
                'min_durasi' => (int)$min_durasi['min_durasi'],
            );
        } else {
            $response = array(
                'status' => 404,
                'message' => 'RentDetail ' . $id . ' Not Found.',
            );
        }

        echo json_encode($response);
    }

    public function insert_rent_detail()
    {
        global $mysqli;
        $arrcheckpost = array(
            'id_peminjaman' => '',
            'id_produk' => '',
            'durasi' => '',
            'jumlah' => ''
        );
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            // $result = mysqli_query(
            //     $mysqli,
            //     "INSERT INTO rent_details SET 
            //     id_peminjaman = '$_POST[id_peminjaman]',
            //     id_produk = '$_POST[id_produk]',
            //     durasi = '$_POST[durasi]',
            //     jumlah = '$_POST[jumlah]'
            //     "
            // );

            //NOTE: untuk cek stock
            $stock = [];
            $newStock = 0;
            $cekStockQuery = "SELECT stock FROM products WHERE id_produk='$_POST[id_produk]'";
            $result = $mysqli->query($cekStockQuery);
            while ($row = mysqli_fetch_array($result)) {
                $stock[] = $row;
            }
            //NOTE: hitung stock baru
            $newStock = (int)$stock[0]['stock'] - (int)$_POST['jumlah'];

            if ($newStock >= 0) {
                $result = mysqli_query(
                    $mysqli,
                    "INSERT INTO rent_details SET 
                    id_peminjaman = '$_POST[id_peminjaman]',
                    id_produk = '$_POST[id_produk]',
                    durasi = '$_POST[durasi]',
                    jumlah = '$_POST[jumlah]'
                    "
                );
                if ($result) {
                    $updateStock = mysqli_query($mysqli, "UPDATE products SET stock = '$newStock' WHERE id_produk = '$_POST[id_produk]'");
                    $response = array(
                        'status' => 1,
                        'message' => 'RentDetail ' . $_POST['id_peminjaman'] . '=>' . $_POST['id_produk'] . ' Added Successfully.'
                    );
                } else {
                    $response = array(
                        'status' => 0,
                        'message' => 'RentDetail ' . $_POST['id_peminjaman'] . '=>' . $_POST['id_produk'] . ' Added Failed.'
                    );
                }
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Stock produk ' . $_POST['id_produk'] . ' tidak cukup!'
                );
            }
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Parameter Do Not Match'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public function update_rent_detail($id_peminjaman, $id_produk)
    {
        global $mysqli;
        $arrcheckpost = array(
            'durasi' => '',
            'jumlah' => ''
        );
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $result = mysqli_query(
                $mysqli,
                "UPDATE rent_details SET
                    durasi = '$_POST[durasi]',
                    jumlah = '$_POST[jumlah]'
                    WHERE id_peminjaman='$id_peminjaman'
                    AND id_produk='$id_produk'
               "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'RentDetail ' . $id_peminjaman . '=>' . $id_produk . ' Updated Successfully.'
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'RentDetail ' . $id_peminjaman . '=>' . $id_produk . ' Update Failed.'
                );
            }
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Parameter Do Not Match'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function delete_rent_detail($id_peminjaman, $id_produk, $jml_produk)
    {
        global $mysqli;


        //NOTE: untuk cek stock
        $stock = [];
        $newStock = 0;
        $cekStockQuery = "SELECT stock FROM products WHERE id_produk='$id_produk'";
        $result = $mysqli->query($cekStockQuery);
        while ($row = mysqli_fetch_array($result)) {
            $stock[] = $row;
        }
        //NOTE: hitung stock baru
        $newStock = (int)$stock[0]['stock'] + (int)$jml_produk;



        $query = "DELETE FROM rent_details WHERE id_peminjaman='$id_peminjaman' AND id_produk='$id_produk'";
        if (mysqli_query($mysqli, $query)) {
            $updateStock = mysqli_query($mysqli, "UPDATE products SET stock = '$newStock' WHERE id_produk = '$id_produk'");
            $response = array(
                'status' => 1,
                'message' => 'RentDetail ' . $id_peminjaman . '=>' . $id_produk . ' Deleted Successfully.'
            );
        } else {
            $response = array(
                'status' => 0,
                'message' => 'RentDetail ' . $id_peminjaman . '=>' . $id_produk . ' Delete Failed.'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
