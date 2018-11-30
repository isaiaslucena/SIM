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
			'ts_add' => strtotime('now')
		);
		$this->db->insert('queue_crop', $data_insert);
		return $this->db->insert_id();
	}

	public function update_queue_crop($field, $value, $id_queue) {
		// $data_update = array(
		// 	'ts_start' => $name,
		// 	'title' => $title,
		// 	'status' => $status
		// );
		// $this->db->set($data_update);

		$this->db->set($field, $value);
		$this->db->where('id', $id_queue);
		$this->db->update('queue_crop');
	}

	public function get_queue_crop() {
		$this->db->order_by('ts_add','asc');
		return $this->db->get('queue_crop')->result_array();
		// return $this->db->get_where('queue_crop', array('id_user' => $iduser))->result_array();
	}

	public function get_queue_crop_todo() {
		$this->db->order_by('ts_add','asc');
		// return $this->db->get('queue_crop')->result_array();
		return $this->db->get_where('queue_crop', array('ts_end' => null))->result_array();
	}

	public function get_queue_crop_done() {
		$this->db->order_by('ts_end','asc');
		return $this->db->get('queue_crop')->result_array();
		// return $this->db->get_where('queue_crop', array('id_user' => $iduser))->result_array();
	}

	public function add_queue_join($idsqcrop) {
		$data_insert = array(
			'id_user' => $data['id_user'],
			'source' => $data['source'],
			'ts_add' => strtotime('now')
		);
		$this->db->insert('queue_join', $data_insert);
		$id_queue_join = $this->db->insert_id();

		$data_arr = array();
		foreach ($idsqcrop as $idqcrop) {
			$data = array(
				'id_queue_join' => $id_queue_join,
				'id_queue_crop' => $idqcrop
			);
			array_push($data_arr, $data);
		}
		$this->db->insert('queue_join_files', $data_arr);
	}
}