<!-- app/Views/penyerahan/surat_jalan.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: sans-serif; color: #000; line-height: 1.4; }
        .sj-container { width: 100%; border: 1px solid #000; padding: 30px; }
        .sj-header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .table-sj { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table-sj td { padding: 8px; vertical-align: top; }
        .footer-sig { margin-top: 50px; display: flex; justify-content: space-between; text-align: center; }
        .sig-box { width: 30%; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Cetak Surat Jalan</button>
    </div>

    <div class="sj-container">
        <div class="sj-header">
            <h2 style="margin:0;">AUTOPRIME SHOWROOM</h2>
            <p style="margin:5px 0;">Jl. Raya Mobil No. 123, Jakarta | Telp: (021) 12345678</p>
        </div>

        <h3 style="text-align:center; text-decoration: underline;">SURAT JALAN PENYERAHAN UNIT</h3>
        <p style="text-align:right;">No: <strong><?= $penyerahan['no_surat_jalan'] ?></strong></p>

        <table class="table-sj">
            <tr>
                <td width="20%">Diterima Dari</td><td>: AutoPrime Showroom (Admin: <?= $penyerahan['nama_petugas'] ?>)</td>
                <td width="20%">Kepada Yth.</td><td>: <?= $penyerahan['nama_customer'] ?></td>
            </tr>
            <tr>
                <td>Nama Mobil</td><td>: <?= $penyerahan['nama_mobil'] ?> (<?= $penyerahan['warna'] ?>)</td>
                <td>No. Polisi</td><td>: <?= $penyerahan['no_polisi'] ?></td>
            </tr>
            <tr>
                <td>Metode</td><td>: <?= ucfirst($penyerahan['metode_serah']) ?></td>
                <td>Alamat Antar</td><td>: <?= $penyerahan['alamat_antar'] ?? '-' ?></td>
            </tr>
        </table>

        <p style="margin-top:20px;"><strong>Kondisi Unit:</strong> <?= $penyerahan['kondisi_serah'] ?></p>
        <p><strong>Catatan:</strong> <?= $penyerahan['catatan_petugas'] ?? '-' ?></p>

        <div class="footer-sig">
            <div class="sig-box">
                <p>Hormat Kami,</p><br><br><br>
                <p>( <?= $penyerahan['nama_petugas'] ?> )</p>
            </div>
            <div class="sig-box">
                <p>Kurir/Driver,</p><br><br><br>
                <p>( .................... )</p>
            </div>
            <div class="sig-box">
                <p>Penerima,</p><br><br><br>
                <p>( <?= $penyerahan['nama_customer'] ?> )</p>
            </div>
        </div>
    </div>
</body>
</html>