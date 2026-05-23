<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 22px; }
        .header p { margin: 5px 0 0; }
        .periode { text-align: center; margin-bottom: 20px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 50px; text-align: right; padding-right: 50px; }
        .footer .sign-line { margin-top: 80px; text-decoration: underline; font-weight: bold; }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="header">
    <h2>AUTOPRIME SHOWROOM</h2>
    <p>Jl. Raya Mobil No. 123, Jakarta | Telp: (021) 12345678</p>
    <h3><?= strtoupper($title) ?></h3>
</div>

<div class="periode">
    Periode: <?= date('d M Y', strtotime($laporan['periode_start_date'])) ?> s/d <?= date('d M Y', strtotime($laporan['periode_akhir_date'])) ?>
</div>

<?php if ($laporan['jenis_laporan'] === 'pembelian'): ?>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Beli</th>
                <th>Supplier</th>
                <th>Mobil</th>
                <th>Status Beli</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= date('d/m/Y', strtotime($row['tgl_pembelian'])) ?></td>
                <td><?= htmlspecialchars($row['nama_supplier'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['nama_mobil'] ?? '-') ?></td>
                <td><?= ucfirst($row['status_pembelian'] ?? '-') ?></td>
                <td class="text-right">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">TOTAL PEMBELIAN</th>
                <th class="text-right">Rp <?= number_format($total, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

<?php elseif ($laporan['jenis_laporan'] === 'penjualan'): ?>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Jual</th>
                <th>Customer</th>
                <th>Mobil</th>
                <th>Total Harga</th>
                <th>Dibayar</th>
                <th>Sisa</th>
                <th>Status Lunas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= date('d/m/Y', strtotime($row['tgl_penjualan'])) ?></td>
                <td><?= htmlspecialchars($row['nama_customer']) ?></td>
                <td><?= htmlspecialchars($row['nama_mobil']) ?></td>
                <td class="text-right">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($row['total_dibayar'], 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($row['sisa_tagihan'], 0, ',', '.') ?></td>
                <td><?= ucfirst(str_replace('_', ' ', $row['status_lunas'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">TOTAL PENJUALAN</th>
                <th class="text-right">Rp <?= number_format($total, 0, ',', '.') ?></th>
                <th colspan="3"></th>
            </tr>
        </tfoot>
    </table>

<?php elseif ($laporan['jenis_laporan'] === 'pembayaran'): ?>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Bayar</th>
                <th>Customer</th>
                <th>Jenis</th>
                <th>Metode</th>
                <th>No Kwitansi</th>
                <th>Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= date('d/m/Y', strtotime($row['tgl_bayar'])) ?></td>
                <td><?= htmlspecialchars($row['nama_customer'] ?? 'Unknown') ?></td>
                <td><?= strtoupper(str_replace('_', ' ', $row['jenis_pembayaran'])) ?></td>
                <td><?= ucfirst($row['metode_bayar']) ?></td>
                <td><?= htmlspecialchars($row['no_kwitansi'] ?? '-') ?></td>
                <td class="text-right">Rp <?= number_format($row['jumlah_bayar'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">TOTAL PEMBAYARAN MASUK</th>
                <th class="text-right">Rp <?= number_format($total, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

<?php elseif ($laporan['jenis_laporan'] === 'pemesanan'): ?>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Pesan</th>
                <th>Customer</th>
                <th>Mobil</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= date('d/m/Y', strtotime($row['tgl_pesan'])) ?></td>
                <td><?= htmlspecialchars($row['nama_customer']) ?></td>
                <td><?= htmlspecialchars($row['nama_mobil']) ?></td>
                <td><?= ucfirst($row['status_pemesanan']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">TOTAL TRANSAKSI PEMESANAN</th>
                <th class="text-center"><?= $total ?> Transaksi</th>
            </tr>
        </tfoot>
    </table>

<?php endif; ?>

<div class="footer">
    <p>Dibuat Oleh,</p>
    <div class="sign-line">( <?= htmlspecialchars($laporan['nama_user'] ?? 'Administrator') ?> )</div>
</div>

</body>
</html>
