<?php $title = 'Data Customer'; ?>
<?= view('templates/header') ?>

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

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center bg-white text-dark">
    <h5 class="mb-0"><i class="bi bi-people-fill text-info me-2"></i> Daftar Customer</h5>
    <a href="<?= base_url('customer/create') ?>" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg"></i> Tambah Customer
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover table-hover mb-0 datatable" id="tblCustomer">
          <thead class="table-hover">
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Nama</th>
              <th>No. KTP</th>
              <th>Telepon</th>
              <th>Email</th>
              <th>Alamat</th>
              <th class="text-center" style="width: 120px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $i => $c): ?>
                <tr>
                  <td class="text-center"><?= $i + 1 ?></td>
                  <td class="fw-bold text-dark"><?= esc($c['nama']) ?></td>
                  <td><span class="badge bg-secondary"><?= esc($c['no_ktp']) ?></span></td>
                  <td><?= esc($c['telepon'] ?? '-') ?></td>
                  <td><?= esc($c['email'] ?? '-') ?></td>
                  <td class="text-muted small"><?= esc(substr($c['alamat'], 0, 35)) ?>...</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="<?= base_url('customer/edit/' . $c['id_customer']) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                        <i class="bi bi-pencil-fill"></i>
                      </a>
                      <a href="<?= base_url('customer/delete/' . $c['id_customer']) ?>" 
                         class="btn btn-sm btn-outline-danger confirm-delete" 
                         title="Hapus">
                        <i class="bi bi-trash3-fill"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center p-5 text-muted">Belum ada data customer yang terdaftar.</td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
    </div>
  </div>
</div>

<?= view('templates/footer') ?>