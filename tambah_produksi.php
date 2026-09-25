<?php

include "koneksi.php";

$ikan = mysqli_real_escape_string(
    $koneksi,
    $_POST['ikan']
);

$tahun = mysqli_real_escape_string(
    $koneksi,
    $_POST['tahun']
);

$lokasi = mysqli_real_escape_string(
    $koneksi,
    $_POST['lokasi']
);

$produksi = mysqli_real_escape_string(
    $koneksi,
    $_POST['produksi']
);


$query = mysqli_query($koneksi, "

INSERT INTO hasil_produksi
(
    Tahun,
    Nama_ikan,
    Daerah,
    produksi
)

VALUES
(
    '$tahun',
    '$ikan',
    '$lokasi',
    '$produksi'
)

");


if($query){

    header("Location: hasil_produksi.php");
    exit();

}else{

    echo "Data gagal disimpan! <br>";

    echo mysqli_error($koneksi);

}

?>