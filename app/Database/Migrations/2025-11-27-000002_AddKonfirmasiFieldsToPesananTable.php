<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKonfirmasiFieldsToPesananTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pesanan', [
            'konfirmasi_oleh' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'User ID pembeli yang mengonfirmasi penerimaan'
            ],
            'tanggal_konfirmasi' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Waktu ketika pembeli mengonfirmasi penerimaan pesanan'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pesanan', ['konfirmasi_oleh', 'tanggal_konfirmasi']);
    }
}
