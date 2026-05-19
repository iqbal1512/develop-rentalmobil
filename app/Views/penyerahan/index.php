<?php $title = 'Kelola Penyerahan Mobil'; ?>
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

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-box-seam-fill text-info me-2"></i> Daftar Penyerahan Mobil
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-hover mb-0 datatable">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th>No Surat Jalan</th>
                                <th>Customer & Mobil</th>
                                <th>Tgl Serah Unit</th>
                                <th>Status Dokumen</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($penyerahan)) : ?>
                                <?php $no = 1; foreach ($penyerahan as $p) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <span class="text-info fw-bold">
                                                <?= $p['no_surat_jalan'] ?? '<small class="text-muted fst-italic">Non-Surat Jalan</small>' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-white fw-bold"><?= esc($p['nama_customer'] ?? 'Unknown') ?></div>
                                            <small class="text-secondary"><?= esc($p['nama_mobil'] ?? 'Unknown') ?></small>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($p['tgl_serah_unit'])) ?></td>
                                        <td>
                                            <span class="badge <?= $p['tgl_serah_stnk'] ? 'bg-success' : 'bg-warning text-dark' ?>">
                                                STNK: <?= $p['tgl_serah_stnk'] ? 'Selesai' : 'Proses' ?>
                                            </span>
                                            <span class="badge <?= $p['tgl_serah_bpkb'] ? 'bg-success' : 'bg-warning text-dark' ?> ms-1">
                                                BPKB: <?= $p['tgl_serah_bpkb'] ? 'Selesai' : 'Proses' ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="<?= base_url('penyerahan/edit/' . $p['id_penyerahan']) ?>" class="btn btn-sm btn-outline-warning" title="Edit Data">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?php if($p['metode_serah'] === 'diantar' && !empty($p['no_surat_jalan'])): ?>
                                                <a href="<?= base_url('penyerahan/cetakSuratJalan/' . $p['id_penyerahan']) ?>" target="_blank" class="btn btn-sm btn-outline-info" title="Cetak Surat Jalan">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                                <?php endif; ?>
                                                
                                                <?php if(!$p['tgl_serah_stnk']): ?>
                                                <form action="<?= base_url('penyerahan/updateStnk/' . $p['id_penyerahan']) ?>" method="post" class="d-inline" onsubmit="event.preventDefault(); confirmFormSubmit(this, 'Konfirmasi Serah STNK', 'Tandai STNK sudah diserahkan?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Serah STNK">
                                                        <i class="bi bi-card-heading"></i>
                                                    </button>
                                                </form>
                                                <?php endif; ?>
 
                                                <?php if(!$p['tgl_serah_bpkb']): ?>
                                                <form action="<?= base_url('penyerahan/updateBpkb/' . $p['id_penyerahan']) ?>" method="post" class="d-inline" onsubmit="event.preventDefault(); confirmFormSubmit(this, 'Konfirmasi Serah BPKB', 'Tandai BPKB sudah diserahkan?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Serah BPKB">
                                                        <i class="bi bi-journal-text"></i>
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-secondary">
                                            <i class="bi bi-box-seam fa-3x mb-3 d-block" style="font-size: 3rem;"></i>
                                            <span class="fst-italic text-muted">Belum ada data penyerahan mobil.</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>