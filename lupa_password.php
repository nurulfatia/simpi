<?php
session_start();
include "koneksi.php";

$pesan = "";

if (isset($_POST['lanjut'])) {

    $no_hp = $_POST['no_hp'];
    if (substr($no_hp, 0, 1) == "0") {
        $no_hp = "6283165603823" . substr($no_hp, 1);
    }
    $cek = mysqli_query(
        $koneksi,
        "SELECT * FROM users WHERE no_hp='$no_hp'"
    );

    if (mysqli_num_rows($cek) == 1) {

        $otp = rand(100000, 999999);
        $expired = date(
            "Y-m-d H:i:s",
            strtotime("+10 minutes")
        );

        mysqli_query(
            $koneksi,
            "UPDATE users SET 
            otp='$otp',
            otp_expired='$expired'
            WHERE no_hp='$no_hp'"
        );

        $_SESSION['reset_no_hp'] = $no_hp;
        $token = "11MNDLNWeQtuhFqdoG6f";
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.fonnte.com/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                "target" => $no_hp,
                "message" => "Kode OTP SIMPI Tangkap: $otp. Berlaku selama 10 menit."
            ],

            CURLOPT_HTTPHEADER => [
                "Authorization: $token"
            ]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        $hasil = json_decode($response, true);
        if (isset($hasil['status']) && $hasil['status'] == true) {
            header("Location: verifikasi_otp.php");
            exit();
        } else {
            $pesan = "OTP gagal dikirim ke WhatsApp!";
        }
    } else {
        $pesan = "Nomor WhatsApp tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lupa Password - SIMPI Tangkap</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
body{
    min-height:100vh;
    display:flex;
    align-items:center;
    background:
        linear-gradient(rgba(0,80,120,.45), rgba(0,100,150,.55)),
        url("laut.jpg") center/cover no-repeat;
}
.card-reset{
    max-width:430px;
    border-radius:20px;
}
</style>
</head>
<body>
<div class="container">
    <div class="card card-reset shadow border-0 mx-auto">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-phone fs-1 text-primary"></i>
                <h3 class="fw-bold mt-2">
                    Lupa Password?
                </h3>
                <p class="text-muted">
                    Masukkan nomor WhatsApp yang terdaftar.
                </p>
            </div>
            <?php if ($pesan != "") { ?>
                <div class="alert alert-danger text-center">
                    <?= $pesan ?>
                </div>
            <?php } ?>
            <form method="POST">
                <input
                    type="text"
                    name="no_hp"
                    class="form-control form-control-lg mb-3"
                    placeholder="Contoh: 081234567890"
                    required
                >
                <button
                    type="submit"
                    name="lanjut"
                    class="btn btn-primary w-100 py-2">

                    Kirim Kode OTP
                    <i class="bi bi-whatsapp"></i>
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="login.php"
                   class="text-decoration-none">
                    ← Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>