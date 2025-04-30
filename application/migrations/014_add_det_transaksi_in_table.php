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
				'input_date' => array(
					'type' => 'DATETIME',
					'null' => TRUE,
				),
				'update_date' => array(
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
                $this->dbforge->create_table('mst_det_transaksi_in');
				$this->db->query("
        		    ALTER TABLE mst_det_transaksi_in 
        		    MODIFY input_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        		    MODIFY updated_date DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        		");
        }

        public function down()
        {
                $this->dbforge->drop_table('mst_det_transaksi_in');
        }
}
