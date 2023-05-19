<?php

require_once('../helper.php');

class Brand
{

    public  function get_brands()
    {
        global $mysqli;
        $query = "SELECT * FROM brands";
        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        $response = array(
            'status' => 1,
            'message' => 'Get List Brands Successfully.',
            'data' => $data
        );
        echo json_encode($response);
    }

    public function get_brand($id)
    {
        global $mysqli;
        $query = "SELECT * FROM brands WHERE id_merk='$id' LIMIT 1";

        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }

        if (!empty($data)) {
            $response = array(
                'status' => 1,
                'message' => 'Get Brand ' . $id . ' Successfully.',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 404,
                'message' => 'Brand ' . $id . ' Not Found.',
            );
        }

        echo json_encode($response);
    }

    public function generate_id()
    {
        global $mysqli;
        $query = "SELECT MAX(id_merk) as last_id FROM brands";
        $result = $mysqli->query($query);
        $data = mysqli_fetch_array($result);
        $last_id = (int) $data['last_id'];
        $id = $last_id + 1;
        return $id;
    }

    public function insert_brand()
    {
        global $mysqli;
        $id_merk = $this->generate_id();
        $arrcheckpost = array('nama_merk' => '');
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $nama_brand = $_POST['nama_merk'];

            $result = mysqli_query(
                $mysqli,
                "INSERT INTO brands SET 
                id_merk = '$id_merk',
                nama_merk = '$_POST[nama_merk]'
                "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'Brand ' . $nama_brand . ' Added Successfully.'
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Brand ' . $nama_brand . ' Added Failed.'
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
    public function update_brand($id)
    {
        global $mysqli;
        $arrcheckpost = array('nama_merk' => '');
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $nama_brand = $_POST['nama_merk'];

            $result = mysqli_query(
                $mysqli,
                "UPDATE brands SET
                    nama_merk = '$_POST[nama_merk]'
                    WHERE id_merk='$id'
               "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'Brand ' . $nama_brand . ' Updated Successfully.'
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Brand ' . $nama_brand . ' Update Failed.'
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

    function delete_brand($id)
    {
        global $mysqli;
        $query = "DELETE FROM brands WHERE id_merk='$id'";
        if (mysqli_query($mysqli, $query)) {
            $response = array(
                'status' => 1,
                'message' => 'Brand ' . $id . ' Deleted Successfully.'
            );
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Brand ' . $id . ' Delete Failed.'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
