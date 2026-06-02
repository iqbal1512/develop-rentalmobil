<?php $title = 'Pemesanan Mobil'; ?>
<?= view('templates/header') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm m-3">
  <div class="card-header d-flex justify-content-between align-items-center bg-white text-dark">
    <h5 class="mb-0"><i class="bi bi-calendar-check-fill text-info me-2"></i> Daftar Pemesanan Mobil</h5>
    <div class="d-flex gap-2">
      <a href="<?= base_url('pemesanan/create') ?>" class="btn btn-primary btn-sm">Tambah Pesanan</a>
    </div>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0 datatable" id="tblPemesananLancar">
          <thead>
            <tr>
              <th class="text-center">#</th> 
              <th>Customer</th> 
              <th>Mobil</th> 
              <th>Tgl Pesan</th> 
              <th>Jatuh Tempo</th> 
              <th>Harga Jadi</th> 
              <th>Nilai Tanda Jadi</th> 
              <th>DP Min (30%)</th> 
              <th class="text-center">Status</th> 
              <th class="text-center">Aksi</th> 
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pemesanan)): ?>
                <?php foreach ($pemesanan as $i => $p): ?>
                <?php 
                  // Hitung nilai nominal DP 30% secara dinamis untuk ditampilkan di tabel view
                  $nominalDpMin = (($p['nilai_dp_minimal'] ?? 30) / 100) * ($p['harga_jadi'] ?? 0);
                ?>
                <tr>
                  <td class="text-center"><?= $i + 1 ?></td>
                  <td><?= esc($p['nama_customer'] ?? '-') ?></td>
                  <td>
                    <strong><?= esc($p['nama_mobil'] ?? '-') ?></strong><br>
                    <small class="text-muted"><?= esc($p['no_polisi'] ?? '-') ?></small>
                  </td>
                  <td><?= date('d/m/Y', strtotime($p['tgl_pesan'] ?? 'now')) ?></td>
                  <td><?= date('d/m/Y', strtotime($p['tgl_jatuh_tempo'] ?? 'now')) ?></td>
                  <td>Rp<?= number_format($p['harga_jadi'] ?? 0, 0, ',', '.') ?></td>
                  <td>Rp<?= number_format($p['nilai_tanda_jadi'] ?? 0, 0, ',', '.') ?></td>
                  <td>Rp<?= number_format($nominalDpMin, 0, ',', '.') ?></td>
                  <td class="text-center">
                    <?php 
                      // Pewarnaan Badge Status Pemesanan agar UI lebih interaktif
                      $badgeClass = 'bg-secondary';
                      if ($p['status_pemesanan'] == 'menunggu') $badgeClass = 'bg-warning text-dark';
                      if ($p['status_pemesanan'] == 'dp_masuk') $badgeClass = 'bg-info text-dark';
                      if ($p['status_pemesanan'] == 'diproses') $badgeClass = 'bg-primary';
                      if ($p['status_pemesanan'] == 'selesai') $badgeClass = 'bg-success';
                      if ($p['status_pemesanan'] == 'dibatalkan') $badgeClass = 'bg-danger';
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= strtoupper($p['status_pemesanan'] ?? 'MENUNGGU') ?></span>
                  </td>
                  <td class="text-center">
                    <div class="btn-group gap-1">
                      <a href="<?= base_url('pemesanan/detail/' . $p['id_pemesanan']) ?>" class="btn btn-sm btn-info text-white" title="Detail"><i class="bi bi-eye"></i></a>
                      <a href="<?= base_url('pemesanan/edit/' . $p['id_pemesanan']) ?>" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                      
                      <a href="<?= base_url('pemesanan/cetak/' . $p['id_pemesanan']) ?>" target="_blank" class="btn btn-sm btn-dark" title="Cetak Berkas"><i class="bi bi-printer"></i></a>
                      
                      <?php if ($p['status_pemesanan'] == 'dp_masuk'): ?>
                        <a href="<?= base_url('penjualan/create/' . $p['id_pemesanan']) ?>" class="btn btn-sm btn-success" title="Proses Jadi Penjualan"><i class="bi bi-cart-check-fill"></i></a>
                      <?php endif; ?>

                      <?php if ($p['status_pemesanan'] == 'menunggu'): ?>
                        <a href="<?= base_url('pemesanan/batal/' . $p['id_pemesanan']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Status mobil akan kembali tersedia.')" title="Batalkan"><i class="bi bi-x-circle"></i></a>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="10" class="text-center p-5">Data Kosong</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Menghancurkan instansi lama jika ada
    if ($.fn.DataTable.isDataTable('#tblPemesananLancar')) {
        $('#tblPemesananLancar').DataTable().destroy();
    }

    // Menjalankan DataTables baru dengan target disable orderable pada kolom indeks ke-0 dan ke-9 (Aksi)
    $('#tblPemesananLancar').DataTable({
        "responsive": true,
        "autoWidth": false,
        "destroy": true,
        "columnDefs": [
            { "targets": [0, 9], "orderable": false }
        ]
    });
});
</script>

<?= view('templates/footer') ?>