<?php

require_once('../helper.php');

class User
{

    public  function get_users()
    {
        global $mysqli;
        $query = "SELECT id_user, email FROM users WHERE role !='admin'";
        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        $response = array(
            'status' => 1,
            'message' => 'Get List Users Successfully.',
            'data' => $data
        );
        echo json_encode($response);
    }

    public function get_user($id)
    {
        global $mysqli;
        $query = "SELECT id_user, email FROM users
                    WHERE id_user='$id' AND role !='admin'
                    LIMIT 1";
        // if ($id != 0) {
        //     $query .= " WHERE id_motor=" . $id . " LIMIT 1";
        // }
        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        $response = array(
            'status' => 1,
            'message' => 'Get User ' . $id . ' Successfully.',
            'data' => $data
        );
        echo json_encode($response);
    }

    public function generate_id()
    {
        global $mysqli;
        $query = "SELECT MAX(id_user) as last_id FROM users";
        $result = $mysqli->query($query);
        $data = mysqli_fetch_array($result);
        $id = $data['last_id'];
        $order = (int) substr($id, 4, 4);
        $order++;
        $id = "US" . sprintf("%04s", $order);
        return $id;
    }

    public function insert_user()
    {
        global $mysqli;
        $id_user = $this->generate_id();
        $arrcheckpost = array('email' => '', 'passwd' => '');
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $result = mysqli_query(
                $mysqli,
                "INSERT INTO users SET
                    id_user = '$id_user',
                    email = '$_POST[email]',
                    passwd = '$_POST[passwd]'
               "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'User ' . $id_user . ' Added Successfully.',
                    'id' => $id_user
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'User ' . $id_user . ' Added Failed.'
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
    public function update_user($id)
    {
        global $mysqli;
        $arrcheckpost = array('email' => '', 'passwd' => '');
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $result = mysqli_query(
                $mysqli,
                "UPDATE users SET
                    email = '$_POST[email]',
                    passwd = '$_POST[passwd]'
                WHERE id_user='$id'
               "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'Members ' . $id . ' Updated Successfully.'
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Members ' . $id . ' Update Failed.'
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

    function delete_user($id)
    {
        global $mysqli;
        $query = "DELETE FROM users WHERE id_user='$id'";
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


    public function login($email, $passwd)
    {
        global $mysqli;
        $query = "SELECT id_user, email, role FROM users
                    WHERE email='$email' AND passwd='$passwd'
                    LIMIT 1";
        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        if (!empty($data)) {
            $response = array(
                'status' => 1,
                'message' => 'Login Successfully.',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 404,
                'message' => 'User Not Found.',
            );
        }
        echo json_encode($response);
    }
}
