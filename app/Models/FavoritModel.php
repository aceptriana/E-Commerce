<?php

namespace App\Models;

use CodeIgniter\Model;

class FavoritModel extends Model
{
	protected $table = 'favorit';
	protected $primaryKey = 'id';
	protected $allowedFields = ['user_id', 'produk_id', 'created_at'];
	public $useTimestamps = false;

	public function isFavorited(int $userId, int $produkId): bool
	{
		return $this->where('user_id', $userId)
			->where('produk_id', $produkId)
			->countAllResults() > 0;
	}

	public function toggleFavorite(int $userId, int $produkId): bool
	{
		if ($this->isFavorited($userId, $produkId)) {
			return (bool) $this->where('user_id', $userId)
				->where('produk_id', $produkId)
				->delete();
		}
		return (bool) $this->insert([
			'user_id' => $userId,
			'produk_id' => $produkId,
			'created_at' => date('Y-m-d H:i:s')
		]);
	}

	public function getUserFavorites(int $userId): array
	{
		return $this->select('favorit.id as favorit_id, produk.*, kategori.nama_kategori, MIN(foto_produk.url_foto) as gambar')
			->join('produk', 'produk.id = favorit.produk_id')
			->join('kategori', 'kategori.id = produk.kategori_id', 'left')
			->join('foto_produk', 'foto_produk.produk_id = produk.id', 'left')
			->where('favorit.user_id', $userId)
			->groupBy('favorit.id, produk.id, produk.nama_produk, produk.deskripsi, produk.harga, produk.stok, produk.berat, produk.kategori_id, produk.is_preorder, produk.tanggal_rilis, produk.dibuat_pada, produk.diperbarui_pada, kategori.nama_kategori')
			->orderBy('favorit.created_at', 'DESC')
			->findAll();
	}
}


