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
            <h2>KWITANSI PENGELUARAN</h2>
            <p>No. <strong><?= $pembelian['no_kwitansi'] ?></strong></p>
        </div>
    </div>

    <div class="content">
        <table>
            <tr>
                <td>Telah dibayarkan kepada</td>
                <td>:</td>
                <td><?= htmlspecialchars($pembelian['nama_supplier'] ?? 'Supplier') ?></td>
            </tr>
            <tr>
                <td>Uang Sejumlah</td>
                <td>:</td>
                <td style="font-style: italic;">
                    <?php
                        if (!function_exists('terbilang_pembelian')) {
                            function terbilang_pembelian($angka) {
                                $angka = abs($angka);
                                $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
                                $terbilang = "";
                                if ($angka < 12) {
                                    $terbilang = " " . $baca[$angka];
                                } else if ($angka < 20) {
                                    $terbilang = terbilang_pembelian($angka - 10) . " Belas";
                                } else if ($angka < 100) {
                                    $terbilang = terbilang_pembelian($angka / 10) . " Puluh" . terbilang_pembelian($angka % 10);
                                } else if ($angka < 200) {
                                    $terbilang = " Seratus" . terbilang_pembelian($angka - 100);
                                } else if ($angka < 1000) {
                                    $terbilang = terbilang_pembelian($angka / 100) . " Ratus" . terbilang_pembelian($angka % 100);
                                } else if ($angka < 2000) {
                                    $terbilang = " Seribu" . terbilang_pembelian($angka - 1000);
                                } else if ($angka < 1000000) {
                                    $terbilang = terbilang_pembelian($angka / 1000) . " Ribu" . terbilang_pembelian($angka % 1000);
                                } else if ($angka < 1000000000) {
                                    $terbilang = terbilang_pembelian($angka / 1000000) . " Juta" . terbilang_pembelian($angka % 1000000);
                                }
                                return $terbilang;
                            }
                        }
                        echo trim(terbilang_pembelian($pembelian['total_harga'])) . " Rupiah";
                    ?>
                </td>
            </tr>
            <tr>
                <td>Untuk Pembayaran</td>
                <td>:</td>
                <td>Pembelian <?= $pembelian['jumlah_pembelian'] ?> Unit Mobil 
                    <strong><?= htmlspecialchars($pembelian['nama_mobil'] ?? '') ?></strong> (<?= htmlspecialchars($pembelian['warna'] ?? '-') ?>)
                </td>
            </tr>
            <tr>
                <td>Metode Pembayaran</td>
                <td>:</td>
                <td style="text-transform: uppercase;"><?= esc($pembelian['metode_bayar'] ?? 'tunai') ?></td>
            </tr>
        </table>
    </div>

    <div class="amount">
        Total: Rp <?= number_format($pembelian['total_harga'], 0, ',', '.') ?>
    </div>

    <div class="signature">
        Jakarta, <?= date('d F Y', strtotime($pembelian['tgl_pembelian'])) ?><br><br><br><br>
        <div class="signature-line">( <?= esc($pembelian['nama_user'] ?? 'Finance/Kasir') ?> )</div>
    </div>
</div>

</body>
</html>
