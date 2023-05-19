<?php

require_once('../helper.php');

class Product
{

    public  function get_products()
    {
        global $mysqli;
        $query = "SELECT * FROM products JOIN brands USING(id_merk) ORDER BY id_produk";
        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        $response = array(
            'status' => 1,
            'message' => 'Get List Products Successfully.',
            'data' => $data
        );
        echo json_encode($response);
    }

    public function get_product($id)
    {
        global $mysqli;
        $query = "SELECT * FROM products JOIN brands USING(id_merk)
                    WHERE id_produk='$id'
                    LIMIT 1";
        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        $response = array(
            'status' => 1,
            'message' => 'Get Product ' . $id . ' Successfully.',
            'data' => $data
        );
        echo json_encode($response);
    }

    public function search_product($key)
    {
        global $mysqli;
        $query = "SELECT * FROM products
                    WHERE nama_produk LIKE '%$key%'
                    LIMIT 1";
        $data = array();
        $result = $mysqli->query($query);
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        $response = array(
            'status' => 1,
            'message' => 'Get Product ' . $key . ' Successfully.',
            'data' => $data
        );
        echo json_encode($response);
    }

    public function generate_id()
    {
        global $mysqli;
        $query = "SELECT MAX(id_produk) as last_id FROM products";
        $result = $mysqli->query($query);
        $data = mysqli_fetch_array($result);
        $id = $data['last_id'];
        $order = (int) substr($id, 4, 4);
        $order++;
        $id = "PR" . sprintf("%04s", $order);
        return $id;
    }

    public function insert_product()
    {
        global $mysqli;
        $id_product = $this->generate_id();
        $arrcheckpost = array(
            'id_merk' => '',
            'nama_produk' => '',
            'kategori' => '',
            'harga_sewa' => '',
            'stock' => '',
            'img_src' => ''
        );
        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $result = mysqli_query(
                $mysqli,
                "INSERT INTO products SET
                    id_produk = '$id_product',
                    id_merk = '$_POST[id_merk]',
                    nama_produk = '$_POST[nama_produk]',
                    kategori = '$_POST[kategori]',
                    harga_sewa = '$_POST[harga_sewa]',
                    stock = '$_POST[stock]',
                    img_src = '$_POST[img_src]'
               "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'Product ' . $id_product . ' Added Successfully.',
                    'id' => $id_product,
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Product ' . $id_product . ' Added Failed.'
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
    public function update_product($id)
    {
        global $mysqli;
        $arrcheckpost = array(
            'id_merk' => '',
            'nama_produk' => '',
            'kategori' => '',
            'harga_sewa' => '',
            'stock' => '',
            'img_src' => '',
        );

        $hitung = count(array_intersect_key($_POST, $arrcheckpost));
        if ($hitung == count($arrcheckpost)) {

            $result = mysqli_query(
                $mysqli,
                "UPDATE products SET
                    id_merk = '$_POST[id_merk]',
                    nama_produk = '$_POST[nama_produk]',
                    kategori = '$_POST[kategori]',
                    harga_sewa = '$_POST[harga_sewa]',
                    stock = '$_POST[stock]',
                    img_src = '$_POST[img_src]'
                WHERE id_produk='$id'
               "
            );

            if ($result) {
                $response = array(
                    'status' => 1,
                    'message' => 'Product ' . $id . ' Updated Successfully.'
                );
            } else {
                $response = array(
                    'status' => 0,
                    'message' => 'Product ' . $id . ' Update Failed.'
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

    function delete_product($id)
    {
        global $mysqli;
        $query = "DELETE FROM products WHERE id_produk='$id'";
        if (mysqli_query($mysqli, $query)) {
            $response = array(
                'status' => 1,
                'message' => 'Product ' . $id . ' Deleted Successfully.'
            );
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Product ' . $id . ' Delete Failed.'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }


    public function login($email, $passwd)
    {
        global $mysqli;
        $query = "SELECT id_product, email, role FROM products
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
                'message' => 'Product Not Found.',
            );
        }
        echo json_encode($response);
    }
}
