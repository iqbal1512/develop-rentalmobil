<?php

namespace App\Controllers;

use App\Models\MobilModel;
use App\Models\SupplierModel;
use CodeIgniter\Controller;

class Mobil extends Controller
{
    protected $model;
    protected $supplierModel;

    public function __construct()
    {
        $this->model         = new MobilModel();
        $this->supplierModel = new SupplierModel();
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
        $rules = [
            'id_supplier' => 'required',
            'nama_mobil'  => 'required',
            'harga_beli'  => 'required',
            'harga_jual'  => 'required',
            'stok'        => 'required|integer',
            'foto'        => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Cek kembali format gambar atau kelengkapan data.');
        }

        // Handle upload foto
        $foto = $this->request->getFile('foto');
        $fotoName = null;
        
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $fotoName = $foto->getRandomName();
            // Pindahkan ke folder public/uploads/mobil
            $foto->move(FCPATH . 'uploads/mobil', $fotoName);
        }

        $this->model->insert([
            'id_supplier'  => $this->request->getPost('id_supplier'),
            'nama_mobil'   => $this->request->getPost('nama_mobil'),
            'warna'        => $this->request->getPost('warna'),
            'vendor'       => $this->request->getPost('vendor'),
            'tipe'         => $this->request->getPost('tipe'),
            'no_polisi'    => $this->request->getPost('no_polisi'),
            'tahun'        => $this->request->getPost('tahun'),
            'harga_beli'   => str_replace(['.', ','], '', $this->request->getPost('harga_beli')),
            'harga_jual'   => str_replace(['.', ','], '', $this->request->getPost('harga_jual')),
            'stok'         => $this->request->getPost('stok'),
            'status_jual'  => $this->request->getPost('status_jual') ?? 'tersedia',
            'status_mobil' => $this->request->getPost('status_mobil') ?? 'bekas',
            'foto'         => $fotoName,
            'keterangan'   => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to(base_url('mobil'))->with('success', 'Unit mobil berhasil ditambahkan.');
    }

    /** Form Edit Data Mobil */
    public function edit($id)
    {
        $mobil = $this->model->find($id);
        if (!$mobil) {
            return redirect()->to(base_url('mobil'))->with('error', 'Data tidak ditemukan.');
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
        if (!$mobil) return redirect()->to(base_url('mobil'));

        $fotoName = $mobil['foto'];
        $foto = $this->request->getFile('foto');

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Hapus foto lama jika ada
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
            'no_polisi'    => $this->request->getPost('no_polisi'),
            'tahun'        => $this->request->getPost('tahun'),
            'harga_beli'   => str_replace(['.', ','], '', $this->request->getPost('harga_beli')),
            'harga_jual'   => str_replace(['.', ','], '', $this->request->getPost('harga_jual')),
            'stok'         => $this->request->getPost('stok'),
            'status_jual'  => $this->request->getPost('status_jual'),
            'status_mobil' => $this->request->getPost('status_mobil'),
            'foto'         => $fotoName,
            'keterangan'   => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to(base_url('mobil'))->with('success', 'Data unit mobil berhasil diperbarui.');
    }

    /** Hapus Data Mobil */
    public function delete($id)
    {
        $mobil = $this->model->find($id);
        if ($mobil) {
            // Gunakan FCPATH agar mengarah ke folder public secara absolut
            if ($mobil['foto'] && file_exists(FCPATH . 'uploads/mobil/' . $mobil['foto'])) {
                unlink(FCPATH . 'uploads/mobil/' . $mobil['foto']);
            }
            $this->model->delete($id);
        }
        return redirect()->to(base_url('mobil'))->with('success', 'Unit mobil berhasil dihapus dari sistem.');
    }
}