<?php

namespace App\Controllers;

use App\Models\FavoritModel;
use App\Models\ProdukModel;

class Favorit extends BaseController
{
	protected $favoritModel;
	protected $produkModel;

	public function __construct()
	{
		$this->favoritModel = new FavoritModel();
		$this->produkModel = new ProdukModel();
	}

	public function index()
	{
		$userId = session()->get('user_id');
		$favorit = $this->favoritModel->getUserFavorites($userId);

		$data = [
			'title' => 'Favorit Saya',
			'favorit' => $favorit
		];

		return $this->render('home/favorit/index', $data);
	}

	public function tambah($produkId)
	{
		if (!session()->get('logged_in')) {
			return redirect()->to('/auth')->with('error', 'Silakan login terlebih dahulu.');
		}

		$userId = session()->get('user_id');

		// Ensure product exists
		$produk = $this->produkModel->find($produkId);
		if (!$produk) {
			return redirect()->back()->with('error', 'Produk tidak ditemukan');
		}

		$this->favoritModel->toggleFavorite($userId, (int) $produkId);

		return redirect()->back()->with('success', 'Produk diperbarui di daftar favorit.');
	}

	public function hapus($produkId)
	{
		if (!session()->get('logged_in')) {
			return redirect()->to('/auth')->with('error', 'Silakan login terlebih dahulu.');
		}

		$userId = session()->get('user_id');
		$this->favoritModel->where('user_id', $userId)->where('produk_id', (int) $produkId)->delete();

		return redirect()->back()->with('success', 'Produk dihapus dari favorit.');
	}
}


