<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_opname_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'tanggal' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
            'no_opname' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'status_opname' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'tipe_opname' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'input_date' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP',
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
        $this->dbforge->create_table('mst_opname', TRUE);
        $this->db->query("
            ALTER TABLE mst_opname 
            MODIFY input_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            MODIFY updated_date DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ");
    }

    public function down()
    {
        $this->dbforge->drop_table('mst_opname', TRUE);
    }
}
