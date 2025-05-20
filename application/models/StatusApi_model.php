<?php

class StatusApi_model extends CI_Model {

	public function getStatus($id = NULL) {
		if ($id === null) {
			$query = $this->db->get('lib_status')->result_array();
		} else {
			$query = $this->db->get_where('lib_status', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function addStatus($data) {
		$this->db->insert('lib_status', $data);
		return $this->db->affected_rows();
	}

	public function editStatus($data, $id) {
		$this->db->update('lib_status', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function deleteStatus($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('lib_status', $data);
		return $this->db->affected_rows();
	}
}
