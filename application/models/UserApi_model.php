<?php

class UserApi_model extends CI_Model {

	public function getUser($id = null) {
    if ($id === null) {
        $query = $this->db->get('mst_users')->result_array();
    } else {
        $query = $this->db->get_where('mst_users', ['id' => $id])->result_array();
    }
    return $query;
    }
	public function registerUser($data) {
		$this->db->insert('mst_users', $data);
		return $this->db->affected_rows();
	}

    public function loginUser($nama) {
        return $this->db->get_where('mst_users', ['name' => $nama])->row_array();
    }

    public function logoutUser($id) {
        return $this->db->get_where('mst_users', ['id' => $id])->row_array();
    }
    public function updateStatus($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update('mst_users', ['is_login' => $status]);
    }    
    
}
	