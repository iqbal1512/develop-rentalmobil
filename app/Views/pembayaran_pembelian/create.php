<?php $title = 'Input Pembayaran Pembelian'; ?>
<?= view('templates/header') ?>

<div class="page-content">
  <div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
      <h5><i class="bi bi-wallet2" style="color: var(--primary);"></i> Input Pembayaran Pembelian</h5>
      <a href="<?= base_url('pembayaran_pembelian') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>
    
    <div class="card-body">
      <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1.5rem;">Silakan lengkapi formulir di bawah ini untuk mencatat pembayaran transaksi pembelian ke supplier.</p>
      
      <!-- Informasi Transaksi Asal -->
      <div class="row mb-4 p-3 bg-light rounded border border-light" style="margin: 0 1px;">
        <div class="col-md-6">
          <table class="table table-borderless table-sm mb-0 text-dark">
            <tr>
              <td class="text-muted" style="width: 130px; font-size: 0.85rem;">Supplier</td>
              <td style="font-size: 0.85rem;" class="fw-bold">: <?= esc($pembelian['nama_supplier'] ?? 'Tidak Terdata') ?></td>
            </tr>
            <tr>
              <td class="text-muted" style="font-size: 0.85rem;">Unit Mobil</td>
              <td style="font-size: 0.85rem;" class="fw-bold">: <?= esc($pembelian['nama_mobil']) ?> (<?= esc($pembelian['warna'] ?? '-') ?>)</td>
            </tr>
            <tr>
              <td class="text-muted" style="font-size: 0.85rem;">Tipe Mobil</td>
              <td style="font-size: 0.85rem;">: <?= esc($pembelian['tipe'] ?? '-') ?></td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table table-borderless table-sm mb-0 text-dark">
            <tr>
              <td class="text-muted" style="width: 130px; font-size: 0.85rem;">Tgl Pembelian</td>
              <td style="font-size: 0.85rem;">: <?= date('d/m/Y', strtotime($pembelian['tgl_pembelian'])) ?></td>
            </tr>
            <tr>
              <td class="text-muted" style="font-size: 0.85rem;">Jumlah Pembelian</td>
              <td style="font-size: 0.85rem;">: <?= $pembelian['jumlah_pembelian'] ?> Unit</td>
            </tr>
            <tr>
              <td class="text-muted" style="font-size: 0.85rem; font-weight: 600;">Total Harga</td>
              <td style="font-size: 0.85rem;" class="text-danger fw-bold">: Rp<?= number_format($pembelian['total_harga'], 0, ',', '.') ?></td>
            </tr>
          </table>
        </div>
      </div>

      <form action="<?= base_url('pembayaran_pembelian/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="id_pembelian" value="<?= $pembelian['id_pembelian'] ?>">
        
        <h6 class="mb-3" style="font-weight: 600; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 10px;">Formulir Pembayaran</h6>
        
        <div class="row">
          <div class="col-md-6 form-group">
            <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
            <select name="metode_bayar" id="metodeBayar" class="form-control" required>
              <option value="tunai" <?= old('metode_bayar') === 'tunai' ? 'selected' : '' ?>>Tunai / Cash</option>
              <option value="transfer" <?= old('metode_bayar') === 'transfer' ? 'selected' : '' ?>>Transfer Bank</option>
            </select>
          </div>
          
          <div class="col-md-6 form-group">
            <label class="form-label">Jumlah Pembayaran (Rp) <span class="text-danger">*</span></label>
            <input type="text" name="jumlah_bayar" class="form-control text-success fw-bold" value="<?= number_format($pembelian['total_harga'], 0, ',', '') ?>" readonly required>
            <span style="font-size: 0.75rem; color: var(--gray-500);">Lunas sesuai nominal total pembelian.</span>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-6 form-group" id="buktiTransferGroup" style="display: none;">
            <label class="form-label">Bukti Transfer <span class="text-danger">*</span></label>
            <input type="file" name="bukti_transfer" id="buktiTransferInput" class="form-control" accept="image/*">
            <p style="font-size: 0.75rem; color: var(--gray-500); margin-top: 5px;">Maksimal file 2MB (Format: JPG, PNG, WEBP).</p>
            <div id="imagePreview" class="mt-3 d-none">
              <img src="#" alt="Preview Bukti" style="max-height: 150px; border-radius: 0.5rem; border: 1px solid var(--gray-200);">
            </div>
          </div>
          
          <div class="col-md-6 form-group">
            <label class="form-label">Keterangan / Catatan Kondisi</label>
            <textarea name="keterangan_kondisi" class="form-control" rows="3" placeholder="Masukkan catatan tambahan mengenai kondisi unit atau rincian pembayaran..."><?= old('keterangan_kondisi', $pembelian['keterangan_kondisi']) ?></textarea>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid var(--gray-200);">
          <a href="<?= base_url('pembayaran_pembelian') ?>" class="btn btn-secondary">Batalkan</a>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check2-circle"></i> Simpan Pembayaran
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const metodeSelect = document.getElementById('metodeBayar');
        const buktiGroup = document.getElementById('buktiTransferGroup');
        const buktiInput = document.getElementById('buktiTransferInput');
        const preview = document.querySelector('#imagePreview img');
        const previewDiv = document.getElementById('imagePreview');

        function toggleBuktiField() {
            if (metodeSelect.value === 'transfer') {
                buktiGroup.style.display = 'block';
                buktiInput.setAttribute('required', 'required');
            } else {
                buktiGroup.style.display = 'none';
                buktiInput.removeAttribute('required');
            }
        }

        metodeSelect.addEventListener('change', toggleBuktiField);
        toggleBuktiField(); // Initial check

        buktiInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewDiv.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>

<?= view('templates/footer') ?>
