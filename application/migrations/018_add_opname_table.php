<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_opname_table extends CI_Migration {

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
                'null' => TRUE,
            ),
            'id_kategori_barang' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'id_tipe_barang' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'id_mitra_barang' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'harga_pack' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
            ),
            'jml_pcs_pack' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
            ),
            'harga_satuan' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
            ),
            'harga_jual' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
            ),
            'stok' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
            ),
            'stok_asli' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
            ),
            'laba' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
            ),
            'catatan' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'input_date' => array(
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ),
            'updated_date' => array(
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ),
            'presence' => array(
                'type' => 'INT',
                'constraint' => 12,
                'default' => 1,
            ),
            'user_input' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'user_update' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('mst_opname');
    }

    public function down()
    {
        $this->dbforge->drop_table('mst_opname');
    }
}
