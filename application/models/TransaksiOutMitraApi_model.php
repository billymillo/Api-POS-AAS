<?php

class TransaksiOutMitraApi_model extends CI_Model {
	public function getTransaksiOutMitra($id = NULL) {
		if ($id === null) {
			$query = $this->db->get('mst_transaksi_out_mitra')->result_array();
		} else {
			$query = $this->db->get_where('mst_transaksi_out_mitra', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addTransaksiOutMitra($data) {
		$this->db->insert('mst_transaksi_out_mitra', $data);
		return $this->db->affected_rows();
	}

	public function getNoTransaksiOutMitra() {
        $this->db->select('no_transaksi_out_mitra' ?? '1');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('mst_transaksi_out_mitra');
        return $query->row_array();
    }

	public function getLastTransaksiOutMitraId() {
		$this->db->select('id');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('mst_transaksi_out_mitra');
		return $query->row_array();
	}
}
