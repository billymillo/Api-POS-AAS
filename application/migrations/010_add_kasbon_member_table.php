<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_kasbon_member_table extends CI_Migration {

        public function up()
        {		
    		$this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'id_member' => array(
					'type' => 'INT',
					'constraint' => '11',
					'unsigned' => TRUE,
				),
				'total_kasbon' => array(
					'type' => 'INT',
					'constraint' => '11',
				),
				'tgl_pelunasan' => array(
					'type' => 'DATETIME',
					'null' => FALSE,
					'default' => NULL,
				),
				'id_status' => array(
					'type' => 'INT',
					'constraint' => '11',
					'unsigned' => TRUE,
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
					'null' => TRUE,
				),
				'user_update' => array(
					'type' => 'VARCHAR',
					'null' => TRUE,
				),
				
        ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('mst_kasbon_member');
        }

        public function down()
        {
                $this->dbforge->drop_table('mst_kasbon_member');
        }
}
