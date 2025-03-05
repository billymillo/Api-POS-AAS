<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_det_transaksi_in_table extends CI_Migration {

        public function up()
        {		
    		$this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'id_transaksi_in' => array(
					'type' => 'INT',
					'constraint' => '11',
					'unsigned' => TRUE,
				),
				'id_produk' => array(
					'type' => 'int',
					'constraint' => 11,
					'default' => NULL,
				),
				'jumlah' => array(
					'type' => 'INT',
					'constraint' => '11',
				),
				'harga_satuan' => array(
					'type' => 'INT',
					'constraint' => '11',
				),
				'total_harga' => array(
					'type' => 'INT',
					'constraint' => '11',
				),
				'tgl_expired' => array(
					'type' => 'DATETIME',
					'null' => FALSE,
					'default' => NULL,
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
                $this->dbforge->create_table('mst_det_transaksi_in');
        }

        public function down()
        {
                $this->dbforge->drop_table('mst_det_transaksi_in');
        }
}
