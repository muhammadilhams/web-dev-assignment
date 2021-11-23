<?php

namespace App\Controllers;

class mahasiswa extends BaseController
{
	function __construct()
	{
		$this->model = new \App\Models\Modelmahasiswa();
	}
	public function hapus($id)
	{
		$this->model->delete($id);
		return redirect()->to('mahasiswa');
	}
	public function edit($id)
	{
		return json_encode($this->model->find($id));
	}

	public function simpan()
	{
		$validasi  = \Config\Services::validation();
		$aturan = [
			'nama' => [
				'label' => 'Nama',
				'rules' => 'required|min_length[5]',
				'errors' => [
					'required' => '{field} harus diisi',
					'min_length' => 'Minimum karakter untuk field {field} adalah 5 karakter'
				]
			],
			'alamat' => [
				'label' => 'Alamat',
				'rules' => 'required|min_length[5]',
				'errors' => [
					'required' => '{field} harus diisi',
					'min_length' => 'Minimum karakter untuk field {field} adalah 5 karakter'
				]
			],
		];

		$validasi->setRules($aturan);
		if ($validasi->withRequest($this->request)->run()) {
			$id = $this->request->getPost('id');
			$nama = $this->request->getPost('nama');
			$nim = $this->request->getPost('nim');
			$alamat = $this->request->getPost('alamat');
            $prodi = $this->request->getPost('prodi');
			$data = [
				'id' => $id,
				'nama' => $nama,
				'nim' => $nim,
				'alamat' => $alamat,
                'prodi' => $prodi,
			];

			$this->model->save($data);

			$hasil['sukses'] = "Berhasil memasukkan data";
			$hasil['error'] = true;
		} else {
			$hasil['sukses'] = false;
			$hasil['error'] = $validasi->listErrors();
		}


		return json_encode($hasil);
	}
	public function index()
	{
		$jumlahBaris = 5;
		$katakunci = $this->request->getGet('katakunci');
		if ($katakunci) {
			$pencarian = $this->model->cari($katakunci);
		} else {
			$pencarian = $this->model;
		}
		$data['katakunci'] = $katakunci;
		$data['datamahasiswa'] = $pencarian->orderBy('id', 'desc')->paginate($jumlahBaris);
		$data['pager'] = $this->model->pager;
		$data['nomor'] = ($this->request->getVar('page') == 1) ? '0' : $this->request->getVar('page');
		return view('mahasiswa_view', $data);
	}
}