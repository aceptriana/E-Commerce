<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTanggalUpdateToPesananTable extends Migration
{
    public function up()
    {
        // Add tanggal_update column if it does not already exist.
        $db = \Config\Database::connect();
        if (!$db->fieldExists('tanggal_update', 'pesanan')) {
            $fields = [
                'tanggal_update' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'no_resi'
                ],
            ];
            $this->forge->addColumn('pesanan', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('pesanan', 'tanggal_update');
    }
}
