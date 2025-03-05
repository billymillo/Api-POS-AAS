<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_detail_transaksi_out_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'id_transaksi_out' => array(
                'type' => 'INT',
                'constraint' => 11,
				'unsigned' => TRUE,
            ),
			'id_produk' => array(
                'type' => 'INT',
                'constraint' => 11,
				'unsigned' => TRUE,
            ),
			'jumlah' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'harga_satuan' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'harga_jual' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'total_harga' => array(
                'type' => 'INT',
                'constraint' => 11,
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
        $this->dbforge->create_table('mst_detail_transaksi_out');
    }

    public function down()
    {
        $this->dbforge->drop_table('mst_detail_transaksi_out');
    }
}
