<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_mitra_table extends CI_Migration {

        public function up()
        {		
    		$this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'nama' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
				),
				'no_tlp' => array(
					'type' => 'INT',
					'constraint' => "15",	
				),
				'email' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
				),
				'status' => array(
					'type' => 'INT',
					'constraint' => '1',
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
        	        $this->dbforge->create_table('mst_mitra');
					$this->db->query("
        			    ALTER TABLE mst_mitra 
        			    MODIFY input_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        			    MODIFY updated_date DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        			");
        	}

        public function down()
        {
                $this->dbforge->drop_table('mst_mitra');
        }
}
