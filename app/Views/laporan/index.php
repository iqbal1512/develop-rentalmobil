<?php $title = 'Laporan Showroom'; ?>
<?= view('templates/header') ?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-funnel-fill" style="color: var(--primary);"></i> Filter Laporan</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('laporan/generate') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label class="form-label">Jenis Laporan</label>
                        <select name="jenis_laporan" class="form-select" required>
                            <option value="">-- Pilih Laporan --</option>
                            <option value="pembelian">Laporan Pembelian</option>
                            <option value="penjualan">Laporan Penjualan</option>
                            <option value="pembayaran">Laporan Pembayaran Masuk</option>
                            <option value="pemesanan">Laporan Pemesanan</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Periode Mulai</label>
                        <input type="date" name="periode_start_date" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Periode Akhir</label>
                        <input type="date" name="periode_akhir_date" class="form-control" required>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-bar-graph"></i> Generate Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history" style="color: var(--primary);"></i> Riwayat Laporan Terakhir</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-hover mb-0 datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Jenis Laporan</th>
                                <th>Periode</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($laporan)): ?>
                                <?php foreach($laporan as $i => $l): ?>
                                <tr>
                                    <td><?= $i+1 ?></td>
                                    <td><span class="badge bg-info text-dark"><?= ucfirst($l['jenis_laporan']) ?></span></td>
                                    <td><?= date('d/m/Y', strtotime($l['periode_start_date'])) ?> - <?= date('d/m/Y', strtotime($l['periode_akhir_date'])) ?></td>
                                    <td><?= htmlspecialchars($l['nama_user'] ?? 'Admin') ?></td>
                                    <td>
                                        <a href="<?= base_url('laporan/cetak/' . $l['id_laporan']) ?>" target="_blank" class="btn btn-sm btn-outline-light" title="Cetak/Lihat">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat laporan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>
