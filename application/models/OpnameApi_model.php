<?php

class OpnameApi_model extends CI_Model {
	public function getOpname($id = null, $offset = null, $limit = null) {
		$this->db->where('mst_opname.id_tipe_barang', 1);
		$this->db->where('mst_opname.presence', 1);
		$this->db->join('lib_kategori', 'mst_opname.id_kategori_barang = lib_kategori.id', 'left');
		$this->db->join('lib_tipe', 'mst_opname.id_tipe_barang = lib_tipe.id', 'left');
		$this->db->join('mst_mitra', 'mst_opname.id_mitra_barang = mst_mitra.id', 'left');
		$this->db->select(
			'mst_opname.*, lib_kategori.kategori as kategori_name, mst_mitra.nama as mitra_name, lib_tipe.tipe as tipe_name'
		);
		if ($id === null) {
			$this->db->limit($limit, $offset); 
			$query = $this->db->get('mst_opname');
		} else {
			$this->db->where('mst_opname.id', $id);
			$query = $this->db->get('mst_opname');
		}
		return $query->result_array();
	}

	public function getOpnameDataById($id) {
		return $this->db->get_where('mst_opname', ['id' => $id])->row_array(); 
	}
	
	public function addOpname($data) {
		$this->db->insert('mst_opname', $data);
		return $this->db->affected_rows();
	}
	public function editOpname($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('mst_opname', $data);	
		return ($this->db->affected_rows() > 0 || $this->db->error()['code'] === 0);
	}

	public function deleteOpname($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('mst_opname', $data);
		return $this->db->affected_rows();
	}
}