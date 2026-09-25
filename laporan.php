<?php
include "auth.php";
include "koneksi.php";

function formatAngka($angka){
    return number_format((float)$angka,2,'.',',');
}
$tahun=$_GET['tahun']??'2024';
$periode=$_GET['periode']??'Tahunan';

$lokasi=[
    'Secanggang','Tanjung Pura','Gebang','Babalan',
    'Sei Lepan','Brandan Barat','Pangkalan Susu',
    'Besitang','Pematang Jaya'
];

$where="WHERE Tahun='$tahun'";

if($periode=='Tahunan'){
    $where.=" AND (Triwulan='' OR Triwulan IS NULL)";
}else{
    $where.=" AND Triwulan='$periode'";
}

$data=mysqli_query($koneksi,"
    SELECT * FROM hasil_produksi
    $where ORDER BY Nama_ikan ASC
");

$ikan_data=[];

while($row=mysqli_fetch_assoc($data)){

    $ikan=$row['Nama_ikan'];
    $daerah=$row['Daerah'];

    if(!isset($ikan_data[$ikan])){
        $ikan_data[$ikan]=array_fill_keys($lokasi,0);
    }

    if(isset($ikan_data[$ikan][$daerah])){
        $ikan_data[$ikan][$daerah]+=(float)$row['produksi'];
    }
}

$ikan_list=array_keys($ikan_data);
$batas=ceil(count($ikan_list)/2);
$halaman1=array_slice($ikan_list,0,$batas);
$halaman2=array_slice($ikan_list,$batas);
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>Laporan Produksi</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f8fb;
    font-family:Arial,sans-serif;
}

main{
    margin-left:230px;
    padding:20px;
}

.table th,
.table td{
    text-align:center;
    vertical-align:middle;
    font-size:11px;
    padding:5px;
}

.rotate{
    writing-mode:vertical-rl;
    transform:rotate(180deg);
    height:85px;
}

.print-only{
    display:none;
}

@media(max-width:768px){

    main{
        margin-left:0;
        padding:10px;
    }

}

@media print{

    @page{
        size:A4 landscape;
        margin:6mm;
    }

    body{
        background:white!important;
    }

    body *{
        visibility:hidden;
    }

    .print-only,
    .print-only *{
        visibility:visible;
    }

    .sidebar,
    .filter,
    .screen-only{
        display:none!important;
    }

    main{
        margin:0!important;
        padding:0!important;
        width:100%!important;
    }

    .print-only{
        display:block!important;
        position:absolute;
        top:0;
        left:0;
        width:100%;
    }

    .print-page{
        width:100%!important;
        height:193mm!important;
        page-break-after:always!important;
        overflow:hidden!important;
    }

    .print-page:last-child{
        page-break-after:auto!important;
    }

    .print-title{
        text-align:center;
        font-size:12px;
        font-weight:bold;
        margin-bottom:2px;
    }

    .print-subtitle{
        text-align:center;
        font-size:9px;
        margin-bottom:4px;
    }

    .print-table{
        width:100%!important;
        table-layout:fixed!important;
        border-collapse:collapse!important;
        font-size:7.5px!important;
    }

    .print-table th,
    .print-table td{
        border:1px solid #000!important;
        padding:2px!important;
        text-align:center!important;
        vertical-align:middle!important;
        line-height:1.1!important;
        height:5.3mm!important;
        white-space:normal!important;
    }

    /* NOMOR */
    .print-table th:first-child,
    .print-table td:first-child{
        width:4%!important;
    }

    /* JENIS IKAN 15% */
    .print-table th:nth-child(2),
    .print-table td:nth-child(2){
        width:15%!important;
        text-align:center!important;
        font-size:7.5px!important;
        padding:2px!important;
        overflow-wrap:break-word!important;
    }

    /* KOLOM KECAMATAN DAN JUMLAH */
    .print-table th:not(:first-child):not(:nth-child(2)),
    .print-table td:not(:first-child):not(:nth-child(2)){
        width:8.1%!important;
        font-size:7.5px!important;
    }

    .print-table .rotate{
        writing-mode:vertical-rl!important;
        transform:rotate(180deg)!important;
        height:16mm!important;
        font-size:7px!important;
        white-space:nowrap!important;
    }

    .print-table thead{
        display:table-header-group!important;
    }

    .print-table tbody{
        display:table-row-group!important;
    }

    .print-table tr{
        page-break-inside:avoid!important;
    }

}

</style>

</head>

<body>

<?php include "index.php"; ?>

<main>

<div class="card p-3 mb-3 filter">

<form method="GET">

<div class="row g-3 align-items-end">

<div class="col-md-3">

<label class="form-label">
<b>Pilih Tahun</b>
</label>

<select name="tahun" class="form-select">

<?php foreach(['2024','2025','2026'] as $t): ?>

<option value="<?= $t ?>" <?= $tahun==$t?'selected':'' ?>>
<?= $t ?>
</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-3">

<label class="form-label">
<b>Pilih Periode</b>
</label>

<select name="periode" class="form-select">

<?php foreach(['Tahunan','TW I','TW II','TW III','TW IV'] as $p): ?>

<option value="<?= $p ?>" <?= $periode==$p?'selected':'' ?>>
<?= $p ?>
</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-2">

<button class="btn btn-primary w-100">
Tampilkan
</button>

</div>

<div class="col-md-2">

<button type="button"
onclick="window.print()"
class="btn btn-success w-100">
Cetak
</button>

</div>

</div>

</form>

</div>

<div class="card p-3 screen-only">

<h5 class="text-center fw-bold mb-1">
LAPORAN PRODUKSI PERIKANAN TANGKAP
</h5>

<div class="text-center mb-3">
KABUPATEN LANGKAT<br>
<b><?= $tahun ?> - <?= $periode ?></b>
</div>

<div class="table-responsive">
<table class="table table-bordered">
<thead class="table-light">

<tr>

<th rowspan="2">No</th>
<th rowspan="2">Jenis Ikan</th>

<th colspan="<?= count($lokasi)+1 ?>">
PRODUKSI PER KECAMATAN
</th>

</tr>

<tr>

<?php foreach($lokasi as $l): ?>

<th class="rotate"><?= $l ?></th>

<?php endforeach; ?>

<th>Jumlah</th>

</tr>

</thead>

<tbody>

<?php

$no=1;
$total_lokasi=array_fill_keys($lokasi,0);
$total_semua=0;

foreach($ikan_data as $ikan=>$nilai):

$jumlah=0;

?>

<tr>

<td><?= $no++ ?></td>

<td><?= htmlspecialchars($ikan) ?></td>

<?php foreach($lokasi as $l):

$produksi=$nilai[$l];
$jumlah+=$produksi;
$total_lokasi[$l]+=$produksi;

?>

<td>
<?= $produksi==0?'-':formatAngka($produksi) ?>
</td>

<?php endforeach; ?>

<td class="fw-bold">
<?= formatAngka($jumlah) ?>
</td>

</tr>

<?php

$total_semua+=$jumlah;

endforeach;

?>

<tr class="fw-bold table-light">

<td colspan="2">TOTAL</td>

<?php foreach($lokasi as $l): ?>

<td><?= formatAngka($total_lokasi[$l]) ?></td>

<?php endforeach; ?>

<td><?= formatAngka($total_semua) ?></td>

</tr>

</tbody>

</table>

</div>

</div>
<div class="print-only">
<?php foreach([$halaman1,$halaman2] as $nomor=>$daftar): ?>
<div class="print-page">
<div class="print-title">
LAPORAN PRODUKSI PERIKANAN TANGKAP
</div>
<div class="print-subtitle">
KABUPATEN LANGKAT<br>
<b><?= $tahun ?> - <?= $periode ?></b>
</div>
<table class="print-table">
<colgroup>
<col style="width:4%">
<col style="width:15%">
<?php foreach($lokasi as $l): ?>
<col style="width:8.1%">
<?php endforeach; ?>
<col style="width:8.1%">
</colgroup>
<thead>
<tr>
<th rowspan="2">No</th>
<th rowspan="2">Jenis Ikan</th>
<th colspan="<?= count($lokasi)+1 ?>">
PRODUKSI PER KECAMATAN
</th>
</tr>
<tr>
<?php foreach($lokasi as $l): ?>
<th class="rotate"><?= $l ?></th>
<?php endforeach; ?>
<th>Jumlah</th>
</tr>
</thead>
<tbody>
<?php
$no=$nomor==0?1:$batas+1;
foreach($daftar as $ikan):
$nilai=$ikan_data[$ikan];
$jumlah=0;
?>
<tr>
<td><?= $no++ ?></td>
<td><?= htmlspecialchars($ikan) ?></td>
<?php foreach($lokasi as $l):
$produksi=$nilai[$l];
$jumlah+=$produksi;
?>
<td>
<?= $produksi==0?'-':formatAngka($produksi) ?>
</td>
<?php endforeach; ?>
<td class="fw-bold">
<?= formatAngka($jumlah) ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php endforeach; ?>
</div>
</main>
</body>
</html>