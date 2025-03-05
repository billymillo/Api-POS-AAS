<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_pem_kasbon_member_table extends CI_Migration {

        public function up()
        {		
    		$this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),
				'id_kasbon' => array(
					'type' => 'INT',
					'constraint' => '11',
					'unsigned' => TRUE,
				),
				'tgl_bayar' => array(
					'type' => 'DATETIME',
					'null' => FALSE,
					'default' => NULL,
				),
				'total_bayar' => array(
					'type' => 'INT',
					'constraint' => '11',
				),
				'id_stat_ver' => array(
					'type' => 'INT',
					'constraint' => '11',
					'unsigned' => TRUE,
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
                $this->dbforge->create_table('mst_pem_kasbon_member');
        }

        public function down()
        {
                $this->dbforge->drop_table('mst_pem_kasbon_member');
        }
}
