<?php

class MemberApi_model extends CI_Model {
	public function getMember($id = NULL) {
		$this->db->where('presence', 1);

		if ($id === null) {
			$query = $this->db->get('mst_member')->result_array();
		} else {
			$query = $this->db->get_where('mst_member', ['id' => $id])->result_array();
		}
		return $query;
	}

	public function getMemberDataById($id) {
		return $this->db->get_where('mst_member', ['id' => $id])->row_array(); 
	}
	public function addMember($data) {
		$this->db->insert('mst_member', $data);
		return $this->db->affected_rows();
	}

	public function editMember($data, $id) {
		$this->db->update('mst_member', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function deleteMember($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('mst_member', $data);
		return $this->db->affected_rows();
	}

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
}
