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

<div class="card shadow-sm border-0 bg-white">
  <div class="card-header d-flex justify-content-between align-items-center bg-white text-dark py-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-people-fill text-info me-2"></i> Daftar Master Customer</h5>
    <a href="<?= base_url('customer/create') ?>" class="btn btn-primary btn-sm fw-semibold">
      <i class="bi bi-plus-lg"></i> Tambah Customer
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 datatable" id="tblCustomer" style="font-size: 13.5px;">
          <thead class="table-light text-secondary">
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Nama Customer</th>
              <th>No. KTP</th>
              <th>Telepon</th>
              <th>Email</th>
              <th>Alamat Tempat Tinggal</th>
              <th class="text-center" style="width: 120px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $i => $c): ?>
                <tr>
                  <td class="text-center text-muted"><?= $i + 1 ?></td>
                  <td>
                    <div class="fw-bold text-dark"><?= esc($c['nama']) ?></div>
                  </td>
                  <td>
                    <span class="badge bg-secondary-subtle text-secondary border font-monospace"><?= esc($c['no_ktp']) ?></span>
                  </td>
                  <td class="fw-semibold text-dark"><?= esc($c['telepon'] ?? '-') ?></td>
                  <td>
                    <div class="text-dark"><?= esc($c['email'] ?? '-') ?></div>
                  </td>
                  <td class="text-muted">
                    <span title="<?= esc($c['alamat']) ?>">
                      <?= esc(substr($c['alamat'], 0, 35)) ?><?= strlen($c['alamat']) > 35 ? '...' : '' ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <a href="<?= base_url('customer/edit/' . $c['id_customer']) ?>" class="btn btn-outline-warning" title="Edit Profil">
                        <i class="bi bi-pencil-fill"></i>
                      </a>
                      <a href="<?= base_url('customer/delete/' . $c['id_customer']) ?>" 
                         class="btn btn-outline-danger" 
                         onclick="return confirm('Apakah Anda yakin ingin menghapus data customer <?= esc($c['nama']) ?>?')"
                         title="Hapus Permanent">
                        <i class="bi bi-trash3-fill"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center p-5 text-muted">
                    <i class="bi bi-person-x display-6 d-block mb-2 text-secondary"></i>
                    Belum ada data customer yang terdaftar.
                  </td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
    </div>
  </div>
</div>

<?= view('templates/footer') ?>