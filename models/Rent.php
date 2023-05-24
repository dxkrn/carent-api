<?php

require_once('../helper.php');

class Rent
{

    public  function get_rents()
    {
        global $mysqli;
        $query = "SELECT * FROM rents 
                    JOIN members
                    USING(id_user) 
                    WHERE status = 'ongoing'
                    ORDER BY(id_peminjaman)";
        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        $response = array(
            'status' => 1,
            'message' => 'Get List Rents Successfully.',
            'data' => $data
        );
        echo json_encode($response);
    }

    public function get_rent($id)
    {
        global $mysqli;
        $query = "SELECT * FROM rents 
        JOIN members
        USING(id_user) 
        WHERE id_peminjaman = '$id' 
        AND status = 'ongoing'
        ORDER BY(id_peminjaman)";

        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }

        if (!empty($data)) {
            $response = array(
                'status' => 1,
                'message' => 'Get Rent ' . $id . ' Successfully.',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 404,
                'message' => 'Rent ' . $id . ' Not Found.',
            );
        }

        echo json_encode($response);
    }

    public function generate_id()
    {
        global $mysqli;
        $query = "SELECT MAX(id_peminjaman) as last_id FROM rents";
        $result = $mysqli->query($query);
        $data = mysqli_fetch_array($result);
        $id = $data['last_id'];
        $order = (int) substr($id, 4, 4);
        $order++;
        $id = "RN" . sprintf("%04s", $order);
        return $id;
    }

    public function insert_rent()
    {
        global $mysqli;
        $id_peminjaman = $this->generate_id();
        $arrcheckpost = array('id_user' => '', 'tgl_sewa' => '');
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $result = mysqli_query(
                $mysqli,
                "INSERT INTO rents SET 
                id_peminjaman = '$id_peminjaman',
                id_user = '$_POST[id_user]',
                tgl_sewa = '$_POST[tgl_sewa]'
                "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'Rent ' . $id_peminjaman . ' Added Successfully.',
                    'id' => $id_peminjaman,
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Rent ' . $id_peminjaman . ' Added Failed.'
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
    public function update_rent($id)
    {
        global $mysqli;
        $arrcheckpost = array('id_user' => '', 'tgl_sewa' => '', 'status' => '');
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $result = mysqli_query(
                $mysqli,
                "UPDATE rents SET
                    id_user = '$_POST[id_user]',
                    tgl_sewa = '$_POST[tgl_sewa]',
                    status = '$_POST[status]'
                    WHERE id_peminjaman='$id'
               "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'Rent ' . $id . ' Updated Successfully.'
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Rent ' . $id . ' Update Failed.'
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

    function delete_rent($id)
    {
        global $mysqli;

        // NOTE: ambil data id_produk
        $getProductsQuery = "SELECT id_produk FROM rent_details WHERE id_peminjaman='$id'";
        $product = '';
        $getProductsResult = $mysqli->query($getProductsQuery);
        while ($row = mysqli_fetch_array($getProductsResult)) {
            $product = (string)$row['id_produk'];

            //NOTE: untuk cek stock
            $stock = [];
            $newStock = 0;
            $cekStockQuery = "SELECT stock FROM products WHERE id_produk='$product'";
            $result = $mysqli->query($cekStockQuery);
            while ($row = mysqli_fetch_array($result)) {
                $stock[] = $row;
            }

            //NOTE: untuk cek jumlah
            $jml = [];
            $cekStockQuery = "SELECT jumlah FROM rent_details WHERE id_peminjaman='$id' AND id_produk='$product'";
            $result = $mysqli->query($cekStockQuery);
            while ($row = mysqli_fetch_array($result)) {
                $jml[] = $row;
            }

            //NOTE: hitung stock baru
            $newStock = (int)$stock[0]['stock'] + (int)$jml[0]['jumlah'];

            //NOTE: update stock
            $updateStock = mysqli_query($mysqli, "UPDATE products SET stock = '$newStock' WHERE id_produk = '$product'");
        }


        // NOTE: delete data rent
        $query = "DELETE FROM rents WHERE id_peminjaman='$id'";

        if (mysqli_query($mysqli, $query)) {

            $response = array(
                'status' => 1,
                'message' => 'Rent ' . $id . ' Deleted Successfully.',
            );
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Rent ' . $id . ' Delete Failed.'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
