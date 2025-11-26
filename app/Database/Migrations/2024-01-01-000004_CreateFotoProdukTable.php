<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFotoProdukTable extends Migration
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
            'produk_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'url_foto' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'urutan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('produk_id', 'produk', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('foto_produk');
    }

    public function down()
    {
        $this->forge->dropTable('foto_produk');
    }
}
