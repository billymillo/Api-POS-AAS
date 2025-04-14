<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_transaksi_in_table extends CI_Migration {

        public function up()
        {		
    		$this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'no_transaksi_in' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
				),
				'jumlah_produk' => array(
					'type' => 'int',
					'constraint' => 11,
					'default' => NULL,
				),
				'total_transaksi' => array(
					'type' => 'INT',
					'constraint' => '11',
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
                $this->dbforge->create_table('mst_transaksi_in');
        }

        public function down()
        {
                $this->dbforge->drop_table('mst_transaksi_in');
        }
}
