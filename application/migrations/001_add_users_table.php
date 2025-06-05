<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_users_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
        'id' => array(
            	'type' => 'INT',
            	'constraint' => 11,
            	'unsigned' => TRUE,
            	'auto_increment' => TRUE
        ),
        'name' => array(
            	'type' => 'VARCHAR',
            	'constraint' => '255',
        ),
		'role' => array(
			'type' => 'ENUM',
			'constraint' => "'kasir','admin'",	
		),
		'email' => array(
			'type' => 'VARCHAR',
			'constraint' => '255',
		),
		'password' => array(
        	        'type' => 'VARCHAR',
        		'constraint' => '255',
        	),
		'is_active' => array(
			'type' => 'INT',
			'constraint' => '1',
		),
		'input_date' => array(
			'type' => 'DATETIME',
			'null' => TRUE,
		),
		'updated_date' => array(
			'type' => 'DATETIME',
			'null' => TRUE,
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
            $this->dbforge->create_table('mst_users');
			$this->db->query("
        	    ALTER TABLE mst_users 
        	    MODIFY input_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        	    MODIFY updated_date DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        	");
    }
	
	public function down()
    {
        $this->dbforge->drop_table('mst_users');
    }
}
