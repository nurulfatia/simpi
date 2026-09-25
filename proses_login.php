<?php
session_start();
include "koneksi.php";

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);

$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$hasil = mysqli_query($koneksi, $query);

if (mysqli_num_rows($hasil) == 1) {
    $data = mysqli_fetch_assoc($hasil);
    $_SESSION['user_id']  = $data['id_user'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role']     = $data['role'];

    header("Location: dashboard.php");
    exit();
} else {
    header("Location: login.php?error=1");
    exit();
}
?>
