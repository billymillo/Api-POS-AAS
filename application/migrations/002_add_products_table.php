<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_products_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'nama_barang' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'barcode_barang' => array(
                'type' => 'INT',
                'constraint' => '20',
            ),
			'gambar_barang' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'id_kategori_barang' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'id_tipe_barang' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'id_mitra' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'harga_pack' => array(
                'type' => 'INT',
                'constraint' => '20',
            ),
            'jml_pcs_pack' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'harga_satuan' => array(
                'type' => 'INT',
                'constraint' => '20',
            ),
            'harga_jual' => array(
                'type' => 'INT',
                'constraint' => '20',
            ),
            'stok' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'laba' => array(
                'type' => 'INT',
                'constraint' => '20',
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
                $this->dbforge->create_table('mst_produk');
				$this->db->query("
        		    ALTER TABLE mst_produk 
        		    MODIFY input_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        		    MODIFY updated_date DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        		");
        }

    public function down()
    {
        $this->dbforge->drop_table('mst_produk');
    }
}
