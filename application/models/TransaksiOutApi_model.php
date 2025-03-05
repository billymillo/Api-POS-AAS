<?php

class TransaksiOutApi_model extends CI_Model {
	public function getTransaksiOut($id = NULL) {
		if ($id === null) {
			$query = $this->db->get('mst_transaksi_out')->result_array();
		} else {
			$query = $this->db->get_where('mst_transaksi_out', ['id' => $id])->result_array();
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
        $query = $this->db->get('mst_transaksi_out', 1);
        return $query->row_array();
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
}
