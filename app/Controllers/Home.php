<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\KategoriModel;
use App\Models\FotoProdukModel;

class Home extends BaseController
{
    protected $produkModel;
    protected $kategoriModel;
    protected $fotoProdukModel;
    
    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriModel();
        $this->fotoProdukModel = new FotoProdukModel();
    }

    public function index()
    {
        // Get featured products (newest 8 products)
        $produk = $this->produkModel->select('produk.*, kategori.nama_kategori')
                                   ->join('kategori', 'kategori.id = produk.kategori_id')
                                   ->orderBy('produk.dibuat_pada', 'DESC')
                                   ->limit(8)
                                   ->find();
        
        // Get all categories for banners
        $kategori = $this->kategoriModel->findAll();
        
        // Get product images
        foreach ($produk as &$item) {
            $item['foto'] = $this->fotoProdukModel->where('produk_id', $item['id'])
                                                 ->orderBy('urutan', 'ASC')
                                                 ->findAll(2); // Get first 2 images for each product
        }
        
        $data = [
            'title' => 'Mantra Jaya Tani - Gordyn & Wallpaper',
            'produk' => $produk,
            'kategori' => $kategori,
        ];
        
        return $this->render('home/index', $data);
    }
}