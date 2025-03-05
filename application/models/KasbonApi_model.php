<?php

class KasbonApi_model extends CI_Model {
	public function getKasbon($id = NULL) {
		if ($id === null) {
			$query = $this->db->get('mst_kasbon_member')->result_array();
		} else {
			$query = $this->db->get_where('mst_kasbon_member', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addKasbon($data) {
		$this->db->insert('mst_kasbon_member', $data);
		return $this->db->affected_rows();
	}

	public function getKasbonDetail($id = NULL) {
		if ($id === null) {
			$query = $this->db->get('mst_det_kasbon_member')->result_array();
		} else {
			$query = $this->db->get_where('mst_det_kasbon_member', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addKasbonDetail($data) {
		$this->db->insert('mst_det_kasbon_member', $data);
		return $this->db->affected_rows();
	}

	public function getKasbonPemba($id = NULL) {
		if ($id === null) {
			$query = $this->db->get('mst_pem_kasbon_member')->result_array();
		} else {
			$query = $this->db->get_where('mst_pem_kasbon_member', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addKasbonPemba($data) {
		$this->db->insert('mst_pem_kasbon_member', $data);
		return $this->db->affected_rows();
	}
}
