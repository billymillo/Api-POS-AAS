<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_detail_transaksi_out_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'id_transaksi_out' => array(
                'type' => 'INT',
                'constraint' => 11,
				'unsigned' => TRUE,
            ),
			'id_produk' => array(
                'type' => 'INT',
                'constraint' => 11,
				'unsigned' => TRUE,
            ),
			'jumlah' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'harga_satuan' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'harga_jual' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'harga_add_on' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'total_harga_dasar' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'total_harga' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'laba' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'saldo' => array(
                'type' => 'INT',
                'constraint' => 11,
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
                $this->dbforge->create_table('mst_detail_transaksi_out');
				$this->db->query("
        		    ALTER TABLE mst_detail_transaksi_out 
        		    MODIFY input_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        		    MODIFY updated_date DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        		");
        }

    public function down()
    {
        $this->dbforge->drop_table('mst_detail_transaksi_out');
    }
}
