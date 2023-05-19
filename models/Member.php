<?php

require_once('../helper.php');

class Member
{

    public  function get_members()
    {
        global $mysqli;
        $query = "SELECT id_user, email, nama, jenis_kelamin, tgl_lahir, telp, pekerjaan, alamat 
                    FROM users
                    JOIN members
                    USING(id_user)";
        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        $response = array(
            'status' => 1,
            'message' => 'Get List Members Successfully.',
            'data' => $data
        );
        echo json_encode($response);
    }

    public function get_member($id)
    {
        global $mysqli;
        $query = "SELECT id_user, email, nama, jenis_kelamin, tgl_lahir, telp, pekerjaan, alamat 
                    FROM users
                    JOIN members
                    USING(id_user)
                    WHERE id_user='$id'
                    LIMIT 1";

        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }

        if (!empty($data)) {
            $response = array(
                'status' => 1,
                'message' => 'Get Member ' . $id . ' Successfully.',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 404,
                'message' => 'Member ' . $id . ' Not Found.',
            );
        }

        echo json_encode($response);
    }

    public function insert_member()
    {
        global $mysqli;
        $arrcheckpost = array('id_user' => '', 'nama' => '', 'jenis_kelamin' => '', 'tgl_lahir' => '', 'telp' => '', 'pekerjaan' => '', 'alamat' => '');
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $id_user = $_POST['id_user'];

            $result = mysqli_query(
                $mysqli,
                "INSERT INTO members SET
                    id_user = '$_POST[id_user]',
                    nama = '$_POST[nama]',
                    jenis_kelamin = '$_POST[jenis_kelamin]',
                    tgl_lahir = '$_POST[tgl_lahir]',
                    telp = '$_POST[telp]',
                    pekerjaan = '$_POST[pekerjaan]',
                    alamat = '$_POST[alamat]'
               "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'Member ' . $id_user . ' Added Successfully.'
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Member ' . $id_user . ' Added Failed.'
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
    public function update_member($id)
    {
        global $mysqli;
        $arrcheckpost = array('nama' => '', 'jenis_kelamin' => '', 'tgl_lahir' => '', 'telp' => '', 'pekerjaan' => '', 'alamat' => '');
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $result = mysqli_query(
                $mysqli,
                "UPDATE members SET
                    nama = '$_POST[nama]',
                    jenis_kelamin = '$_POST[jenis_kelamin]',
                    tgl_lahir = '$_POST[tgl_lahir]',
                    telp = '$_POST[telp]',
                    pekerjaan = '$_POST[pekerjaan]',
                    alamat = '$_POST[alamat]'
                WHERE id_user='$id'
               "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'Member ' . $id . ' Updated Successfully.'
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Member ' . $id . ' Update Failed.'
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

    function delete_member($id)
    {
        global $mysqli;
        $query = "DELETE FROM members WHERE id_user='$id'";
        if (mysqli_query($mysqli, $query)) {
            $response = array(
                'status' => 1,
                'message' => 'Member ' . $id . ' Deleted Successfully.'
            );
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Member ' . $id . ' Delete Failed.'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
