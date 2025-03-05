<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_status_library extends CI_Migration {

        public function up()
        {		
    		$this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'status' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
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
                $this->dbforge->create_table('lib_status');
        }

        public function down()
        {
                $this->dbforge->drop_table('lib_status');
        }
}
