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

                    $response = array(
                        'status' => 1,
                        'message' => 'RentReturn ' . $_POST['id_peminjaman'] . ' Added Successfully.'
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
        $query = "DELETE FROM rent_returns WHERE id_peminjaman='$id'";
        if (mysqli_query($mysqli, $query)) {
            $ubahStatus = mysqli_query($mysqli, "UPDATE rents SET status = 'ongoing' WHERE id_peminjaman = '$id'");
            if ($ubahStatus) {
                $response = array(
                    'status' => 1,
                    'message' => 'RentReturn ' . $id . ' Deleted Successfully.'
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
