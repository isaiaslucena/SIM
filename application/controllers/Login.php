<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function index($message = '') {
		$this->load->view('head');
		if ($message == 'fail'){
			$data['message'] = get_phrase('user_password_incorrect');
		}
		else if ($message == 'signout'){
			$data['message'] = get_phrase('see_you');
		}
		else if (!empty($message)){
			redirect('login/index','refresh');
			$data['message'] = null;
		}
		else {
			$data['message'] = null;
		}
		$this->load->view('login',$data);
	}

	public function mobile_index($message = '') {
		$this->load->view('head');
		if ($message == 'fail'){
			$data['message'] = get_phrase('user_password_incorrect');
		}
		else if ($message == 'signout'){
			$data['message'] = get_phrase('see_you');
		}
		else if (!empty($message)){
			redirect('login/index','refresh');
			$data['message'] = null;
		}
		else {
			$data['message'] = null;
		}
		$this->load->view('mobile_login',$data);
	}

	public function signin() {
		$this->load->model('login_model');
		$datalogin['username'] = $this->input->post('username');
		$datalogin['password'] = md5($this->input->post('password'));
		$datalogin['rememberme'] = $this->input->post('rememberme');
		$login = $this->login_model->validate_login($datalogin);
		if ($login) {
			$datalogin['id_group'] = $this->db->get_where('user', array('username' => $datalogin['username'] , 'password' => $datalogin['password']))->row()->id_group;
			if ($datalogin['id_group'] == 3) {
				redirect('login/index/fail','refresh');
				exit();
			} else {
				$datalogin['id_user'] = $this->db->get_where('user', array('username' => $datalogin['username'] , 'password' => $datalogin['password']))->row()->id_user;
				$sessiondata = array(
					'id_user' => $datalogin['id_user'],
					'username' => $datalogin['username'],
					'logged_in' => TRUE
				);
				$this->session->set_userdata($sessiondata);

				redirect(base_url(),'refresh');
			}
		}
		else {
			redirect('login/index/fail','refresh');
		}
	}

	public function signout() {
		$this->load->model('login_model');
		$sessiondata = array(
			'id_user',
			'username',
			'logged_in',
			'last_page'
		);
		$this->session->unset_userdata($sessiondata);
		$this->session->sess_destroy();
		redirect('login/index/signout','refresh');
	}
}
?>