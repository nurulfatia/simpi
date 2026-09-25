<?php
include "auth.php";
include "koneksi.php";

$username = $_SESSION['username'] ?? 'Admin';
$role = $_SESSION['role'] ?? 'Administrator';
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body{
            margin:0;
            background:#f3f6fa;
            font-family:Arial,sans-serif;
        }

        .content{
            margin-left:230px;
            padding:35px;
        }

        .judul{
            font-size:25px;
            font-weight:bold;
            color:#243746;
        }

        .deskripsi{
            color:#8795a1;
            font-size:14px;
            margin-bottom:25px;
        }

        .profile-card{
            max-width:900px;
            background:white;
            border-radius:18px;
            overflow:hidden;
            box-shadow:0 5px 20px rgba(0,0,0,.06);
        }

        .banner{
            height:145px;
            background:
            linear-gradient(rgba(0,76,115,.75),rgba(18,150,232,.8)),
            url("laut.jpg") center/cover;
            position:relative;
        }

        .profile-icon{
            position:absolute;
            left:35px;
            bottom:-45px;
            width:100px;
            height:100px;
            border-radius:50%;
            background:white;
            border:6px solid white;
            box-shadow:0 3px 12px rgba(0,0,0,.15);
            display:flex;
            align-items:center;
            justify-content:center;
            color:#1296e8;
            font-size:60px;
        }

        .profile-name{
            padding:60px 35px 25px;
            border-bottom:1px solid #edf0f3;
        }

        .profile-name h3{
            font-size:23px;
            font-weight:bold;
            color:#263746;
            margin-bottom:8px;
        }

        .profile-name p{
            color:#8995a1;
            margin:0;
            font-size:14px;
        }

        .status{
            display:inline-block;
            background:#e5f8ed;
            color:#198754;
            padding:5px 12px;
            border-radius:20px;
            font-size:12px;
            margin-top:12px;
        }

        .profile-body{
            padding:30px 35px;
        }

        .section-title{
            font-size:17px;
            font-weight:bold;
            color:#263746;
            margin-bottom:20px;
        }

        .info-box{
            display:flex;
            align-items:center;
            gap:15px;
            padding:16px;
            border:1px solid #e9eef3;
            border-radius:12px;
            margin-bottom:15px;
        }

        .info-icon{
            width:42px;
            height:42px;
            border-radius:10px;
            background:#eaf5fd;
            color:#1296e8;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:21px;
        }

        .info-label{
            font-size:12px;
            color:#8995a1;
            margin-bottom:4px;
        }

        .info-value{
            font-size:14px;
            font-weight:bold;
            color:#34495e;
        }

        .profile-footer{
            padding:18px 35px;
            background:#f8fafc;
            border-top:1px solid #edf0f3;
            font-size:12px;
            color:#8995a1;
        }

        @media(max-width:768px){
            .content{
                margin-left:230px;
                padding:20px;
            }

            .profile-body,
            .profile-name{
                padding-left:22px;
                padding-right:22px;
            }

            .profile-icon{
                left:22px;
            }
        }
    </style>

</head>

<body>

    <?php include "index.php"; ?>

    <div class="content">

        <div class="judul">Profil Admin</div>

        <div class="deskripsi">
            Kelola dan lihat informasi akun administrator
        </div>

        <div class="profile-card">

            <!-- BANNER -->
            <div class="banner">

                <div class="profile-icon">
                    <i class="bi bi-person-fill"></i>
                </div>

            </div>

            <!-- NAMA ADMIN -->
            <div class="profile-name">

                <h3>
                    <?= htmlspecialchars($username) ?>
                </h3>

                <p>
                    <i class="bi bi-shield-check me-1"></i>
                    Administrator Sistem Informasi Perikanan
                </p>

                <span class="status">
                    <i class="bi bi-check-circle me-1"></i>
                    Akun Aktif
                </span>

            </div>

            <!-- INFORMASI -->
            <div class="profile-body">

                <div class="section-title">
                    <i class="bi bi-person-vcard me-2"></i>
                    Informasi Akun
                </div>

                <div class="row">

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-icon">
                                <i class="bi bi-person"></i>
                            </div>

                            <div>
                                <div class="info-label">
                                    Username
                                </div>

                                <div class="info-value">
                                    <?= htmlspecialchars($username) ?>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>

                            <div>
                                <div class="info-label">
                                    Hak Akses
                                </div>

                                <div class="info-value">
                                    <?= htmlspecialchars($role) ?>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-icon">
                                <i class="bi bi-building"></i>
                            </div>

                            <div>
                                <div class="info-label">
                                    Instansi
                                </div>

                                <div class="info-value">
                                    Dinas Perikanan dan Kelautan
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>

                            <div>
                                <div class="info-label">
                                    Wilayah
                                </div>

                                <div class="info-value">
                                    Kabupaten Langkat
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="profile-footer">

                <i class="bi bi-info-circle me-2"></i>

                Profil administrator sistem pengolahan data hasil produksi perikanan tangkap.

            </div>

        </div>

    </div>

</body>
</html>