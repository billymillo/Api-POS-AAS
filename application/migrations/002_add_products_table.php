<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_products_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'nama_barang' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
			'gambar_barang' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'id_kategori_barang' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'id_tipe_barang' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'id_mitra' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'harga_pack' => array(
                'type' => 'DECIMAL',
                'constraint' => '14,2',
            ),
            'jml_pcs_pack' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'harga_satuan' => array(
                'type' => 'DECIMAL',
                'constraint' => '14,2',
            ),
            'harga_jual' => array(
                'type' => 'DECIMAL',
                'constraint' => '14,2',
            ),
            'stok' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'laba' => array(
                'type' => 'DECIMAL',
                'constraint' => '14,2',
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('mst_produk');
    }

    public function down()
    {
        $this->dbforge->drop_table('mst_produk');
    }
}
