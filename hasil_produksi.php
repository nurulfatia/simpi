<?php
include "auth.php";
include "koneksi.php";

$tahun   = $_GET['tahun'] ?? '2024';
$periode = $_GET['periode'] ?? 'Tahunan';
$cari    = $_GET['cari'] ?? '';
$page    = max(1, (int)($_GET['page'] ?? 1));
$limit   = 10;

$lokasi = [
    'Babalan','Bahorok','Batang Serangan','Besitang','Binjai',
    'Brandan Barat','Gebang','Hinai','Kuala','Kutambaru',
    'Padang Tualang','Pangkalan Susu','Pematang Jaya','Salapian',
    'Sawit Seberang','Secanggang','Sei Bingai','Sei Lepan',
    'Selesai','Sirapit','Stabat','Tanjung Pura','Wampu'
];

$daftar_ikan = mysqli_query($koneksi,"
    SELECT DISTINCT Nama_ikan
    FROM hasil_produksi
    WHERE Nama_ikan IS NOT NULL AND Nama_ikan != ''
    ORDER BY Nama_ikan ASC
");
if(isset($_GET['hapus'])){
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi,"DELETE FROM hasil_produksi WHERE id='$id'");
    header("Location: hasil_produksi.php?tahun=$tahun&periode=".urlencode($periode));
    exit;
}
if(isset($_POST['simpan'])){

    $t = mysqli_real_escape_string($koneksi,$_POST['tahun']);
    $p = mysqli_real_escape_string($koneksi,$_POST['periode']);
    $i = mysqli_real_escape_string($koneksi,$_POST['ikan']);
    $d = mysqli_real_escape_string($koneksi,$_POST['daerah']);
    $n = mysqli_real_escape_string($koneksi,$_POST['produksi']);

    $tw = $p == 'Tahunan' ? '' : $p;

    mysqli_query($koneksi,"
        INSERT INTO hasil_produksi
        (Tahun,Triwulan,Nama_ikan,Daerah,produksi)
        VALUES('$t','$tw','$i','$d','$n')
    ");

    header("Location: hasil_produksi.php?tahun=$t&periode=".urlencode($p));
    exit;
}
if(isset($_POST['update'])){

    $id = (int)$_POST['id'];
    $i = mysqli_real_escape_string($koneksi,$_POST['ikan']);
    $d = mysqli_real_escape_string($koneksi,$_POST['daerah']);
    $n = mysqli_real_escape_string($koneksi,$_POST['produksi']);

    mysqli_query($koneksi,"
        UPDATE hasil_produksi SET
        Nama_ikan='$i', Daerah='$d', produksi='$n'
        WHERE id='$id'
    ");

    header("Location: hasil_produksi.php?tahun=$tahun&periode=".urlencode($periode));
    exit;
}
$where = "WHERE Tahun='$tahun'";

$where .= $periode == 'Tahunan'
    ? " AND (Triwulan='' OR Triwulan IS NULL)"
    : " AND Triwulan='$periode'";

if($cari != ''){
    $c = mysqli_real_escape_string($koneksi,$cari);
    $where .= " AND (Nama_ikan LIKE '%$c%' OR Daerah LIKE '%$c%')";
}
$total = mysqli_fetch_assoc(mysqli_query($koneksi,"
    SELECT COUNT(*) total FROM hasil_produksi $where
"))['total'];

$totalHalaman = max(1,ceil($total/$limit));
$page = min($page,$totalHalaman);
$mulai = ($page-1)*$limit;

$data = mysqli_query($koneksi,"
    SELECT * FROM hasil_produksi
    $where
    ORDER BY Nama_ikan,Daerah
    LIMIT $mulai,$limit
");
$edit = null;

if(isset($_GET['edit'])){
    $id = (int)$_GET['edit'];
    $edit = mysqli_fetch_assoc(mysqli_query($koneksi,"
        SELECT * FROM hasil_produksi WHERE id='$id'
    "));
}
?>
<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Data Produksi</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{background:#f5f8fb;font-family:Arial,sans-serif}
main{margin-left:230px;padding:20px}
.card{border:0;border-radius:10px;padding:20px}
.table{font-size:14px}
.table th,.table td{vertical-align:middle}
.pagination .page-link{margin:0 3px;border-radius:6px!important}
@media(max-width:768px){main{margin-left:0;padding:10px}}

.form-label{font-size:13px;font-weight:600;margin-bottom:7px}
.form-control,.form-select{font-size:13px;border-radius:8px;padding:10px 12px}
.input-group-text{font-size:13px}
</style>

</head>

<body>

<?php include "index.php"; ?>

<main>

<h4 class="fw-bold">Data Produksi Perikanan Tangkap</h4>

<p class="text-muted">
Dinas Perikanan dan Kelautan Kabupaten Langkat
</p>
<div class="card mb-4">

<form method="GET">

<div class="row g-3 align-items-end">

<div class="col-md-3">
<label class="form-label"><b>Pilih Tahun</b></label>

<select name="tahun" class="form-select">
<?php foreach(['2024','2025','2026'] as $t): ?>
<option value="<?= $t ?>" <?= $tahun==$t?'selected':'' ?>>
<?= $t ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div class="col-md-3">
<label class="form-label"><b>Pilih Periode</b></label>

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
<i class="bi bi-search"></i> Tampilkan
</button>
</div>

<div class="col-md-2">
<button type="button" class="btn btn-success w-100"
data-bs-toggle="collapse" data-bs-target="#formData">
<i class="bi bi-plus-lg"></i> Tambah
</button>
</div>

</div>

</form>

</div>

<div class="collapse <?= $edit?'show':'' ?>" id="formData">
<div class="card mb-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">
                <i class="bi bi-clipboard-data me-2"></i>
                <?= $edit?'Edit Data Produksi':'Tambah Data Produksi' ?>
            </h5>
            <small class="text-muted">Lengkapi data produksi perikanan tangkap</small>
        </div>
    </div>
    <form method="POST">

        <?php if($edit): ?>
        <input type="hidden" name="id" value="<?= $edit['id'] ?>">
        <?php endif; ?>

        <div class="row g-3">

            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select" required>
                    <?php foreach(['2024','2025','2026'] as $t): ?>
                    <option value="<?= $t ?>" <?= ($edit ? $edit['Tahun'] : $tahun)==$t?'selected':'' ?>>
                        <?= $t ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Periode</label>
                <select name="periode" class="form-select" required>
                    <?php foreach(['Tahunan','TW I','TW II','TW III','TW IV'] as $p): ?>
                    <option value="<?= $p ?>" <?= ($edit ? ($edit['Triwulan'] ?: 'Tahunan') : $periode)==$p?'selected':'' ?>>
                        <?= $p ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Jenis Ikan</label>
                <input list="daftarIkan" type="text" name="ikan"
                       class="form-control"
                       placeholder="Cari atau ketik jenis ikan..."
                       value="<?= $edit?htmlspecialchars($edit['Nama_ikan']):'' ?>"
                       required>
                <datalist id="daftarIkan">
                    <?php while($i=mysqli_fetch_assoc($daftar_ikan)): ?>
                    <option value="<?= htmlspecialchars($i['Nama_ikan']) ?>">
                    <?php endwhile; ?>
                </datalist>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kecamatan</label>
                <select name="daerah" class="form-select" required>
                    <option value="">Pilih Kecamatan</option>
                    <?php foreach($lokasi as $l): ?>
                    <option value="<?= htmlspecialchars($l) ?>" <?= $edit && $edit['Daerah']==$l?'selected':'' ?>>
                        <?= htmlspecialchars($l) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Produksi (Ton)</label>
                <div class="input-group">
                    <input type="number" step="any" name="produksi"
                           class="form-control"
                           placeholder="Masukkan jumlah produksi"
                           value="<?= $edit?htmlspecialchars($edit['produksi']):'' ?>"
                           required>
                    <span class="input-group-text">Ton</span>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="hasil_produksi.php?tahun=<?= $tahun ?>&periode=<?= urlencode($periode) ?>"
               class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" name="<?= $edit?'update':'simpan' ?>" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                <?= $edit?'Update':'Simpan' ?>
            </button>
        </div>

    </form>
</div>
</div>

<div class="card">
<div class="d-flex justify-content-between mb-3">
<h5 class="fw-bold mb-0">
Data <?= htmlspecialchars($tahun) ?> - <?= htmlspecialchars($periode) ?>
</h5>
<form method="GET" class="d-flex">
<input type="hidden" name="tahun" value="<?= htmlspecialchars($tahun) ?>">
<input type="hidden" name="periode" value="<?= htmlspecialchars($periode) ?>">
<input type="text" name="cari" class="form-control me-2"
placeholder="Cari ikan atau kecamatan..."
value="<?= htmlspecialchars($cari) ?>">
<button class="btn btn-primary">
<i class="bi bi-search"></i>
</button>
</form>
</div>

<div class="table-responsive">
<table class="table table-bordered table-hover">
<thead class="table-light">
<tr>
<th>No</th>
<th>Jenis Ikan</th>
<th>Kecamatan</th>
<th>Produksi (Ton)</th>
<th>Tahun</th>
<th>Periode</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>
<?php if(mysqli_num_rows($data)>0): ?>
<?php $no=$mulai+1; ?>
<?php while($row=mysqli_fetch_assoc($data)): ?>

<tr>

<td><?= $no++ ?></td>

<td><?= htmlspecialchars($row['Nama_ikan']) ?></td>

<td><?= htmlspecialchars($row['Daerah']) ?></td>

<td><?= htmlspecialchars($row['produksi']) ?></td>

<td><?= htmlspecialchars($row['Tahun']) ?></td>

<td><?= !empty($row['Triwulan'])?htmlspecialchars($row['Triwulan']):'Tahunan' ?></td>

<td>

<a class="btn btn-primary btn-sm"
href="hasil_produksi.php?tahun=<?= $tahun ?>&periode=<?= urlencode($periode) ?>&page=<?= $page ?>&edit=<?= $row['id'] ?>">

<i class="bi bi-pencil"></i>
</a>

<a class="btn btn-danger btn-sm"
onclick="return confirm('Yakin ingin menghapus data ini?')"
href="hasil_produksi.php?tahun=<?= $tahun ?>&periode=<?= urlencode($periode) ?>&hapus=<?= $row['id'] ?>">

<i class="bi bi-trash"></i>
</a>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>
<td colspan="7" class="text-center">
Data tidak ditemukan
</td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<!-- PAGINATION -->

<?php if($totalHalaman>1): ?>

<div class="d-flex justify-content-center mt-3">

<ul class="pagination pagination-sm">

<?php
$awal=max(1,$page-2);
$akhir=min($totalHalaman,$page+2);
?>

<?php if($page>1): ?>

<li class="page-item">
<a class="page-link"
href="?tahun=<?= $tahun ?>&periode=<?= urlencode($periode) ?>&cari=<?= urlencode($cari) ?>&page=<?= $page-1 ?>">
‹
</a>
</li>

<?php endif; ?>

<?php for($i=$awal;$i<=$akhir;$i++): ?>

<li class="page-item <?= $i==$page?'active':'' ?>">

<a class="page-link"
href="?tahun=<?= $tahun ?>&periode=<?= urlencode($periode) ?>&cari=<?= urlencode($cari) ?>&page=<?= $i ?>">

<?= $i ?>

</a>

</li>

<?php endfor; ?>

<?php if($page<$totalHalaman): ?>

<li class="page-item">

<a class="page-link"
href="?tahun=<?= $tahun ?>&periode=<?= urlencode($periode) ?>&cari=<?= urlencode($cari) ?>&page=<?= $page+1 ?>">

›
</a>

</li>

<?php endif; ?>

</ul>

</div>

<?php endif; ?>

</div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>