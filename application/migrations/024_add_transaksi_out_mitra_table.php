<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_transaksi_out_mitra_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
			'no_transaksi_out_mitra' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
            ),
            'id_mitra' => array(
                'type' => 'INT',
                'constraint' => 11,
				'unsigned' => TRUE,
            ),
            'total_jumlah' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'total_transaksi' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'status_transaksi' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'tanggal_awal' => array(
                'type' => 'DATETIME',
				'null' => FALSE,
            ),
            'tanggal_akhir' => array(
                'type' => 'DATETIME',
				'null' => FALSE,
            ),
            'input_date' => array(
				'type' => 'DATETIME',
				'null' => TRUE,
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
        $this->dbforge->create_table('mst_transaksi_out_mitra', TRUE);
		$this->db->query("
			ALTER TABLE mst_transaksi_out_mitra 
    		MODIFY input_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
		");
		$this->db->query("
    		ALTER TABLE mst_transaksi_out_mitra 
    		MODIFY updated_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
		");
    }


    public function down()
    {
        $this->dbforge->drop_table('mst_transaksi_out_mitra');
    }
}
