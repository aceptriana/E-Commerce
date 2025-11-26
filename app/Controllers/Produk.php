<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\KategoriModel;
use App\Models\FotoProdukModel;
use App\Models\UlasanModel;

class Produk extends BaseController
{
    protected $produkModel;
    protected $kategoriModel;
    protected $fotoProdukModel;
    protected $ulasanModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriModel();
        $this->fotoProdukModel = new FotoProdukModel();
        $this->ulasanModel = new UlasanModel();
    }

    public function index()
    {
        $produk = $this->produkModel->getProdukWithKategori();
        
        // Get photos for each product
        foreach($produk as &$item) {
            $foto = $this->produkModel->getFotoProduk($item['id']);
            $item['foto'] = !empty($foto) ? $foto[0]['url_foto'] : 'img/products/product_placeholder_square_medium.jpg';
        }

        $data = [
            'title' => 'Semua Produk',
            'produk' => $produk,
            'kategori' => $this->kategoriModel->findAll()
        ];

        return $this->render('home/produk/index', $data);
    }

    public function detail($id)
    {
        $produk = $this->produkModel->getProdukById($id);
        
        if (!$produk) {
            return redirect()->to(base_url('produk'))->with('error', 'Produk tidak ditemukan');
        }

        // Get product photos
        $fotos = $this->produkModel->getFotoProduk($id);
        
        // Get related products (same category)
        $relatedProducts = $this->produkModel->where('kategori_id', $produk['kategori_id'])
                                           ->where('id !=', $id)
                                           ->limit(4)
                                           ->find();
        
        // Get photos for related products
        foreach($relatedProducts as &$related) {
            $related_foto = $this->produkModel->getFotoProduk($related['id']);
            $related['foto'] = !empty($related_foto) ? $related_foto[0]['url_foto'] : 'img/products/product_placeholder_square_medium.jpg';
        }

        // Get reviews with user info
        $ulasan = $this->ulasanModel->select('ulasan.*, users.nama_lengkap')
                                   ->join('users', 'users.id = ulasan.user_id')
                                   ->where('ulasan.produk_id', $id)
                                   ->orderBy('ulasan.tanggal', 'DESC')
                                   ->findAll();
        
        // Calculate average rating
        $ratingData = $this->ulasanModel->getRataRataRating($id);
        $rating = $ratingData['rating'] ?? 0;

        // Get kategori info
        $kategori = $this->kategoriModel->find($produk['kategori_id']);

        $data = [
            'title' => $produk['nama_produk'],
            'produk' => $produk,
            'fotos' => $fotos,
            'foto_produk' => $fotos,
            'relatedProducts' => $relatedProducts,
            'related_products' => $relatedProducts,
            'ulasan' => $ulasan,
            'rating' => $rating,
            'kategori' => $kategori
        ];

        return $this->render('produk/detail', $data);
    }

    public function kategori($id)
    {
        $kategori = $this->kategoriModel->find($id);
        
        if (!$kategori) {
            return redirect()->to(base_url('produk'))->with('error', 'Kategori tidak ditemukan');
        }

        $produk = $this->produkModel->where('kategori_id', $id)->findAll();
        
        // Get photos for each product
        foreach($produk as &$item) {
            $foto = $this->produkModel->getFotoProduk($item['id']);
            $item['foto'] = !empty($foto) ? $foto[0]['url_foto'] : 'img/products/product_placeholder_square_medium.jpg';
        }

        // Get all categories with product count
        $all_kategori = $this->kategoriModel->findAll();
        foreach($all_kategori as &$kat) {
            $kat['jumlah_produk'] = $this->produkModel->where('kategori_id', $kat['id'])->countAllResults();
        }

        $data = [
            'title' => $kategori['nama_kategori'],
            'kategori' => $kategori,
            'all_kategori' => $all_kategori,
            'produk' => $produk
        ];

        return $this->render('home/produk/kategori', $data);
    }

    public function preorder()
    {
        $produk = $this->produkModel->where('is_preorder', 1)->findAll();
        
        // Get photos for each product
        foreach($produk as &$item) {
            $foto = $this->produkModel->getFotoProduk($item['id']);
            $item['foto'] = !empty($foto) ? $foto[0]['url_foto'] : 'img/products/product_placeholder_square_medium.jpg';
        }

        $data = [
            'title' => 'Pre Order',
            'produk' => $produk
        ];

        return $this->render('home/produk/preorder', $data);
    }

    public function search()
    {
        $keyword = $this->request->getGet('keyword');
        
        if (empty($keyword)) {
            return redirect()->to(base_url('produk'));
        }

        $produk = $this->produkModel->select('produk.*, kategori.nama_kategori')
                                  ->join('kategori', 'kategori.id = produk.kategori_id')
                                  ->like('produk.nama_produk', $keyword)
                                  ->orLike('produk.deskripsi', $keyword)
                                  ->findAll();
        
        // Get photos for each product
        foreach($produk as &$item) {
            $foto = $this->produkModel->getFotoProduk($item['id']);
            $item['foto'] = !empty($foto) ? $foto[0]['url_foto'] : 'img/products/product_placeholder_square_medium.jpg';
        }

        $data = [
            'title' => 'Hasil Pencarian: ' . $keyword,
            'produk' => $produk,
            'keyword' => $keyword,
            'kategori' => $this->kategoriModel->findAll()
        ];

        return $this->render('home/produk/search', $data);
    }
}
