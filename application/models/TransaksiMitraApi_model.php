<?php

class TransaksiMitraApi_model extends CI_Model {
	public function getTransaksiMitra($id = NULL) {
		if ($id === null) {
			$query = $this->db->get('mst_transaksi_in_mitra')->result_array();
		} else {
			$query = $this->db->get_where('mst_transaksi_in_mitra', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addTransaksiMitra($data) {
		$this->db->insert('mst_transaksi_in_mitra', $data);
		return $this->db->affected_rows();
	}

	public function getNoTransaksiInMitra() {
        $this->db->select('no_transaksi_in');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('mst_transaksi_in_mitra');
        return $query->row_array();
    }

	public function getLastTransaksiMitraId() {
		$this->db->select('id');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('mst_transaksi_in_mitra');
		return $query->row_array();
	}

	public function getDetail($id = NULL) {
		if ($id === null) {
			$query = $this->db->get('mst_det_transaksi_in_mitra')->result_array();
		} else {
			$query = $this->db->get_where('mst_det_transaksi_in_mitra', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addDetail($data) {
		$this->db->insert('mst_det_transaksi_in_mitra', $data);
		return $this->db->affected_rows();
	}
}
