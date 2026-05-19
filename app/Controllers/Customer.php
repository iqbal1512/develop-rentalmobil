<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Controller;

class Customer extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new CustomerModel();
        helper(['form', 'url']);
    }

    /** Menampilkan Data Customer */
    public function index()
    {
        return view('customer/index', [
            'title'     => 'Kelola Data Customer',
            // PERBAIKAN: Ganti 'customer' menjadi 'customers' agar sinkron dengan View
            'customers' => $this->model->orderBy('nama', 'ASC')->findAll(),
        ]);
    }

    /** Form Tambah Customer */
    public function create()
    {
        return view('customer/create', ['title' => 'Tambah Data Customer']);
    }

    /** Simpan Data Baru */
    public function store()
    {
        $rules = [
            'nama'   => 'required|max_length[100]',
            'alamat' => 'required',
            'no_ktp' => 'required|min_length[16]|is_unique[customer.no_ktp]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Cek kembali inputan anda. No KTP mungkin sudah terdaftar.');
        }

        $fotoKtpName = null;
        $foto = $this->request->getFile('foto_ktp');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $fotoKtpName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/ktp', $fotoKtpName);
        }

        $this->model->insert([
            'nama'    => $this->request->getPost('nama'),
            'alamat'  => $this->request->getPost('alamat'),
            'telepon' => $this->request->getPost('telepon'),
            'no_ktp'  => $this->request->getPost('no_ktp'),
            'email'   => $this->request->getPost('email'),
            'no_zip'  => $this->request->getPost('no_zip'),
            'foto_ktp'=> $fotoKtpName,
        ]);

        return redirect()->to(base_url('customer'))->with('success', 'Customer berhasil ditambahkan.');
    }

    /** Form Edit Customer */
    public function edit($id)
    {
        $customer = $this->model->find($id);
        if (!$customer) {
            return redirect()->to(base_url('customer'))->with('error', 'Data tidak ditemukan.');
        }
        return view('customer/edit', [
            'title'    => 'Edit Data Customer', 
            'customer' => $customer
        ]);
    }

    /** Update Data */
    public function update($id)
    {
        $data = [
            'nama'    => $this->request->getPost('nama'),
            'alamat'  => $this->request->getPost('alamat'),
            'telepon' => $this->request->getPost('telepon'),
            'no_ktp'  => $this->request->getPost('no_ktp'),
            'email'   => $this->request->getPost('email'),
            'no_zip'  => $this->request->getPost('no_zip'),
        ];

        $foto = $this->request->getFile('foto_ktp');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $fotoKtpName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/ktp', $fotoKtpName);
            $data['foto_ktp'] = $fotoKtpName;
            
            // Opsional: Hapus foto lama
            $oldCustomer = $this->model->find($id);
            if ($oldCustomer && !empty($oldCustomer['foto_ktp'])) {
                $oldPath = ROOTPATH . 'public/uploads/ktp/' . $oldCustomer['foto_ktp'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $this->model->update($id, $data);

        return redirect()->to(base_url('customer'))->with('success', 'Data customer diperbarui.');
    }

    /** Hapus Data */
    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('customer'))->with('success', 'Data customer dihapus.');
    }
}