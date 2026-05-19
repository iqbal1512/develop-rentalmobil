  </div><!-- /.page-content -->
</div><!-- /.main-content -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Init DataTables
$(document).ready(function () {
    // Gunakan pengecekan yang lebih aman
    if ($('.datatable').length > 0) {
        $('.datatable').each(function() {
            $(this).DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
                },
                pageLength: 10,
                responsive: true,
                autoWidth: false, // Penting agar tidak hitung lebar kolom manual yang bikin error
                dom: '<"d-flex justify-content-between mb-2"lf>rtip',
                // Kita matikan sementara columnDefs manual ini agar tidak bentrok
                "ordering": true,
                "retrieve": true // Agar tidak inisialisasi ulang jika sudah ada
            });
        });
    }
});

// Fix untuk pop-up error DataTables (PAKSA MATIKAN ALERT)
$.fn.dataTable.ext.errMode = 'none'; 
$('.datatable').on('error.dt', function(e, settings, techNote, message) {
    console.log('DataTables Error: ', message);
});

// Auto dismiss alerts (Tetap sama)
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 3500);

// Konfirmasi Hapus & Aksi (Tetap sama, sudah bagus)
function confirmDelete(url) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Data yang dihapus tidak dapat dipulihkan!',
        icon: 'warning',
        background: '#FFFFFF',
        color: '#161616',
        showCancelButton: true,
        confirmButtonColor: '#d32f2f',
        cancelButtonColor: '#3D4D55',
        confirmButtonText: '<i class="bi bi-trash3"></i> Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) window.location.href = url;
    });
}

function confirmAction(url, title, text, icon, confirmText, confirmColor) {
    Swal.fire({
        title: title || 'Konfirmasi',
        text: text || 'Apakah Anda yakin?',
        icon: icon || 'question',
        background: '#FFFFFF',
        color: '#161616',
        showCancelButton: true,
        confirmButtonColor: confirmColor || '#B58863',
        cancelButtonColor: '#3D4D55',
        confirmButtonText: confirmText || 'Ya, Lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) window.location.href = url;
    });
}

function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
}

// Premium Smooth Staggered Fade-in Animation
document.addEventListener("DOMContentLoaded", () => {
    const animatedElements = document.querySelectorAll('.card, .stat-card, .table tbody tr, .car-card');
    animatedElements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(10px) scale(0.99)';
        el.style.transition = 'opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1)';
        
        // Organic stagger delay
        const delay = Math.min(index * 40, 1000); 
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0) scale(1)';
        }, 50 + delay);
    });
});
</script>
</body>
</html>