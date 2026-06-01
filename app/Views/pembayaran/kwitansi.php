<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .kwitansi-container { width: 800px; margin: 0 auto; border: 2px solid #000; padding: 20px; position: relative; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #333; }
        .header p { margin: 5px 0 0; }
        .no-kwitansi { text-align: right; }
        .content table { width: 100%; border-collapse: collapse; }
        .content table td { padding: 8px 0; vertical-align: top; }
        .content table td:first-child { width: 25%; font-weight: bold; }
        .content table td:nth-child(2) { width: 2%; }
        .amount { background: #f0f0f0; padding: 10px; font-size: 18px; font-weight: bold; border: 1px solid #ccc; display: inline-block; margin-top: 20px; }
        .signature { margin-top: 50px; text-align: right; }
        .signature-line { margin-top: 80px; text-decoration: underline; font-weight: bold; }
    </style>
</head>
<body onload="window.print()">

<div class="kwitansi-container">
    <div class="header">
        <div>
            <h1>AUTOPRIME SHOWROOM</h1>
            <p>Jl. Raya Mobil No. 123, Jakarta<br>Telp: (021) 12345678</p>
        </div>
        <div class="no-kwitansi">
            <h2>KWITANSI</h2>
            <p>No. <strong><?= $pembayaran['no_kwitansi'] ?></strong></p>
        </div>
    </div>

    <div class="content">
        <table>
            <tr>
                <td>Telah terima dari</td>
                <td>:</td>
                <td><?= htmlspecialchars($pembayaran['nama_customer'] ?? 'Customer') ?></td>
            </tr>
            <tr>
                <td>Uang Sejumlah</td>
                <td>:</td>
                <td style="font-style: italic;">
                    <?php
                        if (!function_exists('terbilang')) {
                            function terbilang($angka) {
                                $angka = abs($angka);
                                $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
                                $terbilang = "";
                                if ($angka < 12) {
                                    $terbilang = " " . $baca[$angka];
                                } else if ($angka < 20) {
                                    $terbilang = terbilang($angka - 10) . " Belas";
                                } else if ($angka < 100) {
                                    $terbilang = terbilang($angka / 10) . " Puluh" . terbilang($angka % 10);
                                } else if ($angka < 200) {
                                    $terbilang = " Seratus" . terbilang($angka - 100);
                                } else if ($angka < 1000) {
                                    $terbilang = terbilang($angka / 100) . " Ratus" . terbilang($angka % 100);
                                } else if ($angka < 2000) {
                                    $terbilang = " Seribu" . terbilang($angka - 1000);
                                } else if ($angka < 1000000) {
                                    $terbilang = terbilang($angka / 1000) . " Ribu" . terbilang($angka % 1000);
                                } else if ($angka < 1000000000) {
                                    $terbilang = terbilang($angka / 1000000) . " Juta" . terbilang($angka % 1000000);
                                }
                                return $terbilang;
                            }
                        }
                        echo terbilang($pembayaran['jumlah_bayar']) . " Rupiah";
                    ?>
                </td>
            </tr>
            <tr>
                <td>Untuk Pembayaran</td>
                <td>:</td>
                <td><?= strtoupper(str_replace('_', ' ', $pembayaran['jenis_pembayaran'])) ?> Pembelian Mobil 
                    <?= htmlspecialchars($pembayaran['nama_mobil'] ?? '') ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="amount">
        Terbilang: Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?>
    </div>

    <div class="signature">
        Jakarta, <?= date('d F Y', strtotime($pembayaran['tgl_bayar'])) ?><br><br><br><br>
        <div class="signature-line">( <?= session()->get('nama') ?? 'Finance/Kasir' ?> )</div>
    </div>
</div>

</body>
</html>
