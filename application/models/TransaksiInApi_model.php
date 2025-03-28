<?php

class TransaksiInApi_model extends CI_Model {
	public function getTransaksiIn($id = NULL) {
		if ($id === null) {
			$query = $this->db->get('mst_transaksi_in')->result_array();
		} else {
			$query = $this->db->get_where('mst_transaksi_in', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addTransaksiIn($data) {
		$this->db->insert('mst_transaksi_in', $data);
		return $this->db->affected_rows();
	}
	
	public function getNoTransaksiIn() {
        $this->db->select('no_transaksi_in');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('mst_transaksi_in');
        return $query->row_array();
    }

	public function getLastTransaksiInId() {
		$this->db->select('id');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('mst_transaksi_in');
		return $query->row_array();
	}

	public function getDetail($id = NULL) {
		if ($id === null) {
			$query = $this->db->get('mst_det_transaksi_in')->result_array();
		} else {
			$query = $this->db->get_where('mst_det_transaksi_in', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addDetail($data) {
		$this->db->insert('mst_det_transaksi_in', $data);
		return $this->db->affected_rows();
	}

	public function getPembayaran($id = NULL) {
		$this->db->where('presence', 1);
		if ($id === null) {
			$query = $this->db->get('lib_metode_pembayaran')->result_array();
		} else {
			$query = $this->db->get_where('lib_metode_pembayaran', ['id' => $id])->result_array();
		}
		return $query;
	}

	public function addPembayaran($data) {
		$this->db->insert('lib_metode_pembayaran', $data);
		return $this->db->affected_rows();
	}

	public function editPembayaran($data, $id) {
		$this->db->update('lib_metode_pembayaran', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function deletePembayaran($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('lib_metode_pembayaran', $data);
		return $this->db->affected_rows();
	}

}
