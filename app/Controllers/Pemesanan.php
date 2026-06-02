<?php

namespace App\Controllers;

use App\Models\PemesananModel;
use App\Models\CustomerModel;
use App\Models\MobilModel;
use App\Models\PenjualanModel;
use App\Models\PembayaranPenjualanModel; // Memanggil model pembayaran penjualan baru
use CodeIgniter\Controller;

/**
 * Pemesanan Controller (Sesuai Aturan Alur Database Baru)
 * Proses bisnis di menu ini:
 * 1. Hanya melakukan input data customer, data mobil, tgl pesan, harga jadi, nilai tanda jadi, nilai dp minimal (30%)
 * 2. Mengatur status_pemesanan awal menjadi 'menunggu'
 * 3. Menghitung otomatis tgl_jatuh_tempo = tgl_pesan + 7 hari
 * 4. Terintegrasi dengan fitur cetak faktur PDF dan pelacakan pembayaran awal.
 */
class Pemesanan extends Controller
{
    protected PemesananModel           $model;
    protected CustomerModel             $customerModel;
    protected MobilModel                $mobilModel;
    protected PenjualanModel            $penjualanModel;
    protected PembayaranPenjualanModel  $pembayaranPenjualanModel;

    public function __construct()
    {
        $this->model                    = new PemesananModel();
        $this->customerModel            = new CustomerModel();
        $this->mobilModel               = new MobilModel();
        $this->penjualanModel           = new PenjualanModel();
        $this->pembayaranPenjualanModel = new PembayaranPenjualanModel();
        helper(['form', 'url']);
    }

    public function index(): string
    {
        // Fungsi opsional untuk otomatisasi pembatalan jika lewat jatuh tempo di database
        if (method_exists($this->model, 'batalOtomatisTempo')) {
            $this->model->batalOtomatisTempo();
        }

        return view('pemesanan/index', [
            'title'     => 'Kelola Pemesanan Mobil',
            'pemesanan' => $this->model->getDetailInvoice(0) ? $this->model->getAllWithRelasi() : $this->model->findAll(), 
        ]);
    }

    public function create(): string
    {
        return view('pemesanan/create', [
            'title'     => 'Buat Pemesanan Baru',
            'customers' => $this->customerModel->orderBy('nama')->findAll(),
            'mobils'    => $this->mobilModel->getMobilTersedia(), // Hanya ambil mobil berstatus 'tersedia'
        ]);
    }

    public function store()
    {
        $rules = [
            'id_customer'     => 'required|integer',
            'id_mobil'        => 'required|integer',
            'tgl_pesan'       => 'required|valid_date',
            'harga_jadi'      => 'required',
            'nilai_tanda_jadi'=> 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Bersihkan format ribuan (titik/koma) dari input view form
        $hargaJadi      = (float) str_replace([',', '.'], '', $this->request->getPost('harga_jadi'));
        $nilaiTandaJadi = (float) str_replace([',', '.'], '', $this->request->getPost('nilai_tanda_jadi'));
        
        // Aturan Nilai DP Minimal: Kunci tetap di angka 30.00 (%) sesuai struktur field tabel kamu
        $nilaiDpMinimal = 30.00; 

        $tglPesan       = $this->request->getPost('tgl_pesan');
        // Otomatisasi tanggal jatuh tempo seminggu (7 hari) dari tgl pesan untuk bayar bukti pesanan/DP
        $tglJatuhTempo  = date('Y-m-d', strtotime($tglPesan . ' +7 days'));

        $this->model->insert([
            'id_customer'      => $this->request->getPost('id_customer'),
            'id_mobil'         => $this->request->getPost('id_mobil'),
            'id_user'          => session()->get('id_user') ?? 1, // Fallback ke id 1 jika session belum set
            'tgl_pesan'        => $tglPesan,
            'tgl_jatuh_tempo'  => $tglJatuhTempo,
            'harga_jadi'       => $hargaJadi,
            'nilai_tanda_jadi' => $nilaiTandaJadi,
            'nilai_dp_minimal' => $nilaiDpMinimal,
            'status_pemesanan' => 'menunggu', // Status default awal saat pertama kali input pesanan
        ]);

        // Update status_jual mobil menjadi 'dipesan' agar customer lain tidak bisa memilih mobil ini
        $this->mobilModel->update($this->request->getPost('id_mobil'), ['status_jual' => 'dipesan']);

        // Hitung nominal DP asli untuk ditampilkan di flash message info
        $nominalDpInfo = ($nilaiDpMinimal / 100) * $hargaJadi;

        return redirect()->to('/pemesanan')->with('success', 
            "Pemesanan berhasil disimpan. Status: Menunggu Pembayaran. Batas Jatuh Tempo: " . date('d-m-Y', strtotime($tglJatuhTempo)) . ". Estimasi nilai DP 30%: Rp " . number_format($nominalDpInfo, 0, ',', '.'));
    }

    public function edit(int $id)
    {
        $pemesanan = $this->model->getDetailInvoice($id);
        if (!$pemesanan) {
            return redirect()->to('/pemesanan')->with('error', 'Data pemesanan tidak ditemukan.');
        }
        
        return view('pemesanan/edit', [
            'title'     => 'Edit Pemesanan',
            'pemesanan' => $pemesanan,
            'customers' => $this->customerModel->orderBy('nama')->findAll(),
            'mobils'    => $this->mobilModel->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'id_customer'     => 'required|integer',
            'id_mobil'        => 'required|integer',
            'tgl_pesan'       => 'required|valid_date',
            'harga_jadi'      => 'required',
            'nilai_tanda_jadi'=> 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $pemesananLama = $this->model->find($id);
        $idMobilBaru   = $this->request->getPost('id_mobil');

        // Jika mobil diganti saat edit, kembalikan status mobil lama dan kunci status mobil baru
        if ($pemesananLama['id_mobil'] != $idMobilBaru) {
            $this->mobilModel->update($pemesananLama['id_mobil'], ['status_jual' => 'tersedia']);
            $this->mobilModel->update($idMobilBaru, ['status_jual' => 'dipesan']);
        }

        $hargaJadi     = (float) str_replace([',', '.'], '', $this->request->getPost('harga_jadi'));
        $tglPesan      = $this->request->getPost('tgl_pesan');
        $tglJatuhTempo = date('Y-m-d', strtotime($tglPesan . ' +7 days'));

        $this->model->update($id, [
            'id_customer'      => $this->request->getPost('id_customer'),
            'id_mobil'         => $idMobilBaru,
            'tgl_pesan'        => $tglPesan,
            'tgl_jatuh_tempo'  => $tglJatuhTempo,
            'harga_jadi'       => $hargaJadi,
            'nilai_tanda_jadi' => (float) str_replace([',', '.'], '', $this->request->getPost('nilai_tanda_jadi')),
            'status_pemesanan' => $this->request->getPost('status_pemesanan') ?? $pemesananLama['status_pemesanan'],
        ]);

        return redirect()->to('/pemesanan')->with('success', 'Data pemesanan berhasil diperbarui.');
    }

    public function detail(int $id)
    {
        $pemesanan = $this->model->getDetailInvoice($id);
        if (!$pemesanan) {
            return redirect()->to('/pemesanan')->with('error', 'Data tidak ditemukan.');
        }
        
        // Mengambil histori data pembayaran uang bukti pesan / DP dari tabel pembayaran_penjualan
        $pembayaran = $this->pembayaranPenjualanModel->where('id_pemesanan', $id)->findAll();

        // Mengambil riwayat invoice penjualan jika data sudah ditarik ke penjualan final
        $penjualan = $this->penjualanModel->where('id_pemesanan', $id)->first();

        return view('pemesanan/detail', [
            'title'      => 'Detail Pemesanan #' . $id,
            'pemesanan'  => $pemesanan,
            'pembayaran' => $pembayaran,
            'penjualan'  => $penjualan,
        ]);
    }

    /** Fitur Cetak Faktur / Bukti Pemesanan Sementara (PDF) */
    public function cetak(int $id)
    {
        $pemesanan = $this->model->getDetailInvoice($id);
        if (!$pemesanan) {
            return redirect()->to('/pemesanan')->with('error', 'Data berkas cetak tidak ditemukan.');
        }

        $pembayaran = $this->pembayaranPenjualanModel->where('id_pemesanan', $id)->findAll();

        $data = [
            'title'      => 'Faktur Bukti Pemesanan Mobil',
            'pemesanan'  => $pemesanan,
            'pembayaran' => $pembayaran
        ];

        // Memanggil file view cetak polosan html
        $html = view('pemesanan/cetak_pdf', $data);

        // Inisialisasi Dompdf (Pastikan library dompdf sudah terinstall via composer)
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A5', 'landscape'); // Format standar kwitansi kertas mini
        $dompdf->render();

        // Stream hasil pdf langsung otomatis download / preview di browser
        return $dompdf->stream("Bukti_Pemesanan_Booking_#" . $id . ".pdf", ["Attachment" => 0]);
    }

    public function batal(int $id)
    {
        $pemesanan = $this->model->find($id);
        if ($pemesanan) {
            $this->model->update($id, ['status_pemesanan' => 'dibatalkan']);
            // Kembalikan status aset mobil menjadi 'tersedia' kembali agar bisa dijual ke pembeli lain
            $this->mobilModel->update($pemesanan['id_mobil'], ['status_jual' => 'tersedia']);
        }
        return redirect()->to('/pemesanan')->with('success', 'Pemesanan berhasil dibatalkan.');
    }

    public function delete(int $id)
    {
        $pemesanan = $this->model->find($id);
        if ($pemesanan) {
            // Sebelum data transaksi dihapus fisik, kembalikan dulu status mobilnya
            $this->mobilModel->update($pemesanan['id_mobil'], ['status_jual' => 'tersedia']);
            $this->model->delete($id);
        }
        return redirect()->to('/pemesanan')->with('success', 'Data pemesanan berhasil dihapus dari sistem.');
    }
}