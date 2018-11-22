<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {
	public function add_queue_crop($data) {
		$data_insert = array(
			'id_user' => $data['id_user'],
			'source' => $data['source'],
			'filename' => $data['filename'],
			'crop_start' => $data['crop_start'],
			'crop_end' => $data['crop_end'],
			'ts_add' => strtotime('now'),
		);
		$this->db->insert('queue_crop', $data_insert);
		return $this->db->insert_id();
	}

	public function get_queue_crop() {
		$this->db->order_by('ts_add','asc');
		return $this->db->get('queue_crop')->result_array();
		// return $this->db->get_where('queue_crop', array('id_user' => $iduser))->result_array();
	}

	public function get_queue_crop_done() {
		$this->db->order_by('ts_end','asc');
		return $this->db->get('queue_crop')->result_array();
		// return $this->db->get_where('queue_crop', array('id_user' => $iduser))->result_array();
	}
}