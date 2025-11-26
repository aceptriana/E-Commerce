<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReturnModel;
use App\Models\PesananModel;

class Returns extends BaseController
{
    protected $returnModel;
    protected $orderModel;

    public function __construct()
    {
        $this->returnModel = new ReturnModel();
        $this->orderModel = new PesananModel();
    }

    public function index()
    {
        // Admin-only; BaseController's auth filter handles session verification
        $returns = $this->returnModel->orderBy('created_at', 'DESC')->findAll();

        return view('admin/returns/index', ['returns' => $returns]);
    }

    public function approve($id)
    {
        $ret = $this->returnModel->find($id);
        if (!$ret) return redirect()->back()->with('error', 'Pengembalian tidak ditemukan.');

        $this->returnModel->update($id, ['status' => 'approved', 'admin_note' => $this->request->getPost('admin_note')]);
        // Update order status to 'dibatalkan' to represent returned/cancelled order state in existing enum
        $this->orderModel->update($ret['order_id'], ['status' => 'dibatalkan']);
        return redirect()->back()->with('success', 'Permintaan retur telah disetujui.');
    }

    public function reject($id)
    {
        $ret = $this->returnModel->find($id);
        if (!$ret) return redirect()->back()->with('error', 'Pengembalian tidak ditemukan.');

        $this->returnModel->update($id, ['status' => 'rejected', 'admin_note' => $this->request->getPost('admin_note')]);
        // Do not change order status; keep as-is
        return redirect()->back()->with('success', 'Permintaan retur telah ditolak.');
    }
}
