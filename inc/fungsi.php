<?php
include 'koneksi.php';

function getprofilweb($tax)
{
    global $conn;

    $hasil = mysqli_query($conn, "SELECT * FROM konfigurasi WHERE Tax='$tax' ORDER BY ID DESC LIMIT 1");
    while ($r = mysqli_fetch_array($hasil)){
        return $r['Isi'];
    }
}
?>