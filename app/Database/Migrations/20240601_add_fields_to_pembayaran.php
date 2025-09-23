<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToPembayaran extends Migration
{
    public function up()
    {
        $fields = [
            'metode_pembayaran' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'status' => [
                'type' => "ENUM('pending','berhasil','gagal')",
                'default' => 'pending',
                'null' => true,
            ],
            'total_bayar' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'waktu_bayar' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'transaction_id' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'payment_type' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'va_number' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'status_code' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'status_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'external_id' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('pembayaran', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('pembayaran', [
            'metode_pembayaran',
            'status',
            'total_bayar',
            'waktu_bayar',
            'transaction_id',
            'payment_type',
            'va_number',
            'status_code',
            'status_message',
            'external_id',
        ]);
    }
}
