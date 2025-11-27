<?php

namespace App\Controllers;

class Bantuan extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Bantuan & FAQ - Mantra Jaya Tani',
        ];
        
        return $this->render('bantuan/index', $data);
    }
}