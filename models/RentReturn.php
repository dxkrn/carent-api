<?php

require_once('../helper.php');

class RentReturn
{

    public  function get_rent_returns()
    {
        global $mysqli;
        $query = "SELECT * FROM rents 
                    JOIN members
                    USING(id_user) 
                    WHERE status = 'done'
                    ORDER BY(id_peminjaman)";
        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        $response = array(
            'status' => 1,
            'message' => 'Get List RentReturns Successfully.',
            'data' => $data
        );
        echo json_encode($response);
    }

    public function get_rent_return($id)
    {
        global $mysqli;
        $query = "SELECT * FROM rent_returns WHERE id_peminjaman='$id' LIMIT 1";

        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }

        if (!empty($data)) {
            $response = array(
                'status' => 1,
                'message' => 'Get RentReturn ' . $id . ' Successfully.',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 404,
                'message' => 'RentReturn ' . $id . ' Not Found.',
            );
        }

        echo json_encode($response);
    }

    public function insert_rent_return()
    {
        global $mysqli;

        $arrcheckpost = array(
            'id_peminjaman' => '',
            'tgl_kembali' => '',
            'denda' => ''
        );
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $result = mysqli_query(
                $mysqli,
                "INSERT INTO rent_returns SET 
                id_peminjaman = '$_POST[id_peminjaman]',
                tgl_kembali = '$_POST[tgl_kembali]',
                denda = '$_POST[denda]'
                "
            );

            if ($result) {
                $ubahStatus = mysqli_query($mysqli, "UPDATE rents SET status = 'done' WHERE id_peminjaman = '$_POST[id_peminjaman]'");
                if ($ubahStatus) {

                    // NOTE: Update stock
                    $getProductsQuery = "SELECT id_produk FROM rent_details WHERE id_peminjaman='$_POST[id_peminjaman]'";
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
                        $cekStockQuery = "SELECT jumlah FROM rent_details WHERE id_peminjaman='$_POST[id_peminjaman]' AND id_produk='$product'";
                        $result = $mysqli->query($cekStockQuery);
                        while ($row = mysqli_fetch_array($result)) {
                            $jml[] = $row;
                        }

                        //NOTE: hitung stock baru
                        $newStock = (int)$stock[0]['stock'] + (int)$jml[0]['jumlah'];

                        //NOTE: update stock
                        $updateStock = mysqli_query($mysqli, "UPDATE products SET stock = '$newStock' WHERE id_produk = '$product'");
                    }

                    $response = array(
                        'status' => 1,
                        'message' => 'RentReturn ' . $_POST['id_peminjaman'] . ' Added Successfully.',
                    );
                }
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'RentReturn ' . $_POST['id_peminjaman'] . ' Added Failed.'
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
    public function update_rent_return($id)
    {
        global $mysqli;
        $arrcheckpost = array(
            'tgl_kembali' => '',
            'denda' => ''
        );
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $result = mysqli_query(
                $mysqli,
                "UPDATE rent_returns SET
                    tgl_kembali = '$_POST[tgl_kembali]',
                    denda = '$_POST[denda]'
                    WHERE id_peminjaman='$id'
               "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'RentReturn ' . $id . ' Updated Successfully.'
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'RentReturn ' . $id . ' Update Failed.'
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

    function delete_rent_return($id)
    {
        global $mysqli;



        // NOTE: Update stock
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
            $newStock = (int)$stock[0]['stock'] - (int)$jml[0]['jumlah'];

            //NOTE: update stock
            $updateStock = mysqli_query($mysqli, "UPDATE products SET stock = '$newStock' WHERE id_produk = '$product'");
        }

        // NOTE: Delete data dari returns
        $query = "DELETE FROM rent_returns WHERE id_peminjaman='$id'";
        if (mysqli_query($mysqli, $query)) {
            $ubahStatus = mysqli_query($mysqli, "UPDATE rents SET status = 'ongoing' WHERE id_peminjaman = '$id'");
            if ($ubahStatus) {
                $response = array(
                    'status' => 1,
                    'message' => 'RentReturn ' . $id . ' Deleted Successfully.',

                );
            }
        } else {
            $response = array(
                'status' => 0,
                'message' => 'RentReturn ' . $id . ' Delete Failed.'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
