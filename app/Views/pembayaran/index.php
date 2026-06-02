<?php $title = 'Kelola Pembayaran'; ?>
<?= view('templates/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold text-dark">Data Transaksi Pembayaran</h4>
        <p class="text-muted small mb-0">Pantau arus kas masuk dan validasi bukti transfer customer.</p>
    </div>
    <?php if ($pending > 0): ?>
        <span class="badge bg-danger p-2 fs-6 animate__animated animate__pulse animate__infinite">
            <i class="bi bi-exclamation-circle-fill me-1"></i> <?= $pending ?> Transfer Menunggu Verifikasi
        </span>
    <?php endif; ?>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 bg-white text-dark">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size: 13.5px;">
                <thead class="table-light text-secondary">
                    <tr>
                        <th>No. Kwitansi</th>
                        <th>Tanggal</th>
                        <th>Customer / Unit</th>
                        <th>Metode</th>
                        <th>Jumlah Bayar</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pembayaran)): ?>
                        <?php foreach ($pembayaran as $p): ?>
                            <tr>
                                <td class="fw-bold font-monospace text-dark"><?= esc($p['no_kwitansi'] ?? 'BELUM TERBIT') ?></td>
                                <td class="text-muted"><?= date('d/m/Y', strtotime($p['tgl_bayar'])) ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($p['nama_customer']) ?></div>
                                    <small class="text-muted"><?= esc($p['nama_mobil']) ?></small>
                                </td>
                                <td>
                                    <span class="badge <?= $p['metode_bayar'] === 'transfer' ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-success-subtle text-success border border-success-subtle' ?>">
                                        <?= strtoupper($p['metode_bayar']) ?>
                                    </span>
                                </td>
                                <td class="text-success fw-bold">Rp<?= number_format($p['jumlah_bayar'], 0, ',', '.') ?></td>
                                <td>
                                    <?php 
                                        $statusClass = [
                                            'menunggu' => 'bg-warning text-dark',
                                            'terverifikasi' => 'bg-success',
                                            'ditolak' => 'bg-danger'
                                        ][$p['status_verifikasi']] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= strtoupper($p['status_verifikasi']) ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('pembayaran/detail/' . $p['id_pembayaran']) ?>" class="btn btn-outline-dark" title="Detail Lengkap">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        
                                        <?php if ($p['status_verifikasi'] === 'menunggu' && $p['metode_bayar'] === 'transfer'): ?>
                                            <a href="<?= base_url('pembayaran/verifikasi/' . $p['id_pembayaran']) ?>" class="btn btn-success" onclick="return confirm('Apakah Anda sudah mengecek mutasi bank dan menyetujui pembayaran ini?')" title="Setujui/Verifikasi">
                                                <i class="bi bi-check-lg"></i>
                                            </a>
                                            <a href="<?= base_url('pembayaran/tolak/' . $p['id_pembayaran']) ?>" class="btn btn-danger" onclick="return confirm('Tolak bukti transfer ini?')" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($p['status_verifikasi'] === 'terverifikasi'): ?>
                                            <a href="<?= base_url('pembayaran/cetakKwitansi/' . $p['id_pembayaran']) ?>" target="_blank" class="btn btn-outline-secondary" title="Cetak Kwitansi">
                                                <i class="bi bi-printer-fill"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted p-4">Belum ada rekaman data pembayaran masuk.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>