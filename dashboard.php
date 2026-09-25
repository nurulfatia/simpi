<?php
include "auth.php";
include "koneksi.php";

$tahun = $_GET['tahun'] ?? '2024';
$periode = $_GET['periode'] ?? 'Tahunan';

$where = "WHERE Tahun='$tahun'";

if ($periode == 'Tahunan') {
    $where .= " AND (Triwulan='' OR Triwulan IS NULL)";
} else {
    $where .= " AND Triwulan='$periode'";
}

$q = mysqli_query($koneksi,"
    SELECT Daerah, SUM(produksi) AS total
    FROM hasil_produksi
    $where
    GROUP BY Daerah
    ORDER BY total DESC
");

$daerah = [];
$produksi = [];
$total = 0;

while($r = mysqli_fetch_assoc($q)){
    $daerah[] = $r['Daerah'];
    $produksi[] = (float)$r['total'];
    $total += (float)$r['total'];
}
$qikan = mysqli_query($koneksi,"
    SELECT COUNT(DISTINCT Nama_ikan) AS jumlah
    FROM hasil_produksi
    $where
");

$jumlah_ikan = mysqli_fetch_assoc($qikan)['jumlah'] ?? 0;
$jumlah_kecamatan = count($daerah);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>

<style>
*{box-sizing:border-box}

body{
    margin:0;
    background:#f4f8fc;
    font-family:Arial,sans-serif;
    color:#123b68;
}
main{
    margin-left:230px;
    padding:25px;
}
.header{
    background:#fff;
    border:1px solid #d7e5ef;
    border-radius:12px;
    padding:20px 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}
.judul{
    font-size:30px;
    font-weight:bold;
    color:#073b78;
    margin:0;
}
.selamat{
    margin:0;
    font-size:18px;
    color:#52708e;
}
.subjudul{
    margin:4px 0 0;
    font-size:15px;
    color:#52708e;
}
.filter{
    display:flex;
    gap:8px;
}
.filter select{
    height:42px;
    min-width:125px;
    border:1px solid #cbddea;
    border-radius:8px;
    padding:0 12px;
    color:#173f6d;
    background:#fff;
}

.filter button{
    height:42px;
    padding:0 22px;
    border:0;
    border-radius:8px;
    background:#087bdd;
    color:#fff;
    font-weight:bold;
}
.stat{
    padding:7px;
}
.stat-box{
    height:115px;
    background:#fff;
    border:1px solid #cbdfea;
    border-top:4px solid #1488df;
    border-radius:11px;
    padding:18px 20px;
    position:relative;
}

.stat-title{
    font-size:14px;
    color:#244d76;
    margin-bottom:5px;
}

.stat-number{
    font-size:27px;
    font-weight:bold;
    color:#073b78;
}

.stat-unit{
    font-size:13px;
    color:#244d76;
}

.stat-icon{
    position:absolute;
    right:20px;
    bottom:18px;
    font-size:27px;
    color:#198bdf;
}

.purple{
    border-top-color:#7359dc;
}

.purple .stat-icon{
    color:#7359dc;
}

.box{
    background:#fff;
    border:1px solid #d4e4ef;
    border-radius:12px;
    padding:18px;
    margin-top:14px;
    height:360px;
}

.box-title{
    color:#073b78;
    font-size:17px;
    font-weight:bold;
    margin-bottom:10px;
}

.box-title i{
    color:#087bd7;
    font-size:21px;
    margin-right:9px;
}

.chart{
    height:295px;
}

.table{
    margin-bottom:0;
    font-size:13px;
}

.table thead th{
    background:#087bd7!important;
    color:#fff!important;
    text-align:center;
    padding:10px!important;
}

.table tbody td{
    padding:8px!important;
    color:#173f6d;
    border:1px solid #dce7ef!important;
}

.table tbody tr:nth-child(even){
    background:#f4f8fb;
}

.jumlah{
    background:#dceefd!important;
    font-weight:bold;
    color:#064281!important;
}

@media(max-width:992px){
    main{
        margin-left:0;
        padding:15px;
    }

    .header{
        display:block;
    }

    .filter{
        margin-top:15px;
    }
}

@media(max-width:600px){
    .filter{
        display:block;
    }

    .filter select,
    .filter button{
        width:100%;
        margin-bottom:6px;
    }
}
</style>
</head>

<body>

<?php include "index.php"; ?>

<main>

<div class="header">
    <div>
        <p class="selamat">Selamat Datang di</p>
        <h1 class="judul">Sistem Informasi Hasil Produksi</h1>
        <p class="subjudul">
            Dinas Perikanan & Kelautan Kabupaten Langkat
        </p>
    </div>

    <form method="GET" class="filter">

        <select name="tahun">
            <?php foreach(['2024','2025','2026'] as $t): ?>
                <option value="<?= $t ?>"
                    <?= $tahun==$t?'selected':'' ?>>
                    <?= $t ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="periode">
            <?php foreach([
                'Tahunan','TW I','TW II','TW III','TW IV'
            ] as $p): ?>
                <option value="<?= $p ?>"
                    <?= $periode==$p?'selected':'' ?>>
                    <?= $p ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Cari</button>
    </form>
</div>
<div class="row">

    <div class="col-md-3 col-sm-6 stat">
        <div class="stat-box">
            <div class="stat-title">Total Produksi</div>
            <div class="stat-number">
                <?= number_format($total,2,',','.') ?>
            </div>
            <div class="stat-unit">Ton</div>
            <i class="fa fa-bar-chart stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 stat">
        <div class="stat-box">
            <div class="stat-title">Jumlah Jenis Ikan</div>
            <div class="stat-number"><?= $jumlah_ikan ?></div>
            <div class="stat-unit">Jenis</div>
            <i class="fa fa-list-ul stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 stat">
        <div class="stat-box purple">
            <div class="stat-title">Jumlah Kecamatan</div>
            <div class="stat-number"><?= $jumlah_kecamatan ?></div>
            <div class="stat-unit">Kecamatan</div>
            <i class="fa fa-ship stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 stat">
        <div class="stat-box">
            <div class="stat-title">Tahun Data</div>
            <div class="stat-number"><?= $tahun ?></div>
            <div class="stat-unit"><?= $periode ?></div>
            <i class="fa fa-calendar stat-icon"></i>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-title">
                <i class="fa fa-bar-chart"></i>
                Grafik Hasil Produksi per Kecamatan (Ton)
            </div>

            <div class="chart">
                <canvas id="diagram"></canvas>
            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="box">

            <div class="box-title">
                <i class="fa fa-table"></i>
                Rekap Produksi per Kecamatan
            </div>

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kecamatan</th>
                            <th>Produksi (Ton)</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach($daerah as $i=>$d): ?>

                        <tr>
                            <td class="text-center">
                                <?= $i+1 ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($d) ?>
                            </td>

                            <td class="text-end">
                                <?= number_format(
                                    $produksi[$i],
                                    2,
                                    ',',
                                    '.'
                                ) ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                    <tr class="jumlah">
                        <td></td>
                        <td class="text-center">Jumlah</td>
                        <td class="text-end">
                            <?= number_format(
                                $total,
                                2,
                                ',',
                                '.'
                            ) ?>
                        </td>
                    </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</main>


<script>
new Chart(document.getElementById('diagram'),{
    type:'bar',

    data:{
        labels:<?= json_encode($daerah) ?>,

        datasets:[{
            data:<?= json_encode($produksi) ?>,
            backgroundColor:'#138be5',
            borderColor:'#0875c9',
            borderWidth:1,
            barPercentage:.55,
            categoryPercentage:.7
        }]
    },

    options:{
        responsive:true,
        maintainAspectRatio:false,

        legend:{
            display:false
        },

        scales:{
            yAxes:[{
                ticks:{
                    beginAtZero:true,
                    fontColor:'#173f6d'
                },
                gridLines:{
                    color:'#e2e9ef'
                }
            }],

            xAxes:[{
                ticks:{
                    fontColor:'#173f6d',
                    fontSize:9,
                    autoSkip:false
                },
                gridLines:{
                    display:false
                }
            }]
        }
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>