<?php

class ProductApi_model extends CI_Model {
	public function getProduct($id = null, $offset = null, $limit = null) {
		$this->db->where('mst_produk.presence', 1);
		$this->db->join('lib_kategori', 'mst_produk.id_kategori_barang = lib_kategori.id', 'left');
		$this->db->join('lib_tipe', 'mst_produk.id_tipe_barang = lib_tipe.id', 'left');
		$this->db->join('mst_mitra', 'mst_produk.id_mitra_barang = mst_mitra.id', 'left');
		$this->db->select(
			'mst_produk.*, lib_kategori.kategori as kategori_name, mst_mitra.nama as mitra_name, lib_tipe.tipe as tipe_name'
		);
		if ($id === null) {
			$this->db->limit($limit, $offset);
			$query = $this->db->get('mst_produk');
		} else {
			$this->db->where('mst_produk.id', $id);
			$query = $this->db->get('mst_produk');
		}
		return $query->result_array();
	}

	public function getProductDataById($id) {
		return $this->db->get_where('mst_produk', ['id' => $id])->row_array(); 
	}
	
	public function addProduct($data) {
		$this->db->insert('mst_produk', $data);
		return $this->db->affected_rows();
	}
	public function editProduct($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('mst_produk', $data);	
		return ($this->db->affected_rows() > 0 || $this->db->error()['code'] === 0);
	}

	public function deleteProduct($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('mst_produk', $data);
		return $this->db->affected_rows();
	}
	public function getTipe($id = NULL) {
		$this->db->where('presence', 1);
		if ($id === null) {
			$query = $this->db->get('lib_tipe')->result_array();
		} else {
			$query = $this->db->get_where('lib_tipe', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addTipe($data) {
		$this->db->insert('lib_tipe', $data);
		return $this->db->affected_rows();
	}

	public function editTipe($data, $id) {
		$this->db->update('lib_tipe', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function deleteTipe($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('lib_tipe', $data);
		return $this->db->affected_rows();
	}

	public function getMitra($id = NULL) {
		$this->db->where('presence', 1);

		if ($id === null) {
			$query = $this->db->get('mst_mitra')->result_array();
		} else {
			$query = $this->db->get_where('mst_mitra', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addMitra($data) {
		$this->db->insert('mst_mitra', $data);
		return $this->db->affected_rows();
	}

	public function editMitra($data, $id) {
		$this->db->update('mst_mitra', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function deleteMitra($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('mst_mitra', $data);
		return $this->db->affected_rows();
	}

	public function getKategori($id = NULL) {
		$this->db->where('presence', 1);
		if ($id === null) {
			$query = $this->db->get('lib_kategori')->result_array();
		} else {
			$query = $this->db->get_where('lib_kategori', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addKategori($data) {
		$this->db->insert('lib_kategori', $data);
		return $this->db->affected_rows();
	}
	public function getKategoriDataById($id) {
		return $this->db->get_where('lib_kategori', ['id' => $id])->row_array(); 
	}
	public function editKategori($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('lib_kategori', $data);
		return ($this->db->affected_rows() >= 0);
	}

	public function deleteKategori($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('lib_kategori', $data);
		return $this->db->affected_rows();
	}
}
