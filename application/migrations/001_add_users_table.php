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
                $this->dbforge->create_table('users');
        }

        public function down()
        {
                $this->dbforge->drop_table('users');
        }
}
