<?php

class TransaksiOutApi_model extends CI_Model {
	public function getTransaksiOut($id = NULL, $offset = null, $limit = null) {
		$this->db->where('presence', 1);
		if ($id !== NULL) {
			$this->db->where('mst_transaksi_out.id', $id);
			$query = $this->db->get('mst_transaksi_out');
			return $query->row_array();
		} else {
			$this->db->limit($limit, $offset);
			$query = $this->db->get('mst_transaksi_out');
			return $query->result_array();
		}
		return $query;
	}

	public function addTransaksiOut($data) {
		$this->db->insert('mst_transaksi_out', $data);
		return $this->db->affected_rows();
	}
	
	public function getLastTransaksiOut() {
        $this->db->select('no_transaksi_out');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('mst_transaksi_out');
        return $query->row_array();
    }
	
	public function getLastTransaksiOutId() {
		$this->db->select('id');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('mst_transaksi_out');
		return $query->row_array();
	}
	public function getLatestTransaction() {
		$this->db->select('*');
    	$this->db->from('mst_transaksi_out');
    	$this->db->order_by('id', 'DESC'); // Ambil data terbaru berdasarkan ID
    	$this->db->limit(1); // Hanya ambil 1 data terakhir
    	$query = $this->db->get();

    	if ($query->num_rows() > 0) {
    	    return $query->row(); // Ambil data pertama
    	} else {
    	    return null;
    	}
	}
	
	public function getDetail($id = NULL) {
		if ($id === null) {
			$query = $this->db->get('mst_det_transaksi_out')->result_array();
		} else {
			$query = $this->db->get_where('mst_det_transaksi_out', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addDetail($data) {
		$this->db->insert('mst_det_transaksi_out', $data);
		return $this->db->affected_rows();
	}
	
	public function updateDetail($id, $data) {
    	$this->db->where('id', $id);
    	return $this->db->update('mst_det_transaksi_out', $data);
	}

	public function getDetailByProdukAndTransaksi($id_produk, $id_transaksi_out) {
		$this->db->where('id_produk', $id_produk);
		$this->db->where('id_transaksi_out', $id_transaksi_out);
		return $this->db->get('mst_det_transaksi_out')->row_array();
	}
	
}
