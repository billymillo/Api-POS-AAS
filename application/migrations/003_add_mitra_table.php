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
                $this->dbforge->create_table('mst_mitra');
        }

        public function down()
        {
                $this->dbforge->drop_table('mst_mitra');
        }
}
