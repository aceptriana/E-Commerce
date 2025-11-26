<?php

namespace App\Controllers;

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
        if (!$this->session->get('logged_in')) return redirect()->to('/auth');

        $userId = $this->session->get('user_id');
        $returns = $this->returnModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();

        return $this->render('returns/index', ['returns' => $returns, 'title' => 'Pengembalian dan Retur']);
    }

    public function create($orderId = null)
    {
        if (!$this->session->get('logged_in')) return redirect()->to('/auth');

        // Validate that order belongs to user
        $userId = $this->session->get('user_id');
        if ($orderId) {
            $order = $this->orderModel->where('id', $orderId)->where('user_id', $userId)->first();
            if (!$order) return redirect()->to('/returns')->with('error', 'Pesanan tidak ditemukan atau bukan milik Anda.');
        }

        return $this->render('returns/create', ['order' => $order ?? null]);
    }

    public function store()
    {
        if (!$this->session->get('logged_in')) return redirect()->to('/auth');

        $rules = [
            'order_id' => 'required|numeric',
            'reason' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $userId = $this->session->get('user_id');
        $orderId = $this->request->getPost('order_id');

        // Ensure order belongs to user
        $order = $this->orderModel->where('id', $orderId)->where('user_id', $userId)->first();
        if (!$order) return redirect()->back()->with('error', 'Pesanan tidak ditemukan atau bukan milik Anda.');

        // Prevent duplicate pending returns for the same order
        $existing = $this->returnModel->where('order_id', $orderId)->whereIn('status', ['requested', 'approved'])->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Sudah ada permintaan retur untuk pesanan ini.');
        }

        $this->returnModel->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'reason' => $this->request->getPost('reason'),
            'status' => 'requested'
        ]);

        // Note: do not change the order status here to avoid enum mismatch; admin will update status on approval

        return redirect()->to('/returns')->with('success', 'Permintaan retur berhasil dikirim.');
    }
}
