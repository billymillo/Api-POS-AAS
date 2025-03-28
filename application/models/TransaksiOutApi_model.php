<?php

class TransaksiOutApi_model extends CI_Model {
	public function getTransaksiOut($id = NULL, $offset = null, $limit = null) {
		$this->db->where('mst_transaksi_out.presence', 1);
		$this->db->from('mst_transaksi_out');
		
		$this->db->order_by('mst_transaksi_out.id', 'DESC');
	
		if ($id !== NULL) {
			$this->db->where('mst_transaksi_out.id', $id);
		}
	
		if ($limit !== null && $offset !== null) {
			$this->db->limit($limit, $offset);
		} 
	
		$query = $this->db->get();
		$transaksi = $query->result_array();
	
		foreach ($transaksi as &$trx) {
			$this->db->select('*');
			$this->db->from('mst_det_transaksi_out');
			$this->db->where('id_transaksi_out', $trx['id']);
			$trx['detail_transaksi'] = $this->db->get()->result_array();
		}
	
		return $transaksi;
	}

	public function getTransaksiDataById($id) {
		return $this->db->get_where('mst_transaksi_out', ['id' => $id])->row_array(); 
	}

	public function getNoTransaksiOut() {
        $this->db->select('no_transaksi_out');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('mst_transaksi_out');
        return $query->row_array();
    }
	
	public function getLastTransaksiOutId() {
		$this->db->select('id');
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('mst_transaksi_out');
		return $query->row_array();
	}
	public function getLatestTransaction() {
		$this->db->select('*');
    	$this->db->from('mst_transaksi_out');
    	$this->db->order_by('id', 'DESC');
    	$this->db->limit(1);
    	$query = $this->db->get();

    	if ($query->num_rows() > 0) {
    	    return $query->row();
    	} else {
    	    return null;
    	}
	}

	public function addTransaksiOut($data) {
		$this->db->insert('mst_transaksi_out', $data);
		return $this->db->affected_rows();
	}

	public function editTransaksiOut($data, $id) {
		$this->db->update('mst_transaksi_out', $data, ['id' => $id]);
		return $this->db->affected_rows();
	}
	
	public function getDetail($id = NULL, $produk = null) {
		if ($id === null) {
			if ($produk !== null) {
				$this->db->where('mst_det_transaksi_out.presence', 1);
				$this->db->join('mst_produk', 'mst_det_transaksi_out.id_produk = mst_produk.id', 'inner');
				$this->db->select('mst_det_transaksi_out.id as id_transaksi_out');
				$this->db->select('mst_det_transaksi_out.presence as presence');
				$this->db->select('mst_produk.*');
			}
			$query = $this->db->get('mst_det_transaksi_out');
		} else {
			$this->db->where('mst_det_transaksi_out.id', $id);
			if ($produk !== null) {
				$this->db->where('mst_det_transaksi_out.presence', 1);
				$this->db->join('mst_produk', 'mst_det_transaksi_out.id_produk = mst_produk.id', 'inner');
				$this->db->select('mst_det_transaksi_out.id as id_transaksi_out');
				$this->db->select('mst_det_transaksi_out.presence as presence');
				$this->db->select('mst_produk.*');
			}
			$query = $this->db->get('mst_det_transaksi_out');
		}
	
		if ($query && $query->num_rows() > 0) {
			return $query->result_array();
		}
	}
	
	
	
	public function addDetail($data) {
		$this->db->insert('mst_det_transaksi_out', $data);
		return $this->db->affected_rows();
	}
	
	public function updateDetail($id, $data) {
    	$this->db->where('id', $id);
    	return $this->db->update('mst_det_transaksi_out', $data);
	}

	public function getDetailByProdukAndTransaksi($id_produk, $id_transaksi_out) {
		$this->db->where('id_produk', $id_produk);
		$this->db->where('id_transaksi_out', $id_transaksi_out);
		return $this->db->get('mst_det_transaksi_out')->row_array();
	}
	
}
