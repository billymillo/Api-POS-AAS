<?php

class AddonApi_model extends CI_Model {
    public function getAddOn($id = NULL) {
		$this->db->where('presence', 1);
		if ($id === null) {
			$query = $this->db->get('lib_add_on')->result_array();
		} else {
			$query = $this->db->get_where('lib_add_on', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addAddOn($data) {
		$this->db->insert('lib_add_on', $data);
		return $this->db->affected_rows();
	}

	public function editAddOn($data, $id) {
		$this->db->update('lib_add_on', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function deleteAddOn($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('lib_add_on', $data);
		return $this->db->affected_rows();
	}

	public function getAddOnPr($id = NULL) {
		$this->db->where('presence', 1);
		if ($id === null) {
			$query = $this->db->get('mst_produk_add_on')->result_array();
		} else {
			$query = $this->db->get_where('mst_produk_add_on', ['id' => $id])->result_array();
		}
		return $query;
	}

	public function getLastProdukId() {
		$this->db->select('id');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('mst_produk');
		return $query->row_array();
	}

	public function addAddOnPr($data) {
		$this->db->insert('mst_produk_add_on', $data);
		return $this->db->affected_rows();
	}

	public function editAddOnPr($data, $id) {
		$this->db->update('mst_produk_add_on', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function deleteAddOnPr($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('mst_produk_add_on', $data);
		return $this->db->affected_rows();
	}

	public function getAddOnTr($id = NULL) {
		$this->db->where('presence', 1);
		if ($id === null) {
			$query = $this->db->get('mst_transaksi_add_on')->result_array();
		} else {
			$query = $this->db->get_where('mst_transaksi_add_on', ['id' => $id])->result_array();
		}
		return $query;
	}

	public function getLastTransaksiOutId() {
		$this->db->select('id');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('mst_transaksi_out');
		return $query->row_array();
	}

	public function addAddOnTr($data) {
		$this->db->insert('mst_transaksi_add_on', $data);
		return $this->db->affected_rows();
	}

	public function editAddOnTr($data, $id) {
		$this->db->update('mst_transaksi_add_on', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function deleteAddOnTr($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('mst_transaksi_add_on', $data);
		return $this->db->affected_rows();
	}
}