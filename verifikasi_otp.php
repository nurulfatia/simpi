<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['reset_no_hp'])) {
    header("Location: lupa_password.php");
    exit();
}

$pesan = "";

if (isset($_POST['verifikasi'])) {

    $otp = mysqli_real_escape_string($koneksi, $_POST['otp']);
    $no_hp = $_SESSION['reset_no_hp'];

    $sekarang = date("Y-m-d H:i:s");

    $cek = mysqli_query(
        $koneksi,
        "SELECT * FROM users
         WHERE no_hp='$no_hp'
         AND otp='$otp'
         AND otp_expired > '$sekarang'"
    );

    if (mysqli_num_rows($cek) == 1) {

        $_SESSION['otp_benar'] = true;

        header("Location: password_baru.php");
        exit();

    } else {

        $pesan = "Kode OTP salah atau sudah kedaluwarsa!";

    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Verifikasi OTP</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{
    min-height:100vh;
    display:flex;
    align-items:center;
    background:#f5f7fa;
}

.card-otp{
    max-width:430px;
    border-radius:20px;
}

.otp-input{
    text-align:center;
    font-size:24px;
    letter-spacing:8px;
}
</style>

</head>

<body>

<div class="container">

    <div class="card card-otp shadow border-0 mx-auto">

        <div class="card-body p-4">

            <div class="text-center mb-4">

                <i class="bi bi-shield-lock fs-1 text-primary"></i>

                <h3 class="fw-bold">
                    Verifikasi Kode
                </h3>

                <p class="text-muted">
                    Masukkan kode OTP Anda.
                </p>

            </div>

            <?php if ($pesan != "") { ?>

                <div class="alert alert-danger">
                    <?= $pesan ?>
                </div>

            <?php } ?>

            <form method="POST">

                <input
                    type="text"
                    name="otp"
                    class="form-control form-control-lg otp-input mb-3"
                    placeholder="000000"
                    maxlength="6"
                    required
                >

                <button
                    type="submit"
                    name="verifikasi"
                    class="btn btn-primary w-100 py-2">

                    Verifikasi

                </button>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>