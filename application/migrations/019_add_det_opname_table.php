<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_det_opname_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'id_produk' => array(
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => TRUE,
            ),
            'id_opname' => array(
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => TRUE,
            ),
            'stok' => array(
                'type' => 'INT',
                'constraint' => 20,
            ),
            'stok_asli' => array(
                'type' => 'INT',
                'constraint' => 20,
            ),
            'harga_satuan' => array(
                'type' => 'INT',
                'constraint' => 20,
            ),
            'harga_jual' => array(
                'type' => 'INT',
                'constraint' => 20,
            ),
            'catatan' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'input_date' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
            'updated_date' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
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
        $this->dbforge->create_table('mst_det_opname', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('mst_det_opname', TRUE);
    }
}
