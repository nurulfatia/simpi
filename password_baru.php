<?php
session_start();
include "koneksi.php";

if (
    !isset($_SESSION['reset_no_hp']) ||
    !isset($_SESSION['otp_benar'])
) {
    header("Location: lupa_password.php");
    exit();
}

$pesan = "";

if (isset($_POST['simpan'])) {

    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($password != $konfirmasi) {

        $pesan = "Konfirmasi password tidak sama!";

    } else {

        $no_hp = $_SESSION['reset_no_hp'];

        mysqli_query(
            $koneksi,
            "UPDATE users
             SET password='$password',
                 otp=NULL,
                 otp_expired=NULL
             WHERE no_hp='$no_hp'"
        );

        unset($_SESSION['reset_no_hp']);
        unset($_SESSION['otp_benar']);

        header("Location: login.php?reset=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Password Baru</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{
    min-height:100vh;
    display:flex;
    align-items:center;
    background:#f5f7fa;
}

.card-password{
    max-width:430px;
    border-radius:20px;
}
</style>

</head>

<body>

<div class="container">

    <div class="card card-password shadow border-0 mx-auto">

        <div class="card-body p-4">

            <div class="text-center mb-4">

                <i class="bi bi-lock-fill fs-1 text-primary"></i>

                <h3 class="fw-bold">
                    Buat Password Baru
                </h3>

                <p class="text-muted">
                    Masukkan password baru Anda.
                </p>

            </div>

            <?php if ($pesan != "") { ?>

                <div class="alert alert-danger">
                    <?= $pesan ?>
                </div>

            <?php } ?>

            <form method="POST">

                <input
                    type="password"
                    name="password"
                    class="form-control form-control-lg mb-3"
                    placeholder="Password baru"
                    required
                >

                <input
                    type="password"
                    name="konfirmasi"
                    class="form-control form-control-lg mb-3"
                    placeholder="Konfirmasi password baru"
                    required
                >

                <button
                    type="submit"
                    name="simpan"
                    class="btn btn-primary w-100 py-2">

                    Simpan Password Baru

                </button>

            </form>
            <a href="login.php"
               class="btn btn-outline-secondary w-100 mt-3 py-2">

                <i class="bi bi-arrow-left"></i>
                Kembali ke Login

            </a>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script> 
 
</body> 
</html>