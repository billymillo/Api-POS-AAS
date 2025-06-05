<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_metode_pembayaran_table extends CI_Migration {

        public function up()
        {		
    		$this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'metode' => array(
					'type' => 'INT',
					'constraint' => '11',
					'unsigned' => TRUE,
				),
				'input_date' => array(
					'type' => 'DATETIME',
					'null' => TRUE,
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
                $this->dbforge->create_table('lib_metode_pembayaran');
				$this->db->query("
        		    ALTER TABLE lib_metode_pembayaran 
        		    MODIFY input_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        		    MODIFY updated_date DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        		");
        }

        public function down()
        {
                $this->dbforge->drop_table('lib_metode_pembayaran');
        }
}
