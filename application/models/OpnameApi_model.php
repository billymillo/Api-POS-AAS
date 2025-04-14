<?php

class OpnameApi_model extends CI_Model {
	public function getOpname($id = null) {
		$this->db->where('presence', 1);
		if ($id === null) {
			$query = $this->db->get('mst_opname')->result_array();
		} else {
			$query = $this->db->get_where('mst_opname', ['id' => $id])->result_array();
		}
		return $query;
	}

	public function getNoOpname() {
        $this->db->select('no_opname');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('mst_opname');
        return $query->row_array();
    }

	public function addOpname($data) {
		$this->db->insert('mst_opname', $data);
		return $this->db->affected_rows();
	}

	public function editOpname($data, $id) {
		$this->db->update('mst_opname', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function deleteOpname($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('mst_opname', $data);
		return $this->db->affected_rows();
	}

	public function getOpnameDet($id = null) {
		$this->db->where('presence', 1);
		if ($id === null) {
			$query = $this->db->get('mst_det_opname')->result_array();
		} else {
			$query = $this->db->get_where('mst_det_opname', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function getOpnameDataById($id) {
		return $this->db->get_where('mst_det_opname', ['id' => $id])->row_array(); 
	}
	public function addOpnameDet($data) {
		$this->db->insert('mst_det_opname', $data);
		return $this->db->affected_rows();
	}
	public function editOpnameDet($data, $id) {
		$this->db->update('mst_det_opname', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}
	public function getOpnamePem($id = null) {
		$this->db->where('presence', 1);
		if ($id === null) {
			$query = $this->db->get('mst_pembelian_opname')->result_array();
		} else {
			$query = $this->db->get_where('mst_pembelian_opname', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addOpnamePem($data) {
		$this->db->insert('mst_pembelian_opname', $data);
		return $this->db->affected_rows();
	}
}