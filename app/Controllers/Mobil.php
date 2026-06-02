<?php

namespace App\Controllers;

use App\Models\MobilModel;
use App\Models\SupplierModel;
use App\Models\PemesananModel; // IMPORT model pemesanan untuk proteksi data transaksi
use CodeIgniter\Controller;

class Mobil extends Controller
{
    protected $model;
    protected $supplierModel;
    protected $pemesananModel;

    public function __construct()
    {
        $this->model          = new MobilModel();
        $this->supplierModel  = new SupplierModel();
        $this->pemesananModel = new PemesananModel(); // Inisialisasi model pemesanan
        helper(['form', 'url', 'filesystem']);
    }

    /** Menampilkan Data Mobil */
    public function index()
    {
        $data = [
            'title'  => 'Kelola Data Mobil',
            'mobils' => $this->model->findAll(), 
        ];

        return view('mobil/index', $data);
    }

    /** Form Tambah Data Mobil */
    public function create()
    {
        return view('mobil/create', [
            'title'    => 'Tambah Data Mobil',
            'supplier' => $this->supplierModel->findAll(),
        ]);
    }

    /** Simpan Data Mobil */
    public function store()
    {
        // Aturan validasi ketat
        $rules = [
            'id_supplier' => 'required',
            'nama_mobil'  => 'required|min_length[3]',
            'harga_beli'  => 'required',
            'harga_jual'  => 'required',
            'stok'        => 'required|integer|greater_than_equal_to[0]',
            'foto'        => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]',
        ];

        // PERBAIKAN: Mengirimkan list error spesifik dari validator ke View
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle upload foto
        $foto = $this->request->getFile('foto');
        $fotoName = null;
        
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $fotoName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/mobil', $fotoName);
        }

        $this->model->insert([
            'id_supplier'  => $this->request->getPost('id_supplier'),
            'nama_mobil'   => $this->request->getPost('nama_mobil'),
            'warna'        => $this->request->getPost('warna'),
            'vendor'       => $this->request->getPost('vendor'),
            'tipe'         => $this->request->getPost('tipe'),
            'no_polisi'    => strtoupper($this->request->getPost('no_polisi')), // Otomatis jadikan huruf kapital
            'tahun'        => $this->request->getPost('tahun'),
            'harga_beli'   => (float) str_replace(['.', ','], '', $this->request->getPost('harga_beli')),
            'harga_jual'   => (float) str_replace(['.', ','], '', $this->request->getPost('harga_jual')),
            'stok'         => $this->request->getPost('stok'),
            'status_jual'  => $this->request->getPost('status_jual') ?: 'tersedia',
            'status_mobil' => $this->request->getPost('status_mobil') ?: 'bekas',
            'foto'         => $fotoName,
            'keterangan'   => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to(base_url('mobil'))->with('success', 'Unit mobil berhasil ditambahkan ke inventori.');
    }

    /** Form Edit Data Mobil */
    public function edit($id)
    {
        $mobil = $this->model->find($id);
        if (!$mobil) {
            return redirect()->to(base_url('mobil'))->with('error', 'Data unit mobil tidak ditemukan.');
        }

        return view('mobil/edit', [
            'title'    => 'Edit Data Mobil',
            'mobil'    => $mobil,
            'supplier' => $this->supplierModel->findAll(),
        ]);
    }

    /** Update Data Mobil */
    public function update($id)
    {
        $mobil = $this->model->find($id);
        if (!$mobil) return redirect()->to(base_url('mobil'))->with('error', 'Data tidak ditemukan.');

        // PERBAIKAN: Wajib pasang validasi data saat update data agar tidak merusak database
        $rules = [
            'id_supplier' => 'required',
            'nama_mobil'  => 'required|min_length[3]',
            'harga_beli'  => 'required',
            'harga_jual'  => 'required',
            'stok'        => 'required|integer|greater_than_equal_to[0]',
            'foto'        => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fotoName = $mobil['foto'];
        $foto = $this->request->getFile('foto');

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Hapus berkas foto lama dari direktori lokal jika ada berkas baru yang diunggah
            if ($fotoName && file_exists(FCPATH . 'uploads/mobil/' . $fotoName)) {
                unlink(FCPATH . 'uploads/mobil/' . $fotoName);
            }
            
            $fotoName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/mobil', $fotoName);
        }

        $this->model->update($id, [
            'id_supplier'  => $this->request->getPost('id_supplier'),
            'nama_mobil'   => $this->request->getPost('nama_mobil'),
            'warna'        => $this->request->getPost('warna'),
            'vendor'       => $this->request->getPost('vendor'),
            'tipe'         => $this->request->getPost('tipe'),
            'no_polisi'    => strtoupper($this->request->getPost('no_polisi')),
            'tahun'        => $this->request->getPost('tahun'),
            'harga_beli'   => (float) str_replace(['.', ','], '', $this->request->getPost('harga_beli')),
            'harga_jual'   => (float) str_replace(['.', ','], '', $this->request->getPost('harga_jual')),
            'stok'         => $this->request->getPost('stok'),
            'status_jual'  => $this->request->getPost('status_jual'),
            'status_mobil' => $this->request->getPost('status_mobil'),
            'foto'         => $fotoName,
            'keterangan'   => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to(base_url('mobil'))->with('success', 'Spesifikasi unit mobil berhasil diperbarui.');
    }

    /** Hapus Data Mobil dengan Proteksi Foreign Key */
    public function delete($id)
    {
        $mobil = $this->model->find($id);
        if (!$mobil) {
            return redirect()->to(base_url('mobil'))->with('error', 'Unit mobil tidak ditemukan.');
        }

        // AMAN: Periksa apakah unit mobil ini sudah masuk ke riwayat pemesanan/penjualan aktif
        $cekTransaksi = $this->pemesananModel->where('id_mobil', $id)->first();
        if ($cekTransaksi) {
            return redirect()->to(base_url('mobil'))->with('error', 'Gagal menghapus! Unit mobil ini tidak bisa dihapus karena terikat riwayat nota pemesanan customer.');
        }

        // Jika tidak memiliki keterikatan relasi, hapus berkas gambar lalu bersihkan row data
        if ($mobil['foto'] && file_exists(FCPATH . 'uploads/mobil/' . $mobil['foto'])) {
            unlink(FCPATH . 'uploads/mobil/' . $mobil['foto']);
        }

        $this->model->delete($id);
        return redirect()->to(base_url('mobil'))->with('success', 'Unit mobil berhasil dihapus permanen dari sistem.');
    }
}