<?php

namespace App\Controllers;

use App\Models\SupplierModel;
use CodeIgniter\Controller;

class Supplier extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new SupplierModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data = [
            'title'     => 'Kelola Data Supplier',
            'suppliers' => $this->model->findAll(), 
        ];
        return view('supplier/index', $data);
    }

    public function create()
    {
        return view('supplier/create', ['title' => 'Tambah Data Supplier']);
    }

    public function store()
    {
        $this->model->insert([
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'alamat'        => $this->request->getPost('alamat'),
            'telepon'       => $this->request->getPost('telepon'),
            'email'         => $this->request->getPost('email'),
            'no_hp'         => $this->request->getPost('no_hp'),
        ]);
        return redirect()->to(base_url('supplier'))->with('success', 'Data berhasil ditambah');
    }

    public function edit($id)
    {
        $data = [
            'title'    => 'Edit Data Supplier',
            'supplier' => $this->model->find($id)
        ];
        return view('supplier/edit', $data);
    }

    public function update($id)
    {
        $this->model->update($id, [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'alamat'        => $this->request->getPost('alamat'),
            'telepon'       => $this->request->getPost('telepon'),
            'email'         => $this->request->getPost('email'),
            'no_hp'         => $this->request->getPost('no_hp'),
        ]);
        return redirect()->to(base_url('supplier'))->with('success', 'Data berhasil diubah');
    }

    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('supplier'))->with('success', 'Data berhasil dihapus');
    }
}