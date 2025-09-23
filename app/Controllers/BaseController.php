<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;
    protected $kategoriModel;
    protected $viewData = [];
    protected $keranjangModel;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = \Config\Services::session();
        $this->kategoriModel = new \App\Models\KategoriModel();
        $this->keranjangModel = new \App\Models\KeranjangModel();

        // Load categories for all views
        $data['header_kategori'] = $this->kategoriModel->findAll();

        // Load cart data if user is logged in
        if ($this->session->get('logged_in')) {
            $userId = $this->session->get('user_id');
            $data['cart_count'] = $this->keranjangModel->where('user_id', $userId)->countAllResults();
            $data['cart_items'] = $this->keranjangModel->getCartItems($userId);
            $data['cart_total'] = $this->keranjangModel->getCartTotal($userId);
        } else {
            $data['cart_count'] = 0;
            $data['cart_items'] = [];
            $data['cart_total'] = 0;
        }

        $this->viewData = $data;
    }

    protected function render($view, $data = [])
    {
        // Merge view data with any additional data
        $data = array_merge($this->viewData, $data);
        return view($view, $data);
    }
}
