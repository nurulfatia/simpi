<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login - Sistem Informasi Perikanan Tangkap</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>

body{
    min-height:100vh;
    margin:0;
    font-family:'Poppins',sans-serif;
    background:
    linear-gradient(rgba(0,80,120,.45),rgba(0,100,150,.55)),
    url("laut.jpg") center/cover no-repeat;
}

.instansi{
    position:absolute;
    top:40px;
    left:50px;
    display:flex;
    align-items:center;
    gap:15px;
    color:white;
}

.instansi img{
    width:70px;
    height:70px;
    object-fit:contain;
}

.instansi h5,
.instansi p{
    margin:0;
}

.login-card{
    width:430px;
    background:rgba(255,255,255,.95);
    border-radius:22px;
    padding:30px;
}

.input-box{
    position:relative;
}

.input-box i{
    position:absolute;
    left:18px;
    top:17px;
    color:#68788a;
}

.form-control{
    height:55px;
    padding-left:50px;
    border-radius:10px;
}

.btn-masuk{
    height:55px;
    background:#1685c5;
    color:white;
    border:none;
    transition:.3s;
}

.btn-masuk:hover{
    background:#0d6fa8;
}

.lupa-password{
    display:inline-block;
    margin-top:15px;
    color:#1685c5;
    text-decoration:none;
    font-size:14px;
    font-weight:500;
}

.lupa-password:hover{
    text-decoration:underline;
    color:#0d6fa8;
}

</style>

</head>

<body>

<div class="instansi">

    <img src="logo.jpg" alt="Logo Kabupaten Langkat">

    <div>
        <h5 class="fw-bold">Dinas Perikanan dan Kelautan</h5>
        <p>Kabupaten Langkat</p>
    </div>
</div>

<div class="container-fluid">

    <div class="row min-vh-100 align-items-center">
        <div class="col-md-6 text-white ps-5">

            <h1 class="fw-bold display-5">
                Data Perikanan<br>
                untuk Laut yang Lebih Baik
            </h1>

            <hr class="border-3 opacity-100" style="width:60px;">

            <p class="fs-5 mt-4">
                Mendukung pengelolaan sumber daya perikanan<br>
                yang berkelanjutan di Kabupaten Langkat.
            </p>

        </div>
        <div class="col-md-6 d-flex justify-content-center">

            <div class="login-card shadow">

                <h2 class="text-center fw-bold">
                    Sistem Informasi<br>
                    Hasil Produksi<br>
                    Perikanan Tangkap
                </h2>

                <div class="bg-info mx-auto my-3"
                     style="width:50px;height:4px;">
                </div>

                <p class="text-center fw-semibold mb-4">
                    Dinas Perikanan dan Kelautan<br>
                    Kabupaten Langkat
                </p>
                <?php if(isset($_GET['error'])): ?>

                    <div class="alert alert-danger">
                        Username atau password salah.
                    </div>
                <?php endif; ?>
                <form method="post" action="proses_login.php">
                    <div class="input-box mb-3">
                        <i class="bi bi-person"></i>
                        <input type="text"
                               class="form-control"
                               name="username"
                               placeholder="Masukkan username"
                               required>
                    </div>

                    <div class="input-box mb-3">
                        <i class="bi bi-lock"></i>
                        <input type="password"
                               class="form-control"
                               name="password"
                               placeholder="Masukkan password"
                               required>
                    </div>

                    <button type="submit"
                            class="btn-masuk w-100 rounded-3 fw-semibold">
                        Masuk
                        <i class="bi bi-arrow-right"></i>
                    </button>

                    <div class="text-center">
                        <a href="lupa_password.php"
                           class="lupa-password">
                            <i class="bi bi-key"></i>
                            Lupa Password?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>