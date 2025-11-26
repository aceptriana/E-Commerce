<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengirimanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pesanan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jasa_pengiriman' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'biaya_pengiriman' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'estimasi_pengiriman' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'status_pengiriman' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'diproses', 'dikirim', 'selesai'],
                'default'    => 'pending',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pesanan_id', 'pesanan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengiriman');
    }

    public function down()
    {
        $this->forge->dropTable('pengiriman');
    }
}
