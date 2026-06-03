<?php

namespace App\Controllers;

use App\Models\PemesananModel;
use App\Models\CustomerModel;
use App\Models\MobilModel;
use App\Models\PenjualanModel;
use App\Models\PembayaranModel; // FIXED: Menggunakan model tunggal yang valid
use CodeIgniter\Controller;

/**
 * Pemesanan Controller (Sesuai Aturan Alur Database Baru)
 */
class Pemesanan extends Controller
{
    protected PemesananModel    $model;
    protected CustomerModel     $customerModel;
    protected MobilModel        $mobilModel;
    protected PenjualanModel    $penjualanModel;
    protected PembayaranModel   $pembayaranModel; // FIXED: Konsisten menggunakan properti ini

    public function __construct()
    {
        $this->model            = new PemesananModel();
        $this->customerModel    = new CustomerModel();
        $this->mobilModel       = new MobilModel();
        $this->penjualanModel   = new PenjualanModel();
        $this->pembayaranModel  = new PembayaranModel(); // FIXED
        helper(['form', 'url']);
    }

    public function index(): string
    {
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
        // Antisipasi jika fungsi getMobilTersedia belum ada di MobilModel, gunakan query builder langsung
        $mobils = method_exists($this->mobilModel, 'getMobilTersedia') 
            ? $this->mobilModel->getMobilTersedia() 
            : $this->mobilModel->where('status_jual', 'tersedia')->findAll();

        return view('pemesanan/create', [
            'title'     => 'Buat Pemesanan Baru',
            'customers' => $this->customerModel->orderBy('nama')->findAll(),
            'mobils'    => $mobils,
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

        $hargaJadi      = (float) str_replace([',', '.'], '', $this->request->getPost('harga_jadi'));
        $nilaiTandaJadi = (float) str_replace([',', '.'], '', $this->request->getPost('nilai_tanda_jadi'));
        $nilaiDpMinimal = 30.00; 

        $tglPesan       = $this->request->getPost('tgl_pesan');
        $tglJatuhTempo  = date('Y-m-d', strtotime($tglPesan . ' +7 days'));

        // --- PROSES INSERT PEMESANAN ---
        $this->model->insert([
            'id_customer'      => $this->request->getPost('id_customer'),
            'id_mobil'         => $this->request->getPost('id_mobil'),
            'id_user'          => session()->get('id_user') ?? 1,
            'tgl_pesan'        => $tglPesan,
            'tgl_jatuh_tempo'  => $tglJatuhTempo,
            'harga_jadi'       => $hargaJadi,
            'nilai_tanda_jadi' => $nilaiTandaJadi,
            'nilai_dp_minimal' => $nilaiDpMinimal,
            'status_pemesanan' => 'menunggu',
        ]);

        // --- TAMBAHAN LOGIKA DI BAWAH INI ---
        
        // 1. Ambil ID dari data yang baru saja masuk ke database
        $idPemesananBaru = $this->model->getInsertID();

        // 2. Update status mobil jadi 'dipesan'
        $this->mobilModel->update($this->request->getPost('id_mobil'), ['status_jual' => 'dipesan']);

        // 3. Langsung arahkan ke halaman tambah pembayaran sambil membawa ID
        return redirect()->to('/pembayaran/create?id_pemesanan=' . $idPemesananBaru)
                         ->with('success', 'Pemesanan berhasil! Silakan masukkan detail pembayaran.');
        
        // --- SELESAI TAMBAHAN ---
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
        
        // FIXED: Memakai $this->pembayaranModel yang terdefinisi di atas
        $pembayaran = $this->pembayaranModel->where('id_pemesanan', $id)->findAll();
        $penjualan = $this->penjualanModel->where('id_pemesanan', $id)->first();

        return view('pemesanan/detail', [
            'title'      => 'Detail Pemesanan #' . $id,
            'pemesanan'  => $pemesanan,
            'pembayaran' => $pembayaran,
            'penjualan'  => $penjualan,
        ]);
    }

    public function cetak(int $id)
    {
        $pemesanan = $this->model->getDetailInvoice($id);
        if (!$pemesanan) {
            return redirect()->to('/pemesanan')->with('error', 'Data berkas cetak tidak ditemukan.');
        }

        // FIXED: Memakai $this->pembayaranModel yang terdefinisi di atas
        $pembayaran = $this->pembayaranModel->where('id_pemesanan', $id)->findAll();

        $data = [
            'title'      => 'Faktur Bukti Pemesanan Mobil',
            'pemesanan'  => $pemesanan,
            'pembayaran' => $pembayaran
        ];

        $html = view('pemesanan/cetak_pdf', $data);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A5', 'landscape'); 
        $dompdf->render();

        return $dompdf->stream("Bukti_Pemesanan_Booking_#" . $id . ".pdf", ["Attachment" => 0]);
    }

    public function batal(int $id)
    {
        $pemesanan = $this->model->find($id);
        if ($pemesanan) {
            $this->model->update($id, ['status_pemesanan' => 'dibatalkan']);
            $this->mobilModel->update($pemesanan['id_mobil'], ['status_jual' => 'tersedia']);
        }
        return redirect()->to('/pemesanan')->with('success', 'Pemesanan berhasil dibatalkan.');
    }

    public function delete(int $id)
    {
        $pemesanan = $this->model->find($id);
        if ($pemesanan) {
            $this->mobilModel->update($pemesanan['id_mobil'], ['status_jual' => 'tersedia']);
            $this->model->delete($id);
        }
        return redirect()->to('/pemesanan')->with('success', 'Data pemesanan berhasil dihapus dari sistem.');
    }
}