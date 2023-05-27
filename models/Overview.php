<?php

require_once('../helper.php');

class Overview
{

    public  function get_overview()
    {
        global $mysqli;
        $data = array();
        $jmlPeminjaman = array();

        // $totalPenyewaan = array();
        // $totalKatalog = array();
        // $totalMember = array();
        // $totalPendapatan = array();

        $resultTotalPenyewaan = mysqli_query($mysqli, "SELECT COUNT(id_peminjaman) AS totalSewa FROM rents");
        $resultTotallKatalog = mysqli_query($mysqli, "SELECT COUNT(id_produk) AS totalKatalog FROM products");
        $resultTotalMember = mysqli_query($mysqli, "SELECT COUNT(id_user) AS totalMember FROM members");
        $resultTotalDenda = mysqli_query($mysqli, "SELECT SUM(denda) totalDenda FROM rent_returns");
        $resultTotalPendapatan = mysqli_query(
            $mysqli,
            "SELECT SUM(durasi*jumlah*harga_sewa) totalPendapatan
                                                        FROM rents
                                                        JOIN rent_details
                                                        USING(id_peminjaman)
                                                        JOIN products
                                                        USING(id_produk)
                                                        WHERE status='done'
                                                        "
        );
        $resultTerlaris = mysqli_query($mysqli, "SELECT nama_produk, SUM(jumlah) AS jml_peminjaman FROM rent_details JOIN products USING(id_produk) GROUP BY id_produk ORDER BY(jml_peminjaman) DESC LIMIT 4");

        while ($row = mysqli_fetch_object($resultTotalPenyewaan)) {
            $data[] = $row;
        }
        while ($row = mysqli_fetch_object($resultTotallKatalog)) {
            $data[] = $row;
        }
        while ($row = mysqli_fetch_object($resultTotalMember)) {
            $data[] = $row;
        }
        while ($row = mysqli_fetch_object($resultTotalDenda)) {
            $data[] = $row;
        }
        while ($row = mysqli_fetch_object($resultTotalPendapatan)) {
            $data[] = $row;
        }
        while ($row = mysqli_fetch_object($resultTerlaris)) {
            $jmlPeminjaman[] = $row;
        }



        $response = array(
            'status' => 1,
            'message' => 'Get List Brands Successfully.',
            'data' => $data,
            'peminjaman' => $jmlPeminjaman,
        );
        echo json_encode($response);
    }
}
