<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_add_on_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'add_on' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
            'harga' => array(
                'type' => 'INT',
                'constraint' => 20,
            ),
            'input_date' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
            'updated_date' => array(
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
        $this->dbforge->create_table('lib_add_on', TRUE);
        $this->db->query("
            ALTER TABLE lib_add_on 
            MODIFY input_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            MODIFY updated_date DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ");
    }

    public function down()
    {
        $this->dbforge->drop_table('lib_add_on', TRUE);
    }
}
