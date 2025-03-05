<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_det_kasbon_member_table extends CI_Migration {

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
				'id_transaksi_out' => array(
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
                $this->dbforge->create_table('mst_det_kasbon_member');
        }

        public function down()
        {
                $this->dbforge->drop_table('mst_det_kasbon_member');
        }
}
