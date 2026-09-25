<?php

include "koneksi.php";

$id = $_GET['id'];

mysqli_query(
    $koneksi,
    "DELETE FROM hasil_produksi WHERE id='$id'"
);

header("Location: hasil_produksi.php");

exit();

?>