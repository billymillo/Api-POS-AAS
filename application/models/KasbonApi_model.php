<?php

class KasbonApi_model extends CI_Model {
	public function getKasbon($id = NULL) {
		$this->db->where('presence', 1);
		$this->db->order_by('mst_kasbon_member.id', 'DESC');
		if ($id === null) {
			$query = $this->db->get('mst_kasbon_member')->result_array();
		} else {
			$query = $this->db->get_where('mst_kasbon_member', ['id' => $id])->result_array();
		}
		return $query;
	}
	public function getKasbonDataById($id) {
		return $this->db->get_where('mst_kasbon_member', ['id' => $id])->row_array(); 
	}

	public function editKasbon($data, $id) {
		$this->db->update('mst_kasbon_member', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}

	public function deleteKasbon($data, $id) {
		$this->db->where('id', $id);
		$this->db->update('mst_kasbon_member', $data);
		return $this->db->affected_rows();
	}

	public function getLastKasbonId() {
		$this->db->select('id');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('mst_kasbon_member');
		return $query->row_array();
	}
	public function addKasbon($data) {
		$this->db->insert('mst_kasbon_member', $data);
		return $this->db->affected_rows();
	}

	public function getKasbonDetail($id = NULL, $produk = null) {
		if ($id === null) {
			if ($produk !== null) {
				$this->db->where('mst_det_kasbon_member.presence', 1);
				$this->db->join('mst_kasbon_member', 'mst_det_kasbon_member.id_kasbon = mst_kasbon_member.id', 'left');
				$this->db->join('mst_transaksi_out', 'mst_det_kasbon_member.id_transaksi_out = mst_transaksi_out.id', 'left');
				$this->db->select('mst_det_kasbon_member.id, mst_det_kasbon_member.id_kasbon, mst_det_kasbon_member.id_transaksi_out, mst_det_kasbon_member.presence');
				$this->db->select('mst_kasbon_member.id_member, mst_kasbon_member.total_kasbon, mst_kasbon_member.tgl_pelunasan, mst_kasbon_member.id_status');
				$this->db->select('mst_transaksi_out.no_transaksi_out, mst_transaksi_out.jumlah_produk, mst_transaksi_out.total_transaksi, mst_transaksi_out.id_metode_pembayaran');
			}
			$query = $this->db->get('mst_det_kasbon_member');
		} else {
			$this->db->where('mst_det_kasbon_member.id', $id);
			if ($produk !== null) {
				$this->db->where('mst_det_kasbon_member.presence', 1);
				$this->db->join('mst_kasbon_member', 'mst_det_kasbon_member.id_kasbon = mst_kasbon_member.id', 'left');
				$this->db->join('mst_transaksi_out', 'mst_det_kasbon_member.id_transaksi_out = mst_transaksi_out.id', 'left');
				$this->db->select('mst_det_kasbon_member.id, mst_det_kasbon_member.id_kasbon, mst_det_kasbon_member.id_transaksi_out, mst_det_kasbon_member.presence');
				$this->db->select('mst_kasbon_member.id_member, mst_kasbon_member.total_kasbon, mst_kasbon_member.tgl_pelunasan, mst_kasbon_member.id_status');
				$this->db->select('mst_transaksi_out.no_transaksi_out, mst_transaksi_out.jumlah_produk, mst_transaksi_out.total_transaksi, mst_transaksi_out.id_metode_pembayaran');
			}
			$query = $this->db->get('mst_det_kasbon_member');
		}
	
		$kasbon = $query->result_array();
	
		foreach ($kasbon as &$trx) {
			if (!empty($trx['id_transaksi_out'])) {
				$this->db->select('*');
				$this->db->from('mst_det_transaksi_out');
				$this->db->where('id_transaksi_out', $trx['id_transaksi_out']);
				$detailQuery = $this->db->get();
	
				if ($detailQuery) {
					$trx['detail_transaksi'] = $detailQuery->result_array();
				} else {
					$trx['detail_transaksi'] = [];
				}
			} else {
				$trx['detail_transaksi'] = [];
			}
		}
	
		return $kasbon;
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
