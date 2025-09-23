<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\KeranjangModel;

class Cart extends BaseController
{
    protected $produkModel;
    protected $keranjangModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->keranjangModel = new KeranjangModel();
    }

    public function add()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Silakan login terlebih dahulu untuk menambahkan produk ke keranjang'
            ]);
        }

        // Get product ID and quantity from request
        $product_id = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity');

        // Validate product exists
        $product = $this->produkModel->find($product_id);
        if (!$product) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan'
            ]);
        }

        // Get user ID from session
        $user_id = session()->get('user_id');

        // Check if product already in cart
        $existing_cart = $this->keranjangModel->where([
            'user_id' => $user_id,
            'produk_id' => $product_id
        ])->first();

        if ($existing_cart) {
            // Update quantity if product exists
            $new_quantity = $existing_cart['quantity'] + $quantity;
            $this->keranjangModel->update($existing_cart['id'], [
                'quantity' => $new_quantity,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Add new item to cart
            $this->keranjangModel->insert([
                'user_id' => $user_id,
                'produk_id' => $product_id,
                'quantity' => $quantity,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Redirect to cart page
        return redirect()->to('/cart');
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth')->with('error', 'Silakan login terlebih dahulu untuk melihat keranjang');
        }

        $user_id = session()->get('user_id');
        $cart_items = $this->keranjangModel->getCartWithProducts($user_id);

        // Calculate totals
        $subtotal = 0;
        foreach ($cart_items as $item) {
            $subtotal += $item['harga'] * $item['quantity'];
        }
        $shipping = 10000; // Fixed shipping cost
        $total = $subtotal + $shipping;

        return view('cart/index', [
            'cart_items' => $cart_items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total
        ]);
    }

    public function update()
    {
        try {
            // Check if user is logged in
            if (!session()->get('logged_in')) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Silakan login terlebih dahulu'
                ]);
            }

            // Get items from POST data
            $items_json = $this->request->getPost('items');
            if (!$items_json) {
                log_message('error', 'Cart update: No items data received');
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            // Log received data
            log_message('debug', 'Cart update received data: ' . $items_json);

            $items = json_decode($items_json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', 'Cart update: JSON decode error - ' . json_last_error_msg());
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Format data tidak valid: ' . json_last_error_msg()
                ]);
            }

            if (!$items || !is_array($items)) {
                log_message('error', 'Cart update: Invalid items format');
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Format data tidak valid'
                ]);
            }

            $user_id = session()->get('user_id');
            $updated = false;

            foreach ($items as $item) {
                if (!isset($item['id']) || !isset($item['quantity'])) {
                    log_message('error', 'Cart update: Missing required fields in item');
                    continue;
                }

                // Verify cart item belongs to user and get current data
                $cart_item = $this->keranjangModel->where([
                    'id' => $item['id'],
                    'user_id' => $user_id
                ])->first();

                if ($cart_item && $cart_item['quantity'] != $item['quantity']) {
                    // Update quantity if it has changed
                    $updateData = [
                        'quantity' => $item['quantity'],
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $result = $this->keranjangModel->update($item['id'], $updateData);
                    if ($result) {
                        $updated = true;
                        log_message('debug', 'Cart update: Successfully updated item ID ' . $item['id']);
                    } else {
                        log_message('error', 'Cart update: Failed to update item ID ' . $item['id']);
                    }
                } else {
                    log_message('error', 'Cart update: Item not found or not owned by user - ID: ' . $item['id']);
                }
            }

            if (!$updated) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Tidak ada item yang diperbarui'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Keranjang berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Cart update error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    public function remove($cart_id)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Silakan login terlebih dahulu'
            ]);
        }

        // Verify cart item belongs to user
        $cart_item = $this->keranjangModel->where([
            'id' => $cart_id,
            'user_id' => session()->get('user_id')
        ])->first();

        if (!$cart_item) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Item tidak ditemukan'
            ]);
        }

        // Remove item
        $this->keranjangModel->delete($cart_id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Item berhasil dihapus dari keranjang'
        ]);
    }
} 