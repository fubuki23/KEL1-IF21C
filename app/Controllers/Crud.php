<?php

namespace App\Controllers;

class Crud extends BaseController
{
	function __construct()
	{
		$this->model = new \App\Models\ModelCrud();
	}
	public function hapus($id)
	{
		$this->model->delete($id);
		return redirect()->to('Crud/inventaris');
	}
	public function edit($id)
	{
		return json_encode($this->model->find($id));
	}

	public function simpan()
	{
		$validasi  = \Config\Services::validation();
		$aturan = [
			'kode' => [
				'label' => 'Kode',
				'rules' => 'required|min_length[2]',
				'errors' => [
					'required' => '{field} harus diisi',
					'min_length' => 'Minimum karakter untuk field {field} adalah 5 karakter'
				]
			],
			'nama' => [ 
				'label' => 'Nama',
				'rules' => 'required|min_length[1]',
				'errors' => [
					'required' => '{field} harus diisi',
					'min_length' => 'Minimum karakter untuk field {field} adalah 5 karakter',
				]
			],
			'jumlah' => [
				'label' => 'Jumlah',
				'rules' => 'required',
				'errors' => [
					'required' => '{field} harus diisi'
				]
			],
		];

		$validasi->setRules($aturan);
		if ($validasi->withRequest($this->request)->run()) {
			$id = $this->request->getPost('id');
			$kode = $this->request->getPost('kode');
			$nama = $this->request->getPost('nama');
			$jenis = $this->request->getPost('jenis');
			$jumlah = $this->request->getPost('jumlah');

			$data = [
				'id' => $id,
				'kode' => $kode,
				'nama' => $nama,
				'jenis' => $jenis,
				'jumlah' => $jumlah
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
	public function inventaris()
	{
		$jumlahBaris = 5;
		$katakunci = $this->request->getGet('katakunci');
		if ($katakunci) {
			$pencarian = $this->model->cari($katakunci);
		} else {
			$pencarian = $this->model;
		}
		$data['katakunci'] = $katakunci;
		$data['datainventaris'] = $pencarian->orderBy('id', 'desc')->paginate($jumlahBaris);
		$data['pager'] = $this->model->pager;
		$data['nomor'] = ($this->request->getVar('page') == 1) ? '0' : $this->request->getVar('page');
		return view('web/aset/inventaris', $data);
	}
}
