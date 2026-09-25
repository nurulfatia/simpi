<?php
$halaman_aktif = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
*{
    font-family:'Poppins',sans-serif;
}

.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:230px;
    height:100vh;
    padding:30px 16px 0;
    color:white;
    background:
    linear-gradient(rgba(0,55,90,.78),rgba(0,55,90,.78)),
    url("laut.jpg") center/cover;
    display:flex;
    flex-direction:column;
    z-index:1000;
}

.instansi{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:45px;
}

.instansi img{
    width:45px;
    height:45px;
    object-fit:contain;
}

.sub{
    font-size:11px;
    font-weight:500;
    line-height:1.6;
}

.nav-link{
    color:white;
    font-size:15px;
    padding:12px 15px;
    margin-bottom:8px;
    border-radius:9px;
    display:flex;
    align-items:center;
    gap:15px;
}

.nav-link i{
    font-size:22px;
    width:25px;
}

.nav-link.active{
    background:#1296e8;
}

.nav-link:hover{
    background:rgba(255,255,255,.15);
    color:white;
}
.admin-area{
    margin-top:auto;
    padding-bottom:25px;
    position:relative;
}
.profil-admin{
    width:100%;
    border:1px solid rgba(255,255,255,.3);
    border-radius:9px;
    padding:9px 8px;
    display:flex;
    align-items:center;
    gap:8px;
    color:white;
    background:rgba(255,255,255,.06);
    cursor:pointer;
}
.profil-admin:hover{
    background:rgba(255,255,255,.15);
}
.profil-admin i:first-child{
    font-size:23px;
}
.nama{
    font-size:12px;
    font-weight:600;
}

.profil-admin small{
    font-size:8px;
    opacity:.85;
    white-space:nowrap;
}

.menu-admin{
    display:none;
    position:absolute;
    bottom:85px;
    left:0;
    width:190px;
    padding:6px;
    background:white;
    border-radius:9px;
    box-shadow:0 4px 15px rgba(0,0,0,.3);
    z-index:9999;
}

.menu-admin.show{
    display:block;
}

.menu-admin a{
    display:block;
    padding:9px 10px;
    color:#333;
    text-decoration:none;
    font-size:12px;
    border-radius:6px;
}

.menu-admin a:hover{
    background:#1296e8;
    color:white;
}

.menu-admin i{
    margin-right:7px;
    font-size:15px;
}
</style>
</head>

<body>

<div class="sidebar">
    <div class="instansi">

        <img src="logo.jpg" alt="Logo Kabupaten Langkat">

        <div class="sub">
            Dinas Perikanan &<br>
            Kelautan Kabupaten<br>
            Langkat
        </div>

    </div>
    <nav class="nav flex-column">

        <a href="dashboard.php"
           class="nav-link <?= $halaman_aktif=='dashboard.php'?'active':'' ?>">
            <i class="bi bi-house-door"></i>
            Dashboard
        </a>

        <a href="hasil_produksi.php"
           class="nav-link <?= $halaman_aktif=='hasil_produksi.php'?'active':'' ?>">
            <i class="bi bi-bar-chart-fill"></i>
            Hasil Produksi
        </a>

        <a href="laporan.php"
           class="nav-link <?= $halaman_aktif=='laporan.php'?'active':'' ?>">
            <i class="bi bi-file-earmark-text"></i>
            Laporan
        </a>

        <a href="logout.php" class="nav-link">
            <i class="bi bi-box-arrow-right"></i>
            Keluar
        </a>

    </nav>
    <div class="admin-area">

        <button class="profil-admin" id="adminBtn" type="button">

            <i class="bi bi-person-circle"></i>

            <div class="text-start">
                <div class="nama">Admin</div>
                <small>Dinas Perikanan & Kelautan</small>
            </div>

            <i class="bi bi-chevron-down ms-auto"
               style="font-size:13px;"></i>

        </button>

        <div class="menu-admin" id="menuAdmin">

            <a href="profil.php">
                <i class="bi bi-person"></i>
                Profil
            </a>

        </div>

    </div>

</div>

<script>
const adminBtn = document.getElementById("adminBtn");
const menuAdmin = document.getElementById("menuAdmin");

adminBtn.addEventListener("click", function(){

    menuAdmin.classList.toggle("show");

});

document.addEventListener("click", function(event){

    if(!adminBtn.contains(event.target) &&
       !menuAdmin.contains(event.target)){

        menuAdmin.classList.remove("show");

    }

});
</script>

</body>
</html>