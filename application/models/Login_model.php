<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
	public function validate_login($datalogin) {
		$username = $datalogin['username'];
		$password = $datalogin['password'];
		$rememberme = $datalogin['rememberme'];

		$validate = $this->db->get_where('user', array(
			'username' => $username,
			'password' => $password
		), 1);
		if ($validate->num_rows() == 1) {
			return true;
		}
		else {
			return false;
		}
	}
}