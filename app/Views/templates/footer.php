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
// ─── DataTables Init ─────────────────────────────────────────────────────────
$(document).ready(function () {
    if ($('.datatable').length > 0) {
        $('.datatable').each(function() {
            $(this).DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' },
                pageLength: 10,
                responsive: true,
                autoWidth: false,
                dom: '<"d-flex justify-content-between mb-2"lf>rtip',
                ordering: true,
                retrieve: true
            });
        });
    }
});
$.fn.dataTable.ext.errMode = 'none';

// ─── Auto Dismiss Bootstrap Alerts ──────────────────────────────────────────
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        el.style.opacity    = '0';
        el.style.transform  = 'translateY(-6px)';
        setTimeout(() => el.remove(), 500);
    });
}, 3500);

// ─── SweetAlert2 Helpers ─────────────────────────────────────────────────────
function confirmDelete(url) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Data yang dihapus tidak dapat dipulihkan!',
        icon: 'warning',
        background: '#FFFFFF',
        color: '#161616',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bi bi-trash3"></i> Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: { popup: 'swal-premium' }
    }).then(r => { if (r.isConfirmed) window.location.href = url; });
}

function confirmAction(url, title, text, icon, confirmText, confirmColor) {
    Swal.fire({
        title: title || 'Konfirmasi',
        text:  text  || 'Apakah Anda yakin?',
        icon:  icon  || 'question',
        background: '#FFFFFF',
        color: '#161616',
        showCancelButton: true,
        confirmButtonColor: confirmColor || '#B58863',
        cancelButtonColor:  '#6b7280',
        confirmButtonText:  confirmText  || 'Ya, Lanjutkan',
        cancelButtonText:   'Batal',
        customClass: { popup: 'swal-premium' }
    }).then(r => { if (r.isConfirmed) window.location.href = url; });
}

function confirmFormSubmit(form, title, text) {
    Swal.fire({
        title: title || 'Konfirmasi Tindakan',
        text:  text  || 'Apakah Anda yakin ingin memproses ini?',
        icon: 'question',
        background: '#FFFFFF',
        color: '#161616',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor:  '#6b7280',
        confirmButtonText:  'Ya, Lanjutkan',
        cancelButtonText:   'Batal',
        customClass: { popup: 'swal-premium' }
    }).then(r => { if (r.isConfirmed) form.submit(); });
}

// ─── Welcome Toast on Login ───────────────────────────────────────────────────
<?php
$successMsg = session()->getFlashdata('success');
$nama       = session()->get('nama') ?? 'Admin';
if ($successMsg && str_contains($successMsg, 'Selamat datang')):
?>
document.addEventListener('DOMContentLoaded', () => {
    // Sedikit delay agar halaman sudah render
    setTimeout(() => {
        const hour = new Date().getHours();
        const greet = hour < 11 ? 'Selamat Pagi' : hour < 15 ? 'Selamat Siang' : hour < 18 ? 'Selamat Sore' : 'Selamat Malam';

        Swal.fire({
            toast: false,
            position: 'center',
            icon: 'success',
            title: `${greet}, <?= esc($nama) ?>! 👋`,
            html: `<div style="color:#6b7280;font-size:0.9rem;margin-top:4px">
                       Anda berhasil masuk ke sistem.<br>
                       <strong style="color:var(--primary, #B58863)">AutoPrime Showroom Management</strong>
                   </div>`,
            showConfirmButton: true,
            confirmButtonText: 'Mulai Bekerja →',
            confirmButtonColor: '#B58863',
            timer: 5000,
            timerProgressBar: true,
            background: '#ffffff',
            color: '#111827',
            width: '420px',
            padding: '2rem',
            showClass:  { popup: 'animate__animated animate__fadeInDown' },
            hideClass:  { popup: 'animate__animated animate__fadeOutUp'  },
            customClass: { popup: 'swal-welcome' }
        });
    }, 600);
});
<?php endif; ?>

// ─── Global Click Handlers ────────────────────────────────────────────────────
$(document).ready(function() {

    // Intercept confirm-delete links
    $(document).on('click', '.confirm-delete', function(e) {
        e.preventDefault();
        confirmDelete($(this).attr('href'));
    });

    // ─── Live Validation (skip select — border handled via CSS only) ──────────
    $('input, textarea').on('input blur', function() {
        const field = $(this);
        if (!field.prop('required')) return;
        const val = (field.val() ?? '').toString().trim();
        if (!val) { field.removeClass('is-valid').addClass('is-invalid'); return; }

        if (field.attr('type') === 'email') {
            const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
            field.toggleClass('is-valid', ok).toggleClass('is-invalid', !ok);
        } else if (field.attr('type') === 'number' && field.attr('min') !== undefined) {
            const ok = parseFloat(val) >= parseFloat(field.attr('min'));
            field.toggleClass('is-valid', ok).toggleClass('is-invalid', !ok);
        } else {
            field.removeClass('is-invalid').addClass('is-valid');
        }
    });

    // Select: hanya ubah border (ikon validasi dihandle CSS, tidak ada injeksi JS)
    $('select').on('change blur', function() {
        const field = $(this);
        if (!field.prop('required')) return;
        const val = (field.val() ?? '').toString().trim();
        field.toggleClass('is-valid', val !== '').toggleClass('is-invalid', val === '');
    });

    // ─── Ripple Effect pada Tombol ───────────────────────────────────────────
    $(document).on('click', '.btn', function(e) {
        const btn    = $(this);
        const offset = btn.offset();
        const x      = e.pageX - offset.left;
        const y      = e.pageY - offset.top;
        const ripple = $('<span class="btn-ripple"></span>').css({ left: x, top: y });
        btn.append(ripple);
        setTimeout(() => ripple.remove(), 700);
    });
});

// ─── Format Rupiah ────────────────────────────────────────────────────────────
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(angka);
}

// ─── Staggered Fade-in Entrance Animations ───────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const els = document.querySelectorAll('.card, .stat-card, .table tbody tr, .car-card');
    els.forEach((el, i) => {
        el.style.opacity   = '0';
        el.style.transform = 'translateY(14px)';
        el.style.transition = `opacity 0.55s cubic-bezier(0.16,1,0.3,1) ${Math.min(i * 35, 500)}ms,
                                transform 0.55s cubic-bezier(0.16,1,0.3,1) ${Math.min(i * 35, 500)}ms`;
        requestAnimationFrame(() => {
            setTimeout(() => {
                el.style.opacity   = '1';
                el.style.transform = 'translateY(0)';
            }, 30 + Math.min(i * 35, 500));
        });
    });
});

// ═══════════════════════════════════════════════════════════════════════════
//  ✨ PREMIUM JS SURPRISE PACK
// ═══════════════════════════════════════════════════════════════════════════

// ── 1. Inject Top Page Progress Bar ──────────────────────────────────────
(function() {
    const bar = document.createElement('div');
    bar.id = 'nprogress-bar';
    document.body.prepend(bar);

    function startBar() {
        bar.style.width   = '0%';
        bar.style.opacity = '1';
        bar.classList.add('loading');
    }
    function finishBar() {
        bar.classList.remove('loading');
        bar.style.width   = '100%';
        bar.style.opacity = '0';
        setTimeout(() => bar.style.width = '0%', 400);
    }

    // Trigger on all internal link clicks
    document.addEventListener('click', e => {
        const a = e.target.closest('a[href]');
        if (!a) return;
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript') || a.target === '_blank') return;
        startBar();
    });

    // Finish when page loads
    window.addEventListener('load', finishBar);
    finishBar(); // Also finish on DOMContentLoaded
})();

// ── 2. Animated Number Counter for Stat Cards ─────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.stat-value');
    counters.forEach(el => {
        const raw = el.textContent.trim();
        // Only animate if it's a plain number
        const num = parseInt(raw.replace(/[^\d]/g, ''), 10);
        if (isNaN(num) || num === 0) return;

        el.textContent = '0';
        let start = 0;
        const duration = 1200;
        const step = timestamp => {
            if (!start) start = timestamp;
            const progress = Math.min((timestamp - start) / duration, 1);
            // Ease out expo
            const eased = 1 - Math.pow(2, -10 * progress);
            el.textContent = Math.floor(eased * num).toLocaleString('id-ID');
            if (progress < 1) requestAnimationFrame(step);
            else el.textContent = num.toLocaleString('id-ID');
        };
        // Delay slightly so it's visible
        setTimeout(() => requestAnimationFrame(step), 300);
    });
});

// ── 3. Sparkle Particle on Button Click ───────────────────────────────────
$(document).on('click', '.btn-primary, .btn-success', function(e) {
    const colors = ['rgba(255,255,255,0.9)', 'rgba(200,200,200,0.7)', 'rgba(180,180,180,0.5)'];
    for (let i = 0; i < 6; i++) {
        const particle = document.createElement('span');
        const size = Math.random() * 6 + 4;
        const angle = Math.random() * 360;
        const distance = Math.random() * 45 + 20;
        const color = colors[Math.floor(Math.random() * colors.length)];
        Object.assign(particle.style, {
            position: 'fixed',
            left: e.clientX + 'px',
            top:  e.clientY + 'px',
            width:  size + 'px',
            height: size + 'px',
            borderRadius: '50%',
            background: color,
            pointerEvents: 'none',
            zIndex: '99999',
            transform: 'translate(-50%, -50%)',
            transition: `all 0.55s cubic-bezier(0.16,1,0.3,1)`,
            opacity: '1'
        });
        document.body.appendChild(particle);
        requestAnimationFrame(() => {
            const rad = (angle * Math.PI) / 180;
            particle.style.left = (e.clientX + Math.cos(rad) * distance) + 'px';
            particle.style.top  = (e.clientY + Math.sin(rad) * distance) + 'px';
            particle.style.opacity = '0';
            particle.style.transform = 'translate(-50%, -50%) scale(0.2)';
        });
        setTimeout(() => particle.remove(), 600);
    }
});

// ── 4. Real-time Clock in Topbar ─────────────────────────────────────────
(function() {
    const badge = document.querySelector('.topbar-badge');
    if (!badge || !badge.textContent.includes(' ')) return;
    // Find the date badge (has bi-clock icon)
    const clockBadge = document.querySelector('.topbar-badge:first-child');
    if (!clockBadge) return;

    function updateClock() {
        const now  = new Date();
        const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const date = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        clockBadge.innerHTML = `<i class="bi bi-clock"></i> ${date} — ${time}`;
    }
    updateClock();
    setInterval(updateClock, 1000);
})();

// ── 5. Smooth Tooltip System ──────────────────────────────────────────────
(function() {
    const tip = document.createElement('div');
    Object.assign(tip.style, {
        position: 'fixed',
        background: 'rgba(17,24,37,0.92)',
        color: '#fff',
        fontSize: '0.72rem',
        fontWeight: '500',
        padding: '4px 10px',
        borderRadius: '6px',
        pointerEvents: 'none',
        zIndex: '99998',
        opacity: '0',
        transition: 'opacity 0.18s ease, transform 0.18s ease',
        transform: 'translateY(4px)',
        whiteSpace: 'nowrap',
        backdropFilter: 'blur(6px)'
    });
    document.body.appendChild(tip);

    document.addEventListener('mouseover', e => {
        const el = e.target.closest('[title]');
        if (!el) return;
        const text = el.getAttribute('title');
        if (!text) return;
        el.dataset.tipText = text;
        el.removeAttribute('title'); // prevent native tooltip
        tip.textContent = text;
        tip.style.opacity = '1';
        tip.style.transform = 'translateY(0)';
    });
    document.addEventListener('mousemove', e => {
        tip.style.left = (e.clientX + 12) + 'px';
        tip.style.top  = (e.clientY - 28) + 'px';
    });
    document.addEventListener('mouseout', e => {
        const el = e.target.closest('[data-tip-text]');
        if (el && el.dataset.tipText) {
            el.setAttribute('title', el.dataset.tipText);
            delete el.dataset.tipText;
        }
        tip.style.opacity = '0';
        tip.style.transform = 'translateY(4px)';
    });
})();
</script>
</body>
</html>