<?php $title = 'Data Supplier'; ?>
<?= view('templates/header') ?>

<div class="row">
    <div class="col-12">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white text-dark">
        <h5 class="mb-0"><i class="bi bi-building text-info me-2"></i> Daftar Supplier</h5>
        <a href="<?= base_url('supplier/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Tambah Supplier
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-hover mb-0 datatable" id="tblSupplier">
                <thead class="table-hover">
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Nama Supplier</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($suppliers)): ?>
                        <?php foreach ($suppliers as $i => $s): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td class="fw-bold text-dark"><?= esc($s['nama_supplier']) ?></td>
                            <td class="text-secondary small"><?= esc(substr($s['alamat'], 0, 40)) ?>...</td>
                            <td><?= esc($s['telepon'] ?? '-') ?></td>
                            <td><?= esc($s['email'] ?? '-') ?></td>
                            <td><?= esc($s['no_hp'] ?? '-') ?></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('supplier/edit/' . $s['id_supplier']) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= base_url('supplier/delete/' . $s['id_supplier']) ?>" class="btn btn-sm btn-outline-danger confirm-delete" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center p-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data supplier yang tersimpan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>