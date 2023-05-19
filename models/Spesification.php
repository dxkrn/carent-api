<?php

require_once('../helper.php');

class Spesification
{

    public  function get_spesifications()
    {
        global $mysqli;
        $query = "SELECT * FROM spesifications";
        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        $response = array(
            'status' => 1,
            'message' => 'Get List Spesifications Successfully.',
            'data' => $data
        );
        echo json_encode($response);
    }

    public function get_spesification($id)
    {
        global $mysqli;
        $query = "SELECT * FROM spesifications WHERE id_produk='$id' LIMIT 1";

        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }

        if (!empty($data)) {
            $response = array(
                'status' => 1,
                'message' => 'Get Spesification of ' . $id . ' Successfully.',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 404,
                'message' => 'Spesification of ' . $id . ' Not Found.',
            );
        }

        echo json_encode($response);
    }

    public function insert_spesification()
    {
        global $mysqli;
        $arrcheckpost = array(
            'id_produk' => '',
            'resolusi_gambar' => '',
            'resolusi_video' => '',
            'iso_max' => '',
            'shutter_speed' => '',
            'fitur' => ''
        );
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $id_product = $_POST['id_produk'];

            $result = mysqli_query(
                $mysqli,
                "INSERT INTO spesifications SET 
                id_produk = '$_POST[id_produk]',
                resolusi_gambar = '$_POST[resolusi_gambar]',
                resolusi_video = '$_POST[resolusi_video]',
                iso_max = '$_POST[iso_max]',
                shutter_speed = '$_POST[shutter_speed]',
                fitur = '$_POST[fitur]'
                "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'Spesification of ' . $id_product . ' Added Successfully.'
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Spesification of ' . $id_product . ' Added Failed.'
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
    public function update_spesification($id)
    {
        global $mysqli;
        $arrcheckpost = array(
            'resolusi_gambar' => '',
            'resolusi_video' => '',
            'iso_max' => '',
            'shutter_speed' => '',
            'fitur' => ''
        );
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $result = mysqli_query(
                $mysqli,
                "UPDATE spesifications SET
                    resolusi_gambar = '$_POST[resolusi_gambar]',
                    resolusi_video = '$_POST[resolusi_video]',
                    iso_max = '$_POST[iso_max]',
                    shutter_speed = '$_POST[shutter_speed]',
                    fitur = '$_POST[fitur]'
                    WHERE id_produk='$id'
               "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'Spesification ' . $id . ' Updated Successfully.'
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Spesification ' . $id . ' Update Failed.'
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

    function delete_spesification($id)
    {
        global $mysqli;
        $query = "DELETE FROM spesifications WHERE id_produk='$id'";
        if (mysqli_query($mysqli, $query)) {
            $response = array(
                'status' => 1,
                'message' => 'Spesification of ' . $id . ' Deleted Successfully.'
            );
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Spesification of ' . $id . ' Delete Failed.'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
