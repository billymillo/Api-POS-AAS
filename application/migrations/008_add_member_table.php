<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_member_table extends CI_Migration {

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
					'type' => 'VARCHAR',
					'constraint' => "15",	
				),
				'saldo' => array(
					'type' => 'int',
					'constraint' => '20',
				),
				'poin' => array(
					'type' => 'int',
					'constraint' => '20',
				),
				'input_date' => array(
					'type' => 'DATETIME',
					'null' => TRUE,
				),
				'update_tipe' => array(
					'type' => 'DATETIME',
					'null' => TRUE,
				),
				'user_input' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'null' => TRUE,
				),
				'user_update' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'null' => TRUE,
				),
        ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('mst_member');
        }

        public function down()
        {
                $this->dbforge->drop_table('mst_member');
        }
}
