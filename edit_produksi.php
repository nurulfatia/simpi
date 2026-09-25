<?php
include "auth.php";
include "koneksi.php";

$id = $_GET['id'] ?? '';

$data = mysqli_query($koneksi,
    "SELECT * FROM hasil_produksi WHERE id='$id'"
);

$row = mysqli_fetch_assoc($data);

if (!$row) {
    header("Location: hasil_produksi.php");
    exit();
}

if (isset($_POST['simpan'])) {

    $ikan = $_POST['ikan'];
    $tahun = $_POST['tahun'];
    $daerah = $_POST['daerah'];
    $produksi = $_POST['produksi'];

    mysqli_query($koneksi, "
        UPDATE hasil_produksi SET
        Nama_ikan='$ikan',
        Tahun='$tahun',
        Daerah='$daerah',
        produksi='$produksi'
        WHERE id='$id'
    ");

    header("Location: hasil_produksi.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit Hasil Produksi</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    margin:0;
    background:#f5f8fb;
    font-family:Arial,sans-serif;
}

main{
    margin-left:230px;
    padding:30px;
}

h1{
    color:#123f69;
    font-size:28px;
    font-weight:bold;
}

.card{
    background:white;
    border:1px solid #e1e7ed;
    border-radius:10px;
    box-shadow:0 2px 7px #ddd;
    max-width:700px;
}

.form-label{
    font-size:14px;
    font-weight:500;
}

.btn-biru{
    background:#1268b3 !important;
    color:white !important;
    border:none;
}

.btn-biru:hover{
    background:#0d5797 !important;
}

@media(max-width:768px){
    main{
        margin-left:0;
        padding:15px;
    }
}

</style>

</head>

<body>

<?php include "index.php"; ?>

<main>

<h1>Edit Hasil Produksi</h1>

<p class="text-muted">
Ubah data hasil produksi perikanan tangkap
</p>

<div class="card p-4 mt-3">

<form method="POST">

<div class="mb-3">

<label class="form-label">Nama Ikan</label>

<input
type="text"
name="ikan"
class="form-control"
value="<?= htmlspecialchars($row['Nama_ikan']) ?>"
required>

</div>


<div class="mb-3">

<label class="form-label">Tahun</label>

<select name="tahun" class="form-select" required>

<option value="2024" <?= $row['Tahun']=='2024'?'selected':'' ?>>
2024
</option>

<option value="2025" <?= $row['Tahun']=='2025'?'selected':'' ?>>
2025
</option>

<option value="2026" <?= $row['Tahun']=='2026'?'selected':'' ?>>
2026
</option>

</select>

</div>


<div class="mb-3">

<label class="form-label">Daerah</label>

<input
type="text"
name="daerah"
class="form-control"
value="<?= htmlspecialchars($row['Daerah']) ?>"
required>

</div>


<div class="mb-3">

<label class="form-label">
Jumlah Produksi (Ton)
</label>

<input
type="number"
name="produksi"
class="form-control"
step="0.01"
min="0"
value="<?= htmlspecialchars($row['produksi']) ?>"
required>
</div>
<button
type="submit"
name="simpan"
class="btn btn-biru px-4">
Simpan Perubahan
</button>
<a href="hasil_produksi.php"
class="btn btn-secondary px-4">
Kembali
</a>
</form>

</div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>