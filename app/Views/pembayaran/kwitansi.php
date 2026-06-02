<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; color: #000; padding: 20px; font-size: 13px; line-height: 1.4; }
        .wrapper { width: 100%; max-width: 750px; margin: 0 auto; border: 2px dashed #000; padding: 25px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .logo-title h2 { margin: 0; font-size: 20px; letter-spacing: 1px; }
        .logo-title p { margin: 3px 0 0 0; font-size: 11px; }
        .kw-number { text-align: right; }
        .kw-number h3 { margin: 0; font-size: 18px; }
        .content-table { width: 100%; margin-bottom: 25px; }
        .content-table td { padding: 6px 0; vertical-align: top; }
        .line-under { border-bottom: 1px dotted #000; font-weight: bold; }
        .footer-sign { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 30px; }
        .terbilang-box { background: #eee; padding: 8px 12px; font-style: italic; font-weight: bold; font-size: 12px; display: inline-block; border: 1px solid #000; }
        .amount-box { font-size: 18px; font-weight: bold; border: 2px solid #000; padding: 6px 15px; display: inline-block; margin-top: 10px; }
        .sign-area { text-align: center; width: 200px; }
        .sign-space { height: 70px; }
        @media print { .no-print { display: none; } body { padding: 0; } .wrapper { border: none; } }
    </style>
</head>
<body>

<div class="no-print" style="max-width: 750px; margin: 0 auto 15px auto; text-align: right;">
    <button onclick="window.print();" style="padding: 6px 15px; background: #000; color: #fff; cursor: pointer; font-weight: bold;">CETAK NOTA (PRINT)</button>
    <button onclick="window.close();" style="padding: 6px 15px; background: #666; color: #fff; cursor: pointer;">TUTUP</button>
</div>

<div class="wrapper">
    <div class="header">
        <div class="logo-title">
            <h2>SHOWROOM MOBIL BAROKAH</h2>
            <p>Jl. Jend. Sudirman No. 45, Kav. B, Kota Tangerang</p>
        </div>
        <div class="kw-number">
            <h3>KWITANSI RESMI</h3>
            <p>No: <?= esc($pembayaran['no_kwitansi']) ?></p>
        </div>
    </div>

    <table class="content-table">
        <tr>
            <td style="width: 180px;">Telah Diterima Dari</td>
            <td style="width: 15px;">:</td>
            <td class="line-under"><?= esc(strtoupper($pembayaran['nama_customer'])) ?></td>
        </tr>
        <tr>
            <td>Banyaknya Uang</td>
            <td>:</td>
            <td class="line-under" style="font-size:12px;">
                ### Rupiah Terlampir Pada Nominal Box Dibawah ###
            </td>
        </tr>
        <tr>
            <td>Untuk Pembayaran</td>
            <td>:</td>
            <td class="line-under">
                <?= esc(str_replace('_', ' ', strtoupper($pembayaran['jenis_pembayaran']))) ?> UNIT KENDARAAN: <?= esc(strtoupper($pembayaran['nama_mobil'])) ?> (METODE: <?= esc(strtoupper($pembayaran['metode_bayar'])) ?>)
            </td>
        </tr>
        <?php if(!empty($pembayaran['keterangan'])): ?>
        <tr>
            <td>Keterangan Memo</td>
            <td>:</td>
            <td style="border-bottom: 1px dotted #000; font-style: italic;"><?= esc($pembayaran['keterangan']) ?></td>
        </tr>
        <?php endif; ?>
    </table>

    <div class="footer-sign">
        <div>
            <div class="terbilang-box">Tanggal Setor: <?= date('d/m/Y', strtotime($pembayaran['tgl_bayar'])) ?></div>
            <br>
            <div class="amount-box">RP. <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?>,-</div>
        </div>
        <div class="sign-area">
            <p>Tangerang, <?= date('d M Y') ?></p>
            <p>Kasir Operasional,</p>
            <div class="sign-space"></div>
            <p style="text-decoration: underline; font-weight: bold;">( <?= esc($pembayaran['nama_user'] ?? 'Administrator') ?> )</p>
        </div>
    </div>
</div>

<script>
    // Otomatis mentrigger dialog printer saat dokumen kwitansi selesai diload browser
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => { window.print(); }, 500);
    });
</script>
</body>
</html>