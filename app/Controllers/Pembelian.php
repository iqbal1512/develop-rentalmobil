<?php

namespace App\Controllers;

use App\Models\PembelianModel;
use App\Models\MobilModel;
use App\Models\SupplierModel;
use CodeIgniter\Controller;

/**
 * Pembelian Controller
 * Proses bisnis: beli mobil dari supplier (tunai/transfer)
 */
class Pembelian extends BaseController
{
    protected PembelianModel $model;
    protected MobilModel     $mobilModel;
    protected SupplierModel  $supplierModel;

    public function __construct()
    {
        $this->model         = new PembelianModel();
        $this->mobilModel    = new MobilModel();
        $this->supplierModel = new SupplierModel();
        helper(['form', 'url']);
    }

    public function index(): string
    {
        $dataPembelian = $this->model
            ->select('pembelian.*, supplier.nama_supplier, mobil.nama_mobil, mobil.tipe, users.nama as nama_user')
            ->join('supplier', 'supplier.id_supplier = pembelian.id_supplier', 'left')
            ->join('mobil',    'mobil.id_mobil = pembelian.id_mobil',           'left')
            ->join('users',    'users.id_user = pembelian.id_user',             'left')
            ->orderBy('pembelian.tgl_pembelian', 'DESC')
            ->findAll();

        return view('pembelian/index', [
            'title'     => 'Kelola Transaksi Pembelian',
            'pembelian' => $dataPembelian,
        ]);
    }

    public function create(): string
    {
        return view('pembelian/create', [
            'title'     => 'Tambah Transaksi Pembelian',
            'suppliers' => $this->supplierModel->findAll(),
            'mobils'    => $this->mobilModel->findAll(),
        ]);
    }

    public function store()
    {
        // 1. Validasi Input Form
        $rules = [
            'supplier_input'   => 'required',
            'mobil_input'      => 'required',
            'tgl_pembelian'    => 'required|valid_date',
            'harga_beli'       => 'required',
            'jumlah_pembelian' => 'required|integer|greater_than[0]',
            'metode_bayar'     => 'required|in_list[tunai,transfer]',
        ];

        if (!$this->validate($rules)) {
            // Gabungkan pesan error agar mudah dibaca di view flashdata 'error'
            $errorMsg = implode(', ', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', 'Gagal validasi: ' . $errorMsg);
        }

        // 2. Formatting Nominal & Angka
        $harga   = (float) str_replace([',', '.'], '', $this->request->getPost('harga_beli'));
        $jumlah  = (int)   $this->request->getPost('jumlah_pembelian');
        $total   = $this->model->hitungTotal($harga, $jumlah);
        $metode  = $this->request->getPost('metode_bayar');

        $supplierInput = $this->request->getPost('supplier_input');
        $mobilInput    = $this->request->getPost('mobil_input');

        // 3. Resolve / Cocokkan ID Supplier
        $supplier = $this->supplierModel->where('nama_supplier', $supplierInput)->first();
        if (!$supplier) {
            $this->supplierModel->insert([
                'nama_supplier' => $supplierInput,
                'alamat'        => '-',
            ]);
            $id_supplier = $this->supplierModel->getInsertID(); // Gunakan getInsertID() standar CI4
        } else {
            $id_supplier = $supplier['id_supplier'];
        }

        // 4. Resolve / Cocokkan ID Mobil
        $mobilName = $mobilInput;
        $warna = 'Default';
        if (preg_match('/^(.*?)\s*\((.*?)\)$/', $mobilInput, $matches)) {
            $mobilName = trim($matches[1]);
            $warna = trim($matches[2]);
        }

        $mobil = $this->mobilModel->where('nama_mobil', $mobilName)->where('warna', $warna)->first();
        if (!$mobil) {
            $mobil = $this->mobilModel->where('nama_mobil', $mobilName)->first();
        }

        if (!$mobil) {
            $this->mobilModel->insert([
                'id_supplier'  => $id_supplier,
                'nama_mobil'   => $mobilName,
                'warna'        => $warna,
                'vendor'       => 'Lainnya',
                'tipe'         => 'Lainnya',
                'harga_beli'   => $harga,
                'harga_jual'   => $harga * 1.15,
                'stok'         => 0,
            ]);
            $id_mobil = $this->mobilModel->getInsertID(); // Gunakan getInsertID() standar CI4
        } else {
            $id_mobil = $mobil['id_mobil'];
        }

        // 5. Handle Upload Bukti Transfer
        $buktiName = null;
        if ($metode === 'transfer') {
            $bukti = $this->request->getFile('bukti_transfer');
            if ($bukti && $bukti->isValid() && !$bukti->hasMoved()) {
                $buktiName = $bukti->getRandomName();
                $bukti->move(ROOTPATH . 'public/uploads/bukti', $buktiName);
            }
        }

        // 6. Generate Nomor Kwitansi
        $noKwitansi = null;
        if ($metode === 'tunai') {
            $noKwitansi = 'PBL-' . date('Ymd') . '-' . rand(1000, 9999);
        }

        // 7. PROTEKSI AMAN ID USER (Kolom NOT NULL di Database)
        // Mengambil ID User dari session. Jika kosong (belum login/testing), paksa ke ID 1 (Admin bawaan seeder)
        $idUserSession = session()->get('id_user') ?? session()->get('id') ?? 1;

        // 8. Proses Simpan Transaksi Pembelian
        $insertData = [
            'id_supplier'        => $id_supplier,
            'id_mobil'           => $id_mobil,
            'id_user'            => $idUserSession, // Dipastikan tidak NULL lagi
            'tgl_pembelian'      => $this->request->getPost('tgl_pembelian'),
            'harga_beli'         => $harga,
            'jumlah_pembelian'   => $jumlah,
            'total_harga'        => $total,
            'metode_bayar'       => $metode,
            'bukti_transfer'     => $buktiName,
            'no_kwitansi'        => $noKwitansi,
            'status_pembelian'   => $metode === 'tunai' ? 'selesai' : 'proses',
            'keterangan_kondisi' => $this->request->getPost('keterangan_kondisi'),
        ];

        if ($this->model->insert($insertData)) {
            // Update stok mobil jika berhasil
            $mobilData = $this->mobilModel->find($id_mobil);
            if ($mobilData) {
                $this->mobilModel->update($id_mobil, ['stok' => $mobilData['stok'] + $jumlah]);
            }

            return redirect()->to('/pembelian')->with('success', 'Transaksi pembelian berhasil disimpan.' .
                ($metode === 'tunai' ? ' Kwitansi: ' . $noKwitansi : ' Menunggu verifikasi transfer.'));
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan ke database. Periksa log sistem.');
        }
    }

    public function edit(int $id)
    {
        $pembelian = $this->model->find($id);
        if (!$pembelian) {
            return redirect()->to('/pembelian')->with('error', 'Data tidak ditemukan.');
        }
        return view('pembelian/edit', [
            'title'     => 'Edit Transaksi Pembelian',
            'pembelian' => $pembelian,
            'suppliers' => $this->supplierModel->findAll(),
            'mobils'    => $this->mobilModel->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $this->model->update($id, [
            'status_pembelian'   => $this->request->getPost('status_pembelian'),
            'keterangan_kondisi' => $this->request->getPost('keterangan_kondisi'),
        ]);
        return redirect()->to('/pembelian')->with('success', 'Transaksi pembelian berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $this->model->delete($id);
        return redirect()->to('/pembelian')->with('success', 'Transaksi pembelian berhasil dihapus.');
    }

    public function selesai(int $id)
    {
        $pembelian = $this->model->find($id);
        if ($pembelian) {
            $this->model->update($id, [
                'status_pembelian' => 'selesai',
                'no_kwitansi'      => 'PBL-' . date('Ymd') . '-' . rand(1000, 9999),
            ]);
        }
        return redirect()->to('/pembelian')->with('success', 'Status pembelian diperbarui ke Selesai.');
    }
}