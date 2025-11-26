<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PesananModel;
use App\Models\ProdukModel;
use App\Models\KategoriModel;
use App\Models\UserModel;
use App\Models\PembayaranModel;
use App\Models\DetailPesananModel;
use App\Models\PengirimanModel;
use App\Models\UlasanModel;

class Pemilik extends BaseController
{
    protected $pesananModel;
    protected $produkModel;
    protected $kategoriModel;
    protected $userModel;
    protected $pembayaranModel;
    protected $detailPesananModel;
    protected $pengirimanModel;
    protected $ulasanModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriModel();
        $this->userModel = new UserModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->detailPesananModel = new DetailPesananModel();
        $this->pengirimanModel = new PengirimanModel();
        $this->ulasanModel = new UlasanModel();
    }

    public function dashboard()
    {
        // Use same data preparation as admin dashboard so UI matches
        $bulan_ini = date('m');
        $tahun_ini = date('Y');

        $total_penjualan_bulan = $this->pesananModel
            ->select('SUM(total) as total')
            ->where('MONTH(tanggal_pesanan)', $bulan_ini)
            ->where('YEAR(tanggal_pesanan)', $tahun_ini)
            ->whereIn('status', ['diproses', 'dikirim', 'selesai'])
            ->first();

        $total_penjualan_tahun = $this->pesananModel
            ->select('SUM(total) as total')
            ->where('YEAR(tanggal_pesanan)', $tahun_ini)
            ->whereIn('status', ['diproses', 'dikirim', 'selesai'])
            ->first();

        $jumlah_pesanan_diproses = $this->pesananModel
            ->where('status', 'diproses')
            ->countAllResults();

        $total_pesanan_aktif = $this->pesananModel
            ->whereIn('status', ['menunggu_pembayaran', 'diproses', 'dikirim'])
            ->countAllResults();

        $persentase_pesanan_diproses = ($total_pesanan_aktif > 0) ?
                                          ($jumlah_pesanan_diproses / $total_pesanan_aktif) * 100 : 0;

        $jumlah_menunggu_pembayaran = $this->pesananModel
            ->where('status', 'menunggu_pembayaran')
            ->countAllResults();

        $penjualan_bulanan = $this->getPenjualanBulanan();
        $kategori_terlaris = $this->getKategoriTerlaris();
        $pesanan_terbaru = $this->getPesananTerbaru();
        $produk_stok_menipis = $this->getProdukStokMenipis();
        $pesanan_preorder = $this->getPesananPreorder();
        $metode_pembayaran = $this->getMetodePembayaran();
        $kategori_warna = $this->getKategoriWarna();

        $data = [
            'title' => 'Dashboard Pemilik - Mantra Jaya Tani',
            'total_penjualan_bulan' => $total_penjualan_bulan['total'] ?? 0,
            'total_penjualan_tahun' => $total_penjualan_tahun['total'] ?? 0,
            'jumlah_pesanan_diproses' => $jumlah_pesanan_diproses,
            'persentase_pesanan_diproses' => $persentase_pesanan_diproses,
            'jumlah_menunggu_pembayaran' => $jumlah_menunggu_pembayaran,
            'penjualan_bulanan' => json_encode($penjualan_bulanan),
            'kategori_terlaris' => json_encode($kategori_terlaris),
            'pesanan_terbaru' => $pesanan_terbaru,
            'produk_stok_menipis' => $produk_stok_menipis,
            'pesanan_preorder' => $pesanan_preorder,
            'metode_pembayaran' => json_encode($metode_pembayaran),
            'kategori_warna' => $kategori_warna,
            'total_pelanggan' => $this->userModel->where('role', 'pelanggan')->countAllResults(),
            'produk_terlaris' => $this->getProdukTerlaris(),
            'rating_rata_rata' => $this->getRatingRataRata(),
        ];

        return view('pemilik/dashboard', $data);
    }

    // Below, call Admin\Laporan methods to reuse report logic
    public function laporanPenjualan()
    {
        // Build laporan data similar to Admin\Laporan::index but scoped for pemilik view
        $tanggal_mulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');

        $db = \Config\Database::connect();

        // Total pendapatan (selesai)
        $builder = $db->table('pesanan');
        $builder->selectSum('total');
        $builder->where('status', 'selesai');
        $builder->where('tanggal_pesanan >=', $tanggal_mulai . ' 00:00:00');
        $builder->where('tanggal_pesanan <=', $tanggal_akhir . ' 23:59:59');
        $query = $builder->get();
        $total_pendapatan = $query->getRow()->total ?? 0;

        // Total pesanan
        $builder = $db->table('pesanan');
        $builder->where('tanggal_pesanan >=', $tanggal_mulai . ' 00:00:00');
        $builder->where('tanggal_pesanan <=', $tanggal_akhir . ' 23:59:59');
        $total_pesanan = $builder->countAllResults();

        // Pesanan detail
        $builder = $db->table('pesanan');
        $builder->select('pesanan.*, users.nama_lengkap');
        $builder->join('users', 'users.id = pesanan.user_id');
        $builder->where('pesanan.tanggal_pesanan >=', $tanggal_mulai . ' 00:00:00');
        $builder->where('pesanan.tanggal_pesanan <=', $tanggal_akhir . ' 23:59:59');
        $builder->orderBy('pesanan.tanggal_pesanan', 'DESC');
        $pesanan = $builder->get()->getResultArray();

        // Produk terlaris
        $builder = $db->table('detail_pesanan');
        $builder->select('produk.id, produk.nama_produk, produk.harga, kategori.nama_kategori, SUM(detail_pesanan.jumlah) as jumlah_terjual, SUM(detail_pesanan.jumlah * detail_pesanan.harga_satuan) as total_pendapatan');
        $builder->join('pesanan', 'pesanan.id = detail_pesanan.pesanan_id');
        $builder->join('produk', 'produk.id = detail_pesanan.produk_id');
        $builder->join('kategori', 'kategori.id = produk.kategori_id');
        $builder->where('pesanan.tanggal_pesanan >=', $tanggal_mulai . ' 00:00:00');
        $builder->where('pesanan.tanggal_pesanan <=', $tanggal_akhir . ' 23:59:59');
        $builder->where('pesanan.status !=', 'dibatalkan');
        $builder->groupBy('produk.id');
        $builder->orderBy('jumlah_terjual', 'DESC');
        $builder->limit(10);
        $produk_terlaris = $builder->get()->getResultArray();

        $data = [
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_akhir' => $tanggal_akhir,
            'total_pendapatan' => $total_pendapatan,
            'total_pesanan' => $total_pesanan,
            'pesanan' => $pesanan,
            'produk_terlaris' => $produk_terlaris,
        ];

        return view('pemilik/laporan/penjualan', $data);
    }

    public function cetakLaporanPenjualan()
    {
        // For now, redirect back to pemilik laporan (export not implemented for pemilik)
        return redirect()->to('/pemilik/laporan/penjualan')->with('error', 'Export Laporan belum tersedia untuk pemilik');
    }

    public function laporanPendapatan()
    {
        return redirect()->to('/pemilik/laporan/penjualan');
    }

    public function cetakLaporanPendapatan()
    {
        return redirect()->to('/pemilik/laporan/penjualan')->with('error', 'Export Laporan belum tersedia untuk pemilik');
    }

    public function laporanProduk()
    {
        // Reuse the same view for now
        return redirect()->to('/pemilik/laporan/penjualan');
    }

    public function cetakLaporanProduk()
    {
        return redirect()->to('/pemilik/laporan/penjualan')->with('error', 'Export Laporan belum tersedia untuk pemilik');
    }

    public function statistik()
    {
        return redirect()->to('/pemilik/laporan/penjualan');
    }

    public function produkTerlaris()
    {
        return redirect()->to('/pemilik/laporan/penjualan');
    }

    public function pelangganTeraktif()
    {
        return redirect()->to('/pemilik/laporan/penjualan');
    }

    // Simple staff placeholders - can be improved later
    public function staff()
    {
        return view('pemilik/staff/index');
    }

    public function addStaff()
    {
        return view('pemilik/staff/add');
    }

    public function saveStaff()
    {
        return redirect()->to('/pemilik/staff');
    }

    public function editStaff($id)
    {
        return view('pemilik/staff/edit', ['id' => $id]);
    }

    public function updateStaff($id)
    {
        return redirect()->to('/pemilik/staff');
    }

    public function deleteStaff($id)
    {
        return redirect()->to('/pemilik/staff');
    }

    // Helper functions copied from Admin Dashboard where necessary
    private function getPenjualanBulanan()
    {
        $hasil = [];
        $bulan_sekarang = date('m');
        $tahun_sekarang = date('Y');

        for ($i = 0; $i < 12; $i++) {
            $bulan = $bulan_sekarang - $i;
            $tahun = $tahun_sekarang;

            if ($bulan <= 0) {
                $bulan = 12 + $bulan;
                $tahun--; 
            }

            $total_bulan = $this->pesananModel
                ->select('SUM(total) as total')
                ->where('MONTH(tanggal_pesanan)', $bulan)
                ->where('YEAR(tanggal_pesanan)', $tahun)
                ->whereIn('status', ['diproses', 'dikirim', 'selesai'])
                ->first();

            $nama_bulan = $this->getNamaBulan($bulan);

            $hasil[] = [
                'bulan' => $nama_bulan,
                'total' => (float)($total_bulan['total'] ?? 0)
            ];
        }

        return array_reverse($hasil);
    }

    private function getNamaBulan($bulan)
    {
        $nama_bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $nama_bulan[$bulan];
    }

    private function getKategoriTerlaris()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT k.nama_kategori, COUNT(dp.id) as jumlah_terjual
            FROM detail_pesanan dp
            JOIN produk p ON dp.produk_id = p.id
            JOIN kategori k ON p.kategori_id = k.id
            JOIN pesanan ps ON dp.pesanan_id = ps.id
            WHERE ps.status IN ('diproses', 'dikirim', 'selesai')
            GROUP BY k.id, k.nama_kategori
            ORDER BY jumlah_terjual DESC
            LIMIT 5");

        return $query->getResultArray();
    }

    private function getPesananTerbaru()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT p.*, u.nama_lengkap as nama_pelanggan
            FROM pesanan p
            JOIN users u ON p.user_id = u.id
            ORDER BY p.tanggal_pesanan DESC
            LIMIT 10");

        return $query->getResultArray();
    }

    private function getProdukStokMenipis()
    {
        return $this->produkModel
            ->select('id, nama_produk, stok, is_preorder')
            ->where('stok <=', 10)
            ->where('is_preorder', false)
            ->orderBy('stok', 'ASC')
            ->limit(10)
            ->find();
    }

    private function getPesananPreorder()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT p.*, u.nama_lengkap as nama_pelanggan
            FROM pesanan p
            JOIN users u ON p.user_id = u.id
            WHERE p.is_preorder = 1
            AND p.status IN ('diproses', 'menunggu_pembayaran')
            ORDER BY p.tanggal_estimasi_pengiriman ASC
            LIMIT 5");

        return $query->getResultArray();
    }

    private function getMetodePembayaran()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT payment_type, COUNT(*) as jumlah
            FROM pembayaran
            WHERE status = 'berhasil'
            GROUP BY payment_type
            ORDER BY jumlah DESC");

        return $query->getResultArray();
    }

    private function getKategoriWarna()
    {
        $kategori = $this->kategoriModel->findAll();
        $colors = ['text-primary','text-success','text-info','text-warning','text-danger','text-secondary','text-dark','text-muted'];
        $kategori_warna = [];
        $i = 0;
        foreach ($kategori as $kat) {
            $kategori_warna[$kat['nama_kategori']] = $colors[$i % count($colors)];
            $i++;
        }
        return $kategori_warna;
    }

    private function getProdukTerlaris()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT p.id, p.nama_produk, SUM(dp.jumlah) as jumlah_terjual
            FROM detail_pesanan dp
            JOIN produk p ON dp.produk_id = p.id
            JOIN pesanan ps ON dp.pesanan_id = ps.id
            WHERE ps.status IN ('diproses', 'dikirim', 'selesai')
            GROUP BY p.id, p.nama_produk
            ORDER BY jumlah_terjual DESC
            LIMIT 5");

        return $query->getResultArray();
    }

    private function getRatingRataRata()
    {
        $rating = $this->ulasanModel->selectAvg('rating')->first();
        return number_format(($rating['rating'] ?? 0), 1);
    }
}
