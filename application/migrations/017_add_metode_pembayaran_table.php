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
				'update_date' => array(
					'type' => 'DATETIME',
					'null' => TRUE,
				),
        ));
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->create_table('lib_metode_pembayaran');
        }

        public function down()
        {
                $this->dbforge->drop_table('lib_metode_pembayaran');
        }
}
