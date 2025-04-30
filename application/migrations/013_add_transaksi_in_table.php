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
                $this->dbforge->create_table('mst_transaksi_in');
				$this->db->query("
        		    ALTER TABLE mst_transaksi_in 
        		    MODIFY input_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        		    MODIFY updated_date DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        		");
        }

        public function down()
        {
                $this->dbforge->drop_table('mst_transaksi_in');
        }
}
