<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\PemesananModel;
use CodeIgniter\Controller;

class Customer extends Controller
{
    protected $model;
    protected $pemesananModel;

    public function __construct()
    {
        $this->model          = new CustomerModel();
        $this->pemesananModel = new PemesananModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        return view('customer/index', [
            'title'     => 'Kelola Data Customer',
            'customers' => $this->model->orderBy('nama', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('customer/create', ['title' => 'Tambah Data Customer']);
    }

    public function store()
    {
        $rules = [
            'nama'    => 'required|max_length[100]|min_length[3]',
            'alamat'  => 'required',
            'telepon' => 'required|numeric|min_length[10]',
            'no_ktp'  => 'required|numeric|exact_length[16]|is_unique[customer.no_ktp]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->insert([
            'nama'    => $this->request->getPost('nama'),
            'alamat'  => $this->request->getPost('alamat'),
            'telepon' => $this->request->getPost('telepon'),
            'no_ktp'  => $this->request->getPost('no_ktp'),
            'email'   => $this->request->getPost('email'),
        ]);

        return redirect()->to(base_url('customer'))->with('success', 'Customer baru berhasil didaftarkan.');
    }

    public function edit($id)
    {
        $customer = $this->model->find($id);
        if (!$customer) {
            return redirect()->to(base_url('customer'))->with('error', 'Data customer tidak ditemukan.');
        }
        return view('customer/edit', [
            'title'    => 'Edit Data Customer', 
            'customer' => $customer
        ]);
    }

    public function update($id)
    {
        $rules = [
            'nama'    => 'required|max_length[100]|min_length[3]',
            'alamat'  => 'required',
            'telepon' => 'required|numeric|min_length[10]',
            'no_ktp'  => "required|numeric|exact_length[16]|is_unique[customer.no_ktp,id_customer,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->model->update($id, [
            'nama'    => $this->request->getPost('nama'),
            'alamat'  => $this->request->getPost('alamat'),
            'telepon' => $this->request->getPost('telepon'),
            'no_ktp'  => $this->request->getPost('no_ktp'),
            'email'   => $this->request->getPost('email'),
        ]);

        return redirect()->to(base_url('customer'))->with('success', 'Data profil customer berhasil diperbarui.');
    }

    public function delete($id)
    {
        $cekRelasiTransaksi = $this->pemesananModel->where('id_customer', $id)->first();

        if ($cekRelasiTransaksi) {
            return redirect()->to(base_url('customer'))->with('error', 'Data customer gagal dihapus! Data ini sedang digunakan dalam transaksi pemesanan.');
        }

        $this->model->delete($id);
        return redirect()->to(base_url('customer'))->with('success', 'Data customer berhasil dihapus dari sistem.');
    }
}