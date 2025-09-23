<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends BaseController
{
    protected $db;
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    
    public function index()
    {
        // Ambil parameter tanggal dari URL
        $tanggal_mulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        
        // Data untuk tampilan
        $data = [
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_akhir' => $tanggal_akhir,
            
            // Total pendapatan dalam periode
            'total_pendapatan' => $this->getTotalPendapatan($tanggal_mulai, $tanggal_akhir),
            
            // Total pesanan dalam periode
            'total_pesanan' => $this->getTotalPesanan($tanggal_mulai, $tanggal_akhir),
            
            // Data pesanan
            'pesanan' => $this->getPesanan($tanggal_mulai, $tanggal_akhir),
            
            // Data produk terlaris
            'produk_terlaris' => $this->getProdukTerlaris($tanggal_mulai, $tanggal_akhir),
        ];
        
        return view('admin/laporan/index', $data);
    }
    
    // Fungsi untuk mengekspor data ke Excel
    public function export()
    {
        // Ambil parameter tanggal dari URL
        $tanggal_mulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        
        // Buat instance spreadsheet
        $spreadsheet = new Spreadsheet();
        
        // Set active sheet dan tambahkan sheet produk terlaris
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('Laporan Penjualan');
        $sheet = $spreadsheet->getActiveSheet();
        
        // SHEET 1: LAPORAN PENJUALAN
        // Set header
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN TOKO KALINA');
        $sheet->setCellValue('A2', 'Periode: ' . date('d-m-Y', strtotime($tanggal_mulai)) . ' s/d ' . date('d-m-Y', strtotime($tanggal_akhir)));
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'ID Pesanan');
        $sheet->setCellValue('C4', 'Tanggal');
        $sheet->setCellValue('D4', 'Pelanggan');
        $sheet->setCellValue('E4', 'Total');
        $sheet->setCellValue('F4', 'Status');
        $sheet->setCellValue('G4', 'Pre-Order');
        
        // Ambil data pesanan
        $pesanan = $this->getPesanan($tanggal_mulai, $tanggal_akhir);
        
        // Isi data pesanan
        $no = 1;
        $row = 5;
        $totalPendapatan = 0;
        
        foreach ($pesanan as $p) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, '#' . str_pad($p['id'], 5, '0', STR_PAD_LEFT));
            $sheet->setCellValue('C' . $row, date('d-m-Y H:i', strtotime($p['tanggal_pesanan'])));
            $sheet->setCellValue('D' . $row, $p['nama_lengkap']);
            $sheet->setCellValue('E' . $row, $p['total']);
            
            // Status pesanan
            $statusText = '';
            switch ($p['status']) {
                case 'menunggu_pembayaran':
                    $statusText = 'Menunggu Pembayaran';
                    break;
                case 'diproses':
                    $statusText = 'Diproses';
                    break;
                case 'dikirim':
                    $statusText = 'Dikirim';
                    break;
                case 'selesai':
                    $statusText = 'Selesai';
                    break;
                case 'dibatalkan':
                    $statusText = 'Dibatalkan';
                    break;
                default:
                    $statusText = 'Unknown';
            }
            $sheet->setCellValue('F' . $row, $statusText);
            
            // Pre-order
            $sheet->setCellValue('G' . $row, $p['is_preorder'] ? 'Ya' : 'Tidak');
            
            if ($p['status'] == 'selesai') {
                $totalPendapatan += $p['total'];
            }
            
            $no++;
            $row++;
        }
        
        // Tambahkan ringkasan
        $row += 2;
        $sheet->setCellValue('A' . $row, 'RINGKASAN');
        $row += 1;
        $sheet->setCellValue('A' . $row, 'Total Pendapatan:');
        $sheet->setCellValue('B' . $row, 'Rp ' . number_format($this->getTotalPendapatan($tanggal_mulai, $tanggal_akhir), 0, ',', '.'));
        $row += 1;
        $sheet->setCellValue('A' . $row, 'Total Pesanan:');
        $sheet->setCellValue('B' . $row, $this->getTotalPesanan($tanggal_mulai, $tanggal_akhir));
        
        // SHEET 2: PRODUK TERLARIS
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $spreadsheet->getActiveSheet()->setTitle('Produk Terlaris');
        $sheetTerlaris = $spreadsheet->getActiveSheet();
        
        // Set header
        $sheetTerlaris->setCellValue('A1', 'PRODUK TERLARIS TOKO KALINA');
        $sheetTerlaris->setCellValue('A2', 'Periode: ' . date('d-m-Y', strtotime($tanggal_mulai)) . ' s/d ' . date('d-m-Y', strtotime($tanggal_akhir)));
        $sheetTerlaris->setCellValue('A4', 'No');
        $sheetTerlaris->setCellValue('B4', 'Nama Produk');
        $sheetTerlaris->setCellValue('C4', 'Kategori');
        $sheetTerlaris->setCellValue('D4', 'Harga');
        $sheetTerlaris->setCellValue('E4', 'Total Terjual');
        $sheetTerlaris->setCellValue('F4', 'Pendapatan');
        
        // Ambil data produk terlaris
        $terlaris = $this->getProdukTerlaris($tanggal_mulai, $tanggal_akhir);
        $no = 1;
        $row = 5;
        
        foreach ($terlaris as $t) {
            $sheetTerlaris->setCellValue('A' . $row, $no);
            $sheetTerlaris->setCellValue('B' . $row, $t['nama_produk']);
            $sheetTerlaris->setCellValue('C' . $row, $t['nama_kategori']);
            $sheetTerlaris->setCellValue('D' . $row, 'Rp ' . number_format($t['harga'], 0, ',', '.'));
            $sheetTerlaris->setCellValue('E' . $row, $t['jumlah_terjual']);
            $sheetTerlaris->setCellValue('F' . $row, 'Rp ' . number_format($t['total_pendapatan'], 0, ',', '.'));
            
            $no++;
            $row++;
        }
        
        // Kembali ke sheet pertama untuk tampilan awal
        $spreadsheet->setActiveSheetIndex(0);
        
        // Styling
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        
        $sheet->getStyle('A1:G1')->applyFromArray($styleArray);
        $sheet->getStyle('A4:G4')->applyFromArray($styleArray);
        $sheetTerlaris->getStyle('A1:F1')->applyFromArray($styleArray);
        $sheetTerlaris->getStyle('A4:F4')->applyFromArray($styleArray);
        
        // Auto size columns
        foreach(range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        foreach(range('A', 'F') as $col) {
            $sheetTerlaris->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'laporan_penjualan_' . date('d-m-Y') . '.xlsx';
        
        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    // Fungsi-fungsi untuk mengambil data laporan
    private function getTotalPendapatan($tanggal_mulai, $tanggal_akhir)
    {
        $builder = $this->db->table('pesanan');
        $builder->selectSum('total');
        $builder->where('status', 'selesai'); // Hanya menghitung pesanan yang selesai
        $builder->where('tanggal_pesanan >=', $tanggal_mulai . ' 00:00:00');
        $builder->where('tanggal_pesanan <=', $tanggal_akhir . ' 23:59:59');
        $query = $builder->get();
        
        return $query->getRow()->total ?? 0;
    }
    
    private function getTotalPesanan($tanggal_mulai, $tanggal_akhir)
    {
        $builder = $this->db->table('pesanan');
        $builder->where('tanggal_pesanan >=', $tanggal_mulai . ' 00:00:00');
        $builder->where('tanggal_pesanan <=', $tanggal_akhir . ' 23:59:59');
        
        return $builder->countAllResults();
    }
    
    private function getPesanan($tanggal_mulai, $tanggal_akhir)
    {
        $builder = $this->db->table('pesanan');
        $builder->select('pesanan.*, users.nama_lengkap');
        $builder->join('users', 'users.id = pesanan.user_id');
        $builder->where('pesanan.tanggal_pesanan >=', $tanggal_mulai . ' 00:00:00');
        $builder->where('pesanan.tanggal_pesanan <=', $tanggal_akhir . ' 23:59:59');
        $builder->orderBy('pesanan.tanggal_pesanan', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    private function getProdukTerlaris($tanggal_mulai, $tanggal_akhir)
    {
        $builder = $this->db->table('detail_pesanan');
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
        
        return $builder->get()->getResultArray();
    }
}