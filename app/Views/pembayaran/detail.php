<?php $title = 'Detail Transaksi Pembayaran'; ?>
<?= view('templates/header') ?>

<div class="row g-4 justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm border-0 bg-white text-dark">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-receipt text-muted me-2"></i> Riwayat Kas Masuk #<?= $pembayaran['id_pembayaran'] ?></h5>
                <a href="<?= base_url('pembayaran') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            <div class="card-body">
                <table class="table table-striped align-middle text-dark mb-4" style="font-size: 14px">
                    <tr>
                        <td class="text-muted" style="width: 160px;">Nomor Kwitansi</td>
                        <td class="fw-bold font-monospace text-primary">: <?= esc($pembayaran['no_kwitansi'] ?? 'BELUM TERSEDIA (BELUM DIVERIFIKASI)') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Setor</td>
                        <td>: <?= date('d F Y', strtotime($pembayaran['tgl_bayar'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nominal Pembayaran</td>
                        <td class="text-success fw-bold">: Rp<?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Metode Bayar</td>
                        <td>: <span class="badge bg-secondary"><?= strtoupper($pembayaran['metode_bayar']) ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alokasi Dana</td>
                        <td>: <span class="badge bg-outline-dark border text-dark"><?= strtoupper($pembayaran['jenis_pembayaran']) ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status Validasi Bank</td>
                        <td>: 
                            <?php 
                                $vClass = ['menunggu'=>'bg-warning text-dark','terverifikasi'=>'bg-success','ditolak'=>'bg-danger'][$pembayaran['status_verifikasi']]??'bg-secondary';
                            ?>
                            <span class="badge <?= $vClass ?>"><?= strtoupper($pembayaran['status_verifikasi']) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Catatan/Keterangan</td>
                        <td>: <span class="text-muted italic"><?= esc($pembayaran['keterangan'] ?? '-') ?></span></td>
                    </tr>
                </table>

                <?php if ($pembayaran['metode_bayar'] === 'transfer' && !empty($pembayaran['bukti_transfer'])): ?>
                    <div class="p-3 border rounded text-center bg-light">
                        <h6 class="fw-bold text-secondary text-start mb-2"><i class="bi bi-image"></i> Berkas Lampiran Bukti Transfer:</h6>
                        <img src="<?= base_url('uploads/bukti/' . $pembayaran['bukti_transfer']) ?>" class="img-fluid rounded border shadow-xs" style="max-height: 450px;" alt="Bukti Transfer">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>