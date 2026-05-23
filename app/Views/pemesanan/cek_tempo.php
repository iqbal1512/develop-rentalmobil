<?php $title = 'Cek Jatuh Tempo Pemesanan'; ?>
<?= view('templates/header') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-clock-history" style="color:var(--danger)"></i>
            Pemesanan Jatuh Tempo / Expired
        </h5>
        <a href="<?= base_url('pemesanan') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($expired)): ?>
        <div class="alert alert-warning m-3">
            <i class="bi bi-exclamation-triangle-fill"></i>
            Terdapat <strong><?= count($expired) ?></strong> pemesanan yang melewati jatuh tempo dan statusnya masih <em>menunggu</em>.
        </div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Mobil</th>
                        <th>Tgl Pesan</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($expired)): ?>
                        <?php foreach ($expired as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc($p['nama_customer'] ?? '-') ?></td>
                            <td><?= esc($p['nama_mobil'] ?? '-') ?> <small class="text-muted"><?= esc($p['tipe'] ?? '') ?></small></td>
                            <td><?= date('d/m/Y', strtotime($p['tgl_pesan'])) ?></td>
                            <td class="text-danger fw-bold"><?= date('d/m/Y', strtotime($p['tgl_jatuh_tempo'])) ?></td>
                            <td>
                                <span class="badge" style="background:var(--danger-light);color:var(--danger)">
                                    <?= ucfirst($p['status_pemesanan']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= base_url('pemesanan/detail/' . $p['id_pemesanan']) ?>"
                                   class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= base_url('pemesanan/batal/' . $p['id_pemesanan']) ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Batalkan pemesanan ini?')" title="Batalkan">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-check-circle" style="font-size:2rem;color:var(--success)"></i><br>
                                Tidak ada pemesanan yang melewati jatuh tempo.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>
