<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {
	public function calendar_index($selecteddate = '') {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'calendar_index',
				'last_page' => base_url('pages/calendar_index')
			);
			$this->session->set_userdata($sessiondata);
			$data_navbar['selected_page'] = 'home';
			$data_navbar['selected_date'] = $selecteddate;
			$data['clients'] = $this->pages_model->clients(null,null,'radio');
			$data['keywords'] = $this->pages_model->keywords();
			
			if (empty($selecteddate)) {
				$data['startdate'] = strtotime('today 00:00:00');
				$data['enddate'] = strtotime('today 23:59:59');
			}
			else {
				$data['startdate'] = strtotime($selecteddate.' 00:00:00');
				$data['enddate'] = strtotime($selecteddate.' 23:59:59');
			}
			
			$this->load->view('head');
			$this->load->view('navbar',$data_navbar);
			$this->load->view('home',$data);
			$this->load->view('footer',$data_navbar);
		} else {
			redirect('login','refresh');
		}
	}

	public function calendar_index2($selecteddate = null, $limit = null, $offset = null) {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'calendar_index',
				'last_page' => base_url('pages/calendar_index')
			);
			$this->session->set_userdata($sessiondata);
			$data_navbar['selected_page'] = 'home';
			$data_navbar['selected_date'] = $selecteddate;
			$clientsc = count($this->pages_model->clients(null,null,'radio'));
			$data['clientsmp'] = ($clientsc / 5);
			
			if (!is_null($limit)) {
				$data['clients'] = $this->pages_model->clients($limit, $offset,'radio');
			} else {
				$data['clients'] = $this->pages_model->clients(null,null,'radio');
			}
			
			$data['keywords'] = $this->pages_model->keywords();
			
			if (is_null($selecteddate)) {
			// if ($selecteddate == 'today') {
				$data['selected_date'] = $selecteddate;
				$data['startdate'] = strtotime('today 00:00:00');
				$data['enddate'] = strtotime('today 23:59:59');
			}
			else {
				$data['selected_date'] = $selecteddate;
				$data['startdate'] = strtotime($selecteddate.' 00:00:00');
				$data['enddate'] = strtotime($selecteddate.' 23:59:59');
			}	
			
			if ($limit == 0) {
				$this->load->view('navbar',$data_navbar);
				$this->load->view('home_dev',$data);
			} else {
				$this->load->view('home_load',$data);
			}
		} else {
			redirect('login','refresh');
		}
	}

	public function calendar($selecteddate = '') {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'calendar',
				'last_page' => base_url('pages/calendar')
			);
			$this->session->set_userdata($sessiondata);
			$data['page'] = 'pages/calendar_index/'.$selecteddate;
			$clientsc = count($this->pages_model->clients(null,null,'radio'));
			$data['clientsc'] = $clientsc;
			$this->load->view('head');
			$this->load->view('loading',$data);
		} else {
			redirect('login','refresh');
		}
	}

	public function home($selecteddate = null, $limit = null, $offset = null) {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'home',
				'last_page' => base_url('pages/home')
			);
			$this->session->set_userdata($sessiondata);
			$data_navbar['selected_page'] = 'home';
			$data_navbar['selected_date'] = $selecteddate;
			$clientsc = count($this->pages_model->clients(null, null, 'radio'));
			$data['clientsmp'] = ($clientsc / 5);
			
			if (!is_null($limit)) {
				$data['clients'] = $this->pages_model->clients($limit, $offset, 'radio');
			} else {
				$data['clients'] = $this->pages_model->clients(null, null, 'radio');
			}
			
			$data['keywords'] = $this->pages_model->keywords();
			
			// if (is_null($selecteddate)) {
			if ($selecteddate == 'today') {
				$data['selected_date'] = $selecteddate;
				$data['startdate'] = strtotime('today 00:00:00');
				$data['enddate'] = strtotime('today 23:59:59');
			}
			else {
				$data['selected_date'] = $selecteddate;
				$data['startdate'] = strtotime($selecteddate.' 00:00:00');
				$data['enddate'] = strtotime($selecteddate.' 23:59:59');
			}	
			
			if ($limit == 0) {
				$this->load->view('navbar',$data_navbar);
				$this->load->view('home_dev',$data);
			} else {
				$this->load->view('home_load',$data);
			}
		} else {
			redirect('login','refresh');
		}
	}

	public function home_tv($selecteddate = null, $limit = null, $offset = null) {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'home',
				'last_page' => base_url('pages/home_tv')
			);
			$this->session->set_userdata($sessiondata);
			$data_navbar['selected_page'] = 'home_tv';
			$data_navbar['selected_date'] = $selecteddate;
			$clientsc = count($this->pages_model->clients(null, null, 'tv'));
			$data['clientsmp'] = ($clientsc / 5);
			
			if (!is_null($limit)) {
				$data['clients'] = $this->pages_model->clients($limit, $offset, 'tv');
			} else {
				$data['clients'] = $this->pages_model->clients();
			}
			
			$data['keywords'] = $this->pages_model->keywords();
			
			// if (is_null($selecteddate)) {
			if ($selecteddate == 'today') {
				$data['selected_date'] = $selecteddate;
				$data['startdate'] = strtotime('today 00:00:00');
				$data['enddate'] = strtotime('today 23:59:59');
			}
			else {
				$data['selected_date'] = $selecteddate;
				$data['startdate'] = strtotime($selecteddate.' 00:00:00');
				$data['enddate'] = strtotime($selecteddate.' 23:59:59');
			}	
			
			if ($limit == 0) {
				$this->load->view('navbar',$data_navbar);
				$this->load->view('home_tvdev',$data);
			} else {
				$this->load->view('home_tvload',$data);
			}
		} else {
			redirect('login','refresh');
		}
	}

	public function index() {
		if ($this->session->has_userdata('logged_in')) {
			$id_user = $this->session->userdata('id_user');
			$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
			if ($id_group == 1) {
				redirect('pages/dashboard','refresh');
			} else {
				$sessiondata = array(
					'view' => 'index',
					'last_page' => base_url('pages/index')
				);
				$this->session->set_userdata($sessiondata);
				$data['page'] = 'pages/home';
				$this->load->view('head');
				$this->load->view('loading',$data);
			}
		} else {
			redirect('login','refresh');
		}
	}

	public function index_admin() {
		if ($this->session->has_userdata('logged_in')) {
			$id_user = $this->session->userdata('id_user');
			$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
			$sessiondata = array(
				'view' => 'index_admin',
				'last_page' => base_url('pages/index_admin')
			);
			$this->session->set_userdata($sessiondata);
			$data['page'] = 'pages/home';
			$this->load->view('head');
			$this->load->view('loading',$data);
		} else {
			redirect('login','refresh');
		}
	}

	public function index_tv() {
		if ($this->session->has_userdata('logged_in')) {
			$id_user = $this->session->userdata('id_user');
			$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
			if ($id_group == 1) {
				redirect('pages/dashboard','refresh');
			} else {
				$sessiondata = array(
					'view' => 'index_tv',
					'last_page' => base_url('pages/index')
				);
				$this->session->set_userdata($sessiondata);
				$data['page'] = 'pages/home_tv';
				$this->load->view('head');
				$this->load->view('loading',$data);
			}
		} else {
			redirect('login','refresh');
		}
	}

	public function dashboard() {
		if ($this->session->has_userdata('logged_in')) {
			$id_user = $this->session->userdata('id_user');
			$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
			if ($id_group == 1) {
				$sessiondata = array(
					'view' => 'dashboard',
					'last_page' => base_url('pages/dashboard')
				);
				$data_navbar['selected_page'] = 'dashboard';
				$this->session->set_userdata($sessiondata);

				$data['connected_users'] = $this->pages_model->connected_users();
				$data['total_radios'] = $this->pages_model->rradios();

				$this->load->view('head');
				$this->load->view('navbar',$data_navbar);
				$this->load->view('dashboard',$data);
				$this->load->view('footer',$data_navbar);
			} else {
				redirect(base_url(),'refresh');
			}
		} else {
			redirect(base_url(),'refresh');
		}
	}

	public function home_keyword() {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'home_keyword',
				'last_page' => base_url('pages/home_keyword')
			);
			$this->session->set_userdata($sessiondata);
			$data_navbar['selected_page'] = 'home';
			$data_navbar['selected_date'] = $this->input->post('selecteddate');
			$data['id_keyword'] = $this->input->post('id_keyword');
			$data['id_client'] = $this->input->post('id_client');
			$data['ids_file_xml'] = $this->input->post('ids_file_xml');
			$data['keyword_selected'] = $this->db->get_where('keyword',array('id_keyword' => $data['id_keyword']))->row()->keyword;
			$data['client_selected'] = $this->db->get_where('client',array('id_client' => $data['id_client']))->row()->name;
			$data['keyword_texts'] = $this->pages_model->text_keyword_id($data['ids_file_xml']);
			$data['clients_keyword'] = $this->pages_model->clients_keyword($data['id_keyword']);
			$data['id_user'] = $this->session->userdata('id_user');
			$this->load->view('head');
			$this->load->view('navbar',$data_navbar);
			$this->load->view('home_keyword',$data);
			$this->load->view('footer');
		} else {
			redirect('login','refresh');
		}
	}

	public function tv_home_keyword() {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'tv_home_keyword',
				'last_page' => base_url('pages/tv_home_keyword')
			);
			$this->session->set_userdata($sessiondata);
			$data_navbar['selected_page'] = 'home_tv';
			$data_navbar['selected_date'] = $this->input->post('selecteddate');
			$data['id_keyword'] = $this->input->post('id_keyword');
			$data['id_client'] = $this->input->post('id_client');
			$data['keyword_selected'] = $this->db->get_where('keyword',array('id_keyword' => $data['id_keyword']))->row()->keyword;
			$data['client_selected'] = $this->db->get_where('client',array('id_client' => $data['id_client']))->row()->name;
			// $data['keyword_texts'] = $this->pages_model->text_keyword_id($data['ids_file_xml']);
			$data['startdate'] = $this->input->post('startdate');
			$data['enddate'] = $this->input->post('enddate');
			$data['keyword_texts'] = $this->pages_model->tv_text_keyword_solr($data['startdate'],$data['enddate'],$data['keyword_selected']);
			// print_r($data['keyword_texts']);
			$data['clients_keyword'] = $this->pages_model->clients_keyword($data['id_keyword']);
			$data['id_user'] = $this->session->userdata('id_user');
			$this->load->view('head');
			$this->load->view('navbar',$data_navbar);
			$this->load->view('tv_home_keyword',$data);
			$this->load->view('footer');
		} else {
			redirect('login','refresh');
		}
	}

	public function load_file() {
		$position = $this->input->post('position');
		$id_client = $this->input->post('idclient');
		$id_keyword = $this->input->post('idkeyword');
		$timestamp = $this->input->post('timestamp');
		$id_radio = $this->input->post('idradio');
		preg_match_all('!\d+!', $this->input->post('divid'), $divid);
		$file = $this->pages_model->load_file($position,$timestamp,$id_radio);

		if (!empty($file)) {
			if ($position == 'next') {
				$divcount = $divid[0][0] - 1;
			} else if ($position == 'previous') {
				$divcount = $divid[0][0] + 1;
			}

			$filedate = (int)date('d',$file[0]['timestamp']);
			$date = (int)date('d',$timestamp);
			if ($filedate < $date) {
				echo '<div class="alert alert-warning" role="alert">'."\r\n";
					echo '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>'."\r\n";
					echo '<span class="sr-only">Error:</span>'."\r\n";
					echo get_phrase('no_more_files').'!'."\r\n";
				echo '</div>'."\r\n";

				echo '<script type="text/javascript">'."\r\n";
						echo '$(".alert-info").alert();'."\r\n";
						echo 'window.setTimeout(function(){'."\r\n";
							echo '$(".alert-warning").alert(\'close\');'."\r\n";
						echo '}, 5000);'."\r\n";
				echo '</script>'."\r\n";
			} else if ($filedate > $date) {
				echo '<div class="alert alert-warning" role="alert">'."\r\n";
					echo '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>'."\r\n";
					echo '<span class="sr-only">Error:</span>'."\r\n";
					echo get_phrase('no_more_files').'!'."\r\n";
				echo '</div>'."\r\n";

				echo '<script type="text/javascript">'."\r\n";
						echo '$(".alert-info").alert();'."\r\n";
						echo 'window.setTimeout(function(){'."\r\n";
							echo '$(".alert-warning").alert(\'close\');'."\r\n";
						echo '}, 5000);'."\r\n";
				echo '</script>'."\r\n";
			} else {
				$keyword_selected = '';
				$mp3fileid = $file[0]['id_file'] - 1;
				$mp3pathorig = $this->db->get_where('file',array('id_file' => $mp3fileid))->row()->path;
				$id_radio = $this->db->get_where('file',array('id_file' => $mp3fileid))->row()->id_radio;
				$mp3pathnew = mb_substr($mp3pathorig, 28);
				$mp3filename = $this->db->get_where('file',array('id_file' => $mp3fileid))->row()->filename;

				echo '<div id="divload'.$divcount.'" class="panel panel-info">'."\r\n";
					echo '<div class="panel-heading text-center">'."\r\n";
						echo '<label class="pull-left" style="font-weight: normal"><input type="checkbox" id="cbload'.$divcount.'" onclick="checkbox_join('.$divcount.','.$file[0]['timestamp'].',\''.$file[0]['state'].' - '.$file[0]['radio'].' - '.date('d/m/Y - H:i:s',$file[0]['timestamp']).'\','.$id_radio.','.$id_client.','.$id_keyword.','.$file[0]['id_file'].','.$mp3fileid.','.$file[0]['id_text'].')"> '.get_phrase('join').'</label>'."\r\n";
						echo '<label><i class="fa fa-bullhorn"></i> '.$file[0]['state'].' - '.$file[0]['radio'].' - '.date('d/m/Y - H:i:s',$file[0]['timestamp']).'</label>'."\r\n";
						echo '<form id="form_edit" style="all: unset;" action="'.base_url('pages/edit_temp').'" target="_blank" method="POST">'."\r\n";
							echo '<input type="hidden" name="mp3pathfilename" value="'.base_url('assets/repository/'.$mp3pathnew.'/'.$mp3filename).'">'."\r\n";
							echo '<input type="hidden" name="xmlpathfilename" value="'.base_url($file[0]['path'].'/'.$file[0]['filename']).'">'."\r\n";
							echo '<input type="hidden" name="state" value="'.$file[0]['state'].'">'."\r\n";
							echo '<input type="hidden" name="radio" value="'.$file[0]['radio'].'">'."\r\n";
							echo '<input type="hidden" name="timestamp" value="'.$file[0]['timestamp'].'">'."\r\n";
							// echo '<input type="hidden" name="id_keyword" value="'.$id_keyword.'">'."\r\n";
							// echo '<input type="hidden" name="client_selected" value="'.$client_selected.'">'."\r\n";
							echo '<button type="submit" class="btn btn-primary btn-xs pull-right">'.get_phrase('edit').'</button>'."\r\n";
						echo '</form>';
						echo '<span class="pull-right">&nbsp;</span>'."\r\n";
						// echo '<button onclick="discard_text(\'divload'.$divcount.'\','.$file[0]['id_text'].','.$id_client.','.$id_keyword.','.$id_user.')" class="btn btn-danger btn-xs pull-right">'.get_phrase('discard').'</button>'."\r\n";
						echo '<button disabled onclick="discard_text()" class="btn btn-danger btn-xs pull-right">'.get_phrase('discard').'</button>'."\r\n";
						echo '<span class="pull-right">&nbsp;</span>'."\r\n";
						echo '<button onclick="return load_file(\'next\','.$id_client.','.$id_keyword.','.$file[0]['timestamp'].','.$file[0]['id_radio'].',\'divload'.$divcount.'\'); return false;" class="btn btn-warning btn-xs pull-right">'.get_phrase('next').'</button>'."\r\n";
						echo '<span class="pull-right">&nbsp;</span>'."\r\n";
						echo '<button onclick="return load_file(\'previous\','.$id_client.','.$id_keyword.','.$file[0]['timestamp'].','.$file[0]['id_radio'].',\'divload'.$divcount.'\'); return false;" class="btn btn-warning btn-xs pull-right">'.get_phrase('previous').'</button>'."\r\n";
					echo '</div>'."\r\n";
					echo '<p class="text-center"><audio id="audiotext" style="width: 100%;" src="'.base_url("assets/repository/".$mp3pathnew."/".$mp3filename).'" controls></audio></p>'."\r\n";
					echo '<div class="panel-body" id="pbodyload'.$divcount.'" style="height: 300px; overflow-y: auto">'."\r\n";
						$textkeywordbold = str_ireplace(' '.$keyword_selected.' ', ' <strong id="strload'.$divcount.'" style="color: white; background-color: red; font-size: 110%;">'.$keyword_selected.'</strong> ', $file[0]['text_content'])."\r\n";
						echo '<p class="text-justify">'.$textkeywordbold.'</p>'."\r\n";
					echo '</div>'."\r\n";
				echo '</div>'."\r\n";

				echo '<script type="text/javascript">'."\r\n";
				echo '$(\'#pbodyload'.$divcount.'\').css(\'overflowY\', \'hidden\');'."\r\n";
				echo '$(\'#pbodyload'.$divcount.'\').click(function() {'."\r\n";
					echo '$(this).css(\'overflowY\', \'auto\');'."\r\n";
				echo '})'."\r\n";
				echo '$(\'#pbodyload'.$divcount.'\').hover(function() {'."\r\n";
					echo '/*do nothing*/'."\r\n";
				echo '}, function() {'."\r\n";
					echo '$(\'#pbodyload'.$divcount.'\').css(\'overflowY\', \'hidden\');'."\r\n";
				echo '});'."\r\n";
				echo '</script>'."\r\n";
			}
		} else {
			echo '<div class="alert alert-warning" role="alert">'."\r\n";
				echo '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>'."\r\n";
				echo '<span class="sr-only">Error:</span>'."\r\n";
				echo get_phrase('no_more_files').'!'."\r\n";
			echo '</div>'."\r\n";

			echo '<script type="text/javascript">'."\r\n";
					echo '$(".alert-info").alert();'."\r\n";
					echo 'window.setTimeout(function(){'."\r\n";
						echo '$(".alert-warning").alert(\'close\');'."\r\n";
					echo '}, 5000);'."\r\n";
			echo '</script>'."\r\n";
		}
	}

	public function discard_keyword() {
		if ($this->session->has_userdata('logged_in')) {
			$data_discard['id_text'] = $this->input->post('idtext', TRUE);
			$data_discard['id_client'] = $this->input->post('idclient', TRUE);
			$data_discard['id_keyword'] = $this->input->post('idkeyword', TRUE);
			$data_discard['id_user'] = $this->input->post('iduser', TRUE);
			$this->pages_model->discard_text($data_discard);
		} else {
			redirect('login','refresh');
		}
	}

	public function client_vhtype() {
		if ($this->session->has_userdata('logged_in')) {
			$data_cvhtype['id_client'] = $this->input->post('id_client', TRUE);
			$data_cvhtype['vhtype'] = $this->input->post('vhtype', TRUE);
			$data_cvhtype['checked'] = $this->input->post('checked', TRUE);
			$this->pages_model->client_vhtype($data_cvhtype);
		} else {
			redirect('login','refresh');
		}
	}

	public function edit() {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'edit',
				'last_page' => base_url('pages/edit')
			);
			$this->session->set_userdata($sessiondata);

			$data_navbar['selected_page'] = 'home';
			$this->load->view('head');
			$this->load->view('navbar',$data_navbar);
			$data['mp3pathfilename'] = $this->input->post('mp3pathfilename');
			$data['xmlpathfilename'] = $this->input->post('xmlpathfilename');
			$data['id_keyword'] = $this->input->post('id_keyword');
			$data['keyword_selected'] = $this->db->get_where('keyword',array('id_keyword' => $data['id_keyword']))->row()->keyword;
			$data['state'] = $this->input->post('state');
			$data['radio'] = $this->input->post('radio');
			$data['timestamp'] = $this->input->post('timestamp');
			$this->load->view('edit',$data);
			$this->load->view('footer',$data_navbar);
		} else {
			redirect('login','refresh');
		}
	}

	public function edit_temp() {
		if ($this->session->has_userdata('logged_in')) {
			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$start = $time;

			$sessiondata = array(
				'view' => 'edit_temp',
				'last_page' => base_url('pages/edit_temp')
			);
			$this->session->set_userdata($sessiondata);

			$data_navbar['selected_page'] = 'home';
			$data['mp3pathfilename'] = $this->input->post('mp3pathfilename');
			$data['xmlpathfilename'] = $this->input->post('xmlpathfilename');
			$data['id_keyword'] = $this->input->post('id_keyword');
			$data['id_client'] = $this->input->post('id_client');
			$data['id_file'] = $this->input->post('id_file');
			$data['id_text'] = $this->input->post('id_text');
			$data['client_selected'] = $this->input->post('client_selected');
			if (empty($this->input->post('client_selected'))) {
				$data['keyword_selected'] = $this->input->post('keyword');
			} else {
				$data['keyword_selected'] = $this->db->get_where('keyword',array('id_keyword' => $data['id_keyword']))->row()->keyword;
			}
			$data['state'] = $this->input->post('state');
			$data['radio'] = $this->input->post('radio');
			$data['timestamp'] = $this->input->post('timestamp');

			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$finish = $time;
			$data['total_time'] = round(($finish - $start), 4);

			$this->load->view('edit_temp',$data);
		} else {
			redirect('login','refresh');
		}
	}

	public function join() {
		if ($this->session->has_userdata('logged_in')) {
			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$start = $time;

			$sessiondata = array(
				'view' => 'join',
				'last_page' => base_url('pages/join')
			);
			$this->session->set_userdata($sessiondata);
			$data_navbar['selected_page'] = 'join';

			$data['id_radio'] = $this->input->post('id_radio');
			if (!empty($data['id_radio'])) {
				$radioinfo = $this->db->get_where('radio',array('id_radio' => $data['id_radio']))->result_array();
				$data['radio'] = $radioinfo[0]['name'];
				$data['state'] = $radioinfo[0]['state'];
			} else {
				$data['state'] = $this->input->post('state');
				$data['radio'] = $this->input->post('radio');
			}

			$data['id_file'] = $this->input->post('id_file');
			$data['id_client'] = $this->input->post('id_client');
			$data['client_selected'] = $this->db->get_where('client',array('id_client' => $data['id_client']))->row()->name;
			$data['id_keyword'] = $this->input->post('id_keyword');
			if (empty($data['id_keyword'])) {
				$data['keyword_selected'] = $this->input->post('keyword');
			} else {
				$data['keyword_selected'] = $this->db->get_where('keyword',array('id_keyword' => $data['id_keyword']))->row()->keyword;
			}

			$idsfilesmp3 = $this->input->post('ff_ids_files_mp3');
			$data['mp3pathfilename'] = $this->pages_model->join_mp3files($idsfilesmp3);

			$idsfilesxmlv = $this->input->post('ff_ids_files_xml');
			if (strpos($idsfilesxmlv, ',') !== FALSE) {
				$idsfilesxmlarr = array();
				$idsfilesxmlarr = explode(',', $idsfilesxmlv);
			} else {
				$idsfilesxml = $this->input->post('ff_ids_files_xml');
			}

			if (isset($idsfilesxmlarr)) {
				$data['xmlpathfilename'] = array();
				foreach ($idsfilesxmlarr as $idxml) {
					$filedb = $this->db->get_where('file',array('id_file' => $idxml))->result_array();
					$file = $filedb[0]['path'].'/'.$filedb[0]['filename'];
					array_push($data['xmlpathfilename'], $file);
				}
			} else {
				$data['xmlpathfilename'] = $this->input->post('xmlpathfilename');
			}

			$data['timestamp'] = $this->input->post('timestamp');

			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$finish = $time;
			$data['total_time'] = round(($finish - $start), 4);

			$this->load->view('edit_temp',$data);
		} else {
			redirect('login','refresh');
		}
	}

	public function join_edit_temp() {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'join_edit_temp',
				'last_page' => base_url('pages/join_edit_temp')
			);
			$this->session->set_userdata($sessiondata);
			$data['ff_ids_files_mp3'] = $this->input->post('ff_ids_files_mp3');
			$data['ff_ids_files_xml'] = $this->input->post('ff_ids_files_xml');
			$data['ff_id_keyword'] = $this->input->post('ff_keyword');
			$data['ff_id_client'] = $this->input->post('ff_id_client');
			$data['ff_id_radio'] = $this->input->post('ff_id_radio');
			$radioinfo = $this->db->get_where('radio',array('id_radio' => $data['ff_id_radio']))->result_array();
			$data['ff_timestamp'] = $this->input->post('ff_timestamp');
			$data['client_selected'] = $this->db->get_where('client',array('id_client' => $data['ff_id_client']))->row()->name;
			$data['keyword_selected'] = $this->db->get_where('keyword',array('id_keyword' => $data['ff_id_keyword']))->row()->keyword;
			$data['radio'] = $radioinfo[0]['name'];
			$data['state'] = $radioinfo[0]['state'];
			$data['page'] = 'pages/join';
			// $this->load->view('head');
			$this->load->view('loading',$data);
		} else {
			redirect('login','refresh');
		}
	}

	public function crop() {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'crop',
				'last_page' => base_url('pages/crop')
			);
			$this->session->set_userdata($sessiondata);

			$data_navbar['selected_page'] = 'home';
			$this->load->view('head');
			$this->load->view('navbar',$data_navbar);
			$data['starttime'] = $this->input->post('starttime');
			$data['endtime'] = $this->input->post('endtime');
			$data['keyword_selected'] = $this->input->post('keyword_selected');
			$data['state'] = $this->input->post('state');
			$data['radio'] = $this->input->post('radio');
			$data['timestamp'] = $this->input->post('timestamp');
			$data['text_crop'] = $this->input->post('result');
			$data['mp3pathfilename'] = $this->input->post('mp3pathfilename');
			$this->pages_model->crop($data['starttime'],$data['endtime'],$data['mp3pathfilename']);
			$this->load->view('crop',$data);
			$this->load->view('footer',$data_navbar);
		} else {
			redirect('login','refresh');
		}
	}

	public function crop_temp() {
		if ($this->session->has_userdata('logged_in')) {
			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$start = $time;

			$sessiondata = array(
				'view' => 'crop_temp',
				'last_page' => base_url('pages/crop_temp')
			);
			$this->session->set_userdata($sessiondata);

			$data['starttime'] = $this->input->post('starttime');
			$data['endtime'] = $this->input->post('endtime');
			$data['client_selected'] = $this->input->post('client_selected');
			$data['keyword_selected'] = $this->input->post('keyword_selected');
			$data['state'] = $this->input->post('state');
			$data['radio'] = $this->input->post('radio');
			$data['timestamp'] = $this->input->post('timestamp');
			$data['text_crop'] = $this->input->post('result');
			$data['mp3pathfilename'] = $this->input->post('mp3pathfilename');
			$data_crop_info['id_file'] = $this->input->post('id_file');
			$data_crop_info['id_user'] = $this->session->userdata('id_user');
			$data_crop_info['id_client'] = $this->input->post('id_client');
			$data_crop_info['id_keyword'] = $this->input->post('id_keyword');
			$data_crop_info['id_text'] = $this->input->post('id_text');
			$data_crop_info['text_cropped'] = $this->input->post('result');
			$data['finalfile'] = $this->pages_model->crop($data['starttime'],$data['endtime'],$data['mp3pathfilename']);
			$this->pages_model->crop_info($data_crop_info);

			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$finish = $time;
			$data['total_time'] = round(($finish - $start), 4);

			$this->load->view('crop_temp',$data);
		} else {
			redirect('login','refresh');
		}
	}

	public function keyword_file() {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'keyword_file',
				'last_page' => base_url('pages/keyword_file')
			);
			$this->session->set_userdata($sessiondata);

			$data_navbar['selected_page'] = 'home';
			$this->load->view('head');
			$this->load->view('navbar',$data_navbar);
			$this->load->view('keyword_file');
			$this->load->view('footer',$data_navbar);
		} else {
			redirect('login','refresh');
		}
	}

	public function reports($page = null, $startdate = null, $enddate = null) {
		if ($this->session->has_userdata('logged_in')) {
			$id_user = $this->session->userdata('id_user');
			$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
			if ($id_group == 1) {
				$sessiondata = array(
					'view' => 'reports',
					'last_page' => base_url('pages/reports')
				);
				$this->session->set_userdata($sessiondata);

				if ($page == 'radios') {
					$data_navbar['selected_page'] = 'reports_radios';
					$data['datatablename'] = 'table_report_radios';
					$data['radiosinfo'] = $this->pages_model->rradios();
					$this->load->view('head');
					$this->load->view('navbar',$data_navbar);
					$this->load->view('reports_radios',$data);
					// $this->load->view('language_datatable',$data);
					$this->load->view('footer',$data_navbar);
				} else if ($page == 'users') {
					$data_navbar['selected_page'] = 'reports_users';
					$data['datatablename'] = 'table_report_users';
					$data['pstartdate'] = $startdate;
					$data['penddate'] = $enddate;
					$this->load->view('head');
					$this->load->view('navbar',$data_navbar);
					$this->load->view('reports_users',$data);
					$this->load->view('footer',$data_navbar);
				} else if ($page == 'all_discard') {
					$data_navbar['selected_page'] = 'reports_users';
					$data['datatablename'] = 'table_report_allusers';
					$data['startdate'] = $startdate;
					$data['enddate'] = $enddate;
					$data['page'] = $page;
					$this->load->view('head');
					$this->load->view('navbar',$data_navbar);
					$this->load->view('report_allusers',$data);
					$this->load->view('language_datatable',$data);
					$this->load->view('footer',$data_navbar);
				} else if ($page == 'all_crop') {
					$data_navbar['selected_page'] = 'reports_users';
					$data['datatablename'] = 'table_report_allusers';
					$data['startdate'] = $startdate;
					$data['enddate'] = $enddate;
					$data['page'] = $page;
					$this->load->view('head');
					$this->load->view('navbar',$data_navbar);
					$this->load->view('report_allusers',$data);
					$this->load->view('language_datatable',$data);
					$this->load->view('footer',$data_navbar);
				} else if ($page == 'all_day'){
					$data_navbar['selected_page'] = 'report_user';
					$data['datatablename'] = 'table_report_allusers';
					$data['startdate'] = $startdate;
					$data['enddate'] = $enddate;
					$data['page'] = $page;
					$data['userinfo'] = $this->pages_model->reports_queries($page,'day','',$startdate,$enddate);
					$this->load->view('head');
					$this->load->view('navbar',$data_navbar);
					$this->load->view('report_user',$data);
					$this->load->view('language_datatable',$data);
					$this->load->view('footer',$data_navbar);
				}
			} else {
				redirect(base_url(),'refresh');
			}

		} else {
			redirect('login','refresh');
		}
	}

	public function report_user($page = null, $iduser = null, $startdate = null, $enddate = null) {
		if ($this->session->has_userdata('logged_in')) {
			$id_user = $this->session->userdata('id_user');
			$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
			if ($id_group == 1) {
				$sessiondata = array(
					'view' => 'report_user',
					'last_page' => base_url('pages/report_user')
				);
				$this->session->set_userdata($sessiondata);

				if ($page == 'discard') {
					$data_navbar['selected_page'] = 'reports_users';
					$data['page'] = $page;
					$data['iduser'] = $iduser;
					$data['datatablename'] = 'table_report_discard_user';
					$data['userinfo'] = $this->pages_model->report_user($page,$iduser,$startdate,$enddate);
					$this->load->view('head');
					$this->load->view('navbar',$data_navbar);
					$this->load->view('report_user',$data);
					$this->load->view('language_datatable',$data);
					$this->load->view('footer',$data_navbar);
				} else if ($page == 'crop') {
					$data_navbar['selected_page'] = 'reports_users';
					$data['page'] = $page;
					$data['iduser'] = $iduser;
					$data['datatablename'] = 'table_report_crop_user';
					$data['userinfo'] = $this->pages_model->report_user($page,$iduser,$startdate,$enddate);
					$this->load->view('head');
					$this->load->view('navbar',$data_navbar);
					$this->load->view('report_user',$data);
					$this->load->view('language_datatable',$data);
					$this->load->view('footer',$data_navbar);
				} else if ($page == 'all') {
					$data_navbar['selected_page'] = 'reports_users';
					$data['page'] = $page;
					$data['iduser'] = $iduser;
					$data['datatablename'] = 'table_report_all_user';
					$data['userinfo'] = $this->pages_model->report_user($page,$iduser,$startdate,$enddate);
					$this->load->view('head');
					$this->load->view('navbar',$data_navbar);
					$this->load->view('report_user',$data);
					$this->load->view('language_datatable',$data);
					$this->load->view('footer',$data_navbar);
				}
			} else {
				redirect(base_url(),'refresh');
			}
		} else {
			redirect('login','refresh');
		}
	}

	public function reports_radios_table($tablename = null){
		$data['datatablename'] = $tablename;
		$data['radiosinfo'] = $this->pages_model->rradios();
		$this->load->view('reports_radios_table',$data);
		$this->load->view('language_datatable',$data);
	}

	public function search() {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'search',
				'last_page' => base_url('pages/search')
			);
			$this->session->set_userdata($sessiondata);

			$data_navbar['selected_page'] = 'search';
			$this->load->view('head');
			$this->load->view('navbar',$data_navbar);
			$data['allclients'] = $this->pages_model->clients(null,null,'radio');
			$data['allradios'] = $this->pages_model->radios();
			$data['alltvc'] = $this->pages_model->tvc();
			// $data['alltvp'] = = $this->pages_model->tvp();
			$this->load->view('search',$data);
			$this->load->view('footer',$data_navbar);
		} else {
			redirect('login','refresh');
		}
	}

	public function search_result($vtype = null, $pageselected = '', $query = '', $start = '') {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'search_result',
				'last_page' => base_url('pages/search_result/'.$pageselected.'/'.$query.'/'.$start),
			);
			$this->session->set_userdata($sessiondata);

			$data_navbar['selected_page'] = 'search';
			$data['allclients'] = $this->pages_model->clients(null,null,'radio');
			$data['allradios'] = $this->pages_model->radios();
			$data['alltvc'] = $this->pages_model->tvc();
			if (empty($pageselected)) {
				$data_sresult['pageselected'] = 1;
			} else {
				$data_sresult['pageselected'] = $pageselected;
			}

			if (empty($query)) {
				$data_search['id_client'] = $this->input->post('clientid');
				$data_search['clientkeywordid'] = $this->input->post('clientkeywordid');
				$data_search['vtype'] = $this->input->post('optionsRadios');
				$data_search['id_radio'] = $this->input->post('radioid');
				$data_search['tvchannel'] = $this->input->post('tvchannel');
				$data_search['startdate'] = $this->input->post('startdate');
				$data_search['enddate'] = $this->input->post('enddate');
				$data_search['starttime'] = $this->input->post('starttime');
				$data_search['endtime'] = $this->input->post('endtime');
				$data_search['keyword'] = $this->input->post('keyword');

				$data['id_client'] = $this->input->post('clientid');
				$data['clientkeywordid'] = $this->input->post('clientkeywordid');
				$data['vtype'] = $this->input->post('optionsRadios');
				$data['id_radio'] = $this->input->post('radioid');
				$data['tvchannel'] = $this->input->post('tvchannel');
				$data['startdate'] = $this->input->post('startdate');
				$data['enddate'] = $this->input->post('enddate');
				$data['starttime'] = $this->input->post('starttime');
				$data['endtime'] = $this->input->post('endtime');
				$data['keyword'] = $this->input->post('keyword');

				$vtype = $this->input->post('optionsRadios');
			} else {
				$data_search = base64_decode($query);
				$data['search'] = base64_decode($query);
				$data['vtype'] = $vtype;
			}

			$searchresult = $this->pages_model->search_result($vtype, $data_search, $start);
			$data_sresult['searchresult'] = $searchresult;
			$data['searchresult'] = $searchresult;

			$this->load->view('head');
			$this->load->view('navbar',$data_navbar);
			$this->load->view('search',$data);

			if ($vtype == 'radio') {
				$this->load->view('search_result',$data_sresult);
			} else if ($vtype == 'tv') {
				$this->load->view('tvsearch_result',$data_sresult);
			}

			$this->load->view('footer',$data_navbar);
		} else {
			redirect('login','refresh');
		}
	}

	public function get_tvwords() {
		$ssource = $this->input->post("source");
		$storystdate = $this->input->post("ststartdate");
		$storyendate = $this->input->post("stenddate");
		$wordstartdate = $this->input->post("wstartdate");
		$wordendate = $this->input->post("wenddate");
		$stories = $this->pages_model->tv_story($ssource, $storystdate, $storyendate);

		header('Content-Type: application/json');
		foreach ($stories->response->docs as $storydoc) {
			$shash = $storydoc->hash_s;
			$shsource = $storydoc->source_s;
			$words = $this->pages_model->tv_words($shash, $wordstartdate, $wordendate);
			if ($words->response->numFound > 0) {
				$swords[$shsource] = $words;
			}
		}
		print json_encode($swords);
	}

	public function get_tvstories() {
		$ssource = $this->input->post("source");
		$storystdate = $this->input->post("ststartdate");
		$storyendate = $this->input->post("stenddate");
		$stories = $this->pages_model->tv_story($ssource, $storystdate, $storyendate);

		header('Content-Type: application/json');
		print json_encode($stories);
	}

	public function pagination($query = null) {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'seach_pagination'
			);
			$this->session->set_userdata($sessiondata);

			$query;
		} else {
			redirect('login','refresh');
		}
	}

	public function clients($success_msg = '') {
		if ($this->session->has_userdata('logged_in')) {
			$id_user = $this->session->userdata('id_user');
			$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
			if ($id_group == 1) {
				$sessiondata = array(
					'view' => 'clients',
					'last_page' => base_url('pages/clients')
				);
				$this->session->set_userdata($sessiondata);

				$data_navbar['selected_page'] = 'clients';
				$this->load->view('head');
				$this->load->view('navbar',$data_navbar);
				$data['clients'] = $this->pages_model->clients(null,null,'radio');
				$data['datatablename'] = 'table_clients';
				if ($success_msg == 'create') {
					$data['success_msg'] = get_phrase('client_created_successfuly');
				}
				else if ($success_msg == 'delete') {
					$data['success_msg'] = get_phrase('client_deleted_successfuly');
				}
				else if ($success_msg == 'update') {
					$data['success_msg'] = get_phrase('client_updated_successfuly');
				}
				else if (!empty($success_msg)){
					redirect('pages/clients','refresh');
				}
				$this->load->view('clients',$data);
				$this->load->view('language_datatable',$data);
				$this->load->view('footer',$data_navbar);
			} else {
				redirect(base_url(),'refresh');
			}
		} else {
			redirect('login','refresh');
		}
	}

	public function api_keywords_client($id_client = null) {
		$keywords = $this->pages_model->keywords_client($id_client);
		header('Content-Type: application/json');
		echo json_encode($keywords,JSON_PRETTY_PRINT);
	}

	public function api_radios() {
		$radiosinfo = $this->pages_model->rradios();
		// $filesinfo = array();
		// $radios = array();
		// $filesinfo = array(
			// 'radios' => array()
		// );
		// foreach ($radiosinfo as $rinfo) {
			// array_push($filesinfo, $this->pages_model->report_byradio($rinfo['id_radio']));
			// $filesinfo = $this->pages_model->report_byradio($rinfo['id_radio']);
			// $filesinfo['radios'][] = $this->pages_model->report_byradio($rinfo['id_radio']);
		// }
		$filesinfo = $this->pages_model->report_radios();
		// print_r($filesinfo);
		header('Content-Type: application/json');
		echo json_encode($filesinfo, JSON_PRETTY_PRINT);
		// echo json_encode($filesinfo,JSON_FORCE_OBJECT|JSON_PRETTY_PRINT);
	}

	public function report_radios() {
		$this->load->view('reports_radios_dev');
	}

	public function create_client() {
		if ($this->session->has_userdata('logged_in')) {
			$data_post['clientname'] = $this->input->post('clientname_add_modal');
			$data_post['clientpriority'] = $this->input->post('clientpriority_add_modal');
			$data_post['clientidskeywords'] = $this->input->post('clientkeywords_add_modal');
			$this->pages_model->create_client($data_post);
			redirect('pages/clients/create','refresh');
		} else {
			redirect('login','refresh');
		}
	}

	public function delete_client() {
		if ($this->session->has_userdata('logged_in')) {
			$data_post['clientid'] = $this->input->post('clientid_delete_modal');
			$this->pages_model->delete_client($data_post);
			redirect('pages/clients/delete','refresh');
		} else {
			redirect('login','refresh');
		}
	}

	public function update_client() {
		if ($this->session->has_userdata('logged_in')) {
			$data_post['clientid'] = $this->input->post('clientid_edit_modal');
			$data_post['clientname'] = $this->input->post('clientname_edit_modal');
			$data_post['clientpriority'] = $this->input->post('clientpriority_edit_modal');
			$this->pages_model->update_client($data_post);
			redirect('pages/clients/update','refresh');
		} else {
			redirect('login','refresh');
		}
	}

	public function update_client_keywords() {
		if ($this->session->has_userdata('logged_in')) {
			$data_post['clientid'] = $this->input->post('clientid_modal');
			$data_post['keywordsids'] = $this->input->post('clientkeywords_modal');
			$data_post['keywordsidsold'] = $this->input->post('clientkeywordsold_modal');

			$this->pages_model->update_client_keyword($data_post);
			redirect('pages/clients/update','refresh');
		} else {
			redirect('login','refresh');
		}
	}

	public function keywords($success_msg = '') {
		if ($this->session->has_userdata('logged_in')) {
			$id_user = $this->session->userdata('id_user');
			$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
			if ($id_group == 1) {
				$sessiondata = array(
					'view' => 'keywords',
					'last_page' => base_url('pages/keywords')
				);
				$this->session->set_userdata($sessiondata);

				$data_navbar['selected_page'] = 'keywords';
				$this->load->view('head');
				$this->load->view('navbar',$data_navbar);
				$data['keywords'] = $this->pages_model->keywords();
				$data['datatablename'] = 'table_keywords';
				if ($success_msg == 'create') {
					$data['success_msg'] = get_phrase('keyword_created_successfuly');
				}
				else if ($success_msg == 'delete') {
					$data['success_msg'] = get_phrase('keyword_deleted_successfuly');
				}
				else if ($success_msg == 'update') {
					$data['success_msg'] = get_phrase('keyword_updated_successfuly');
				}
				else if (!empty($success_msg)){
					redirect('pages/keywords','refresh');
				}
				$this->load->view('keywords',$data);
				$this->load->view('language_datatable',$data);
				$this->load->view('footer',$data_navbar);
			} else {
				redirect(base_url(),'refresh');
			}
		} else {
			redirect('login','refresh');
		}
	}

	public function create_keyword() {
		if ($this->session->has_userdata('logged_in')) {
			$data_post['keywordname'] = $this->input->post('keywordname_add_modal');
			$data_post['keywordpriority'] = $this->input->post('keywordpriority_add_modal');
			$data_post['keywordidskeywords'] = $this->input->post('keywordkeywords_add_modal');
			$this->pages_model->create_keyword($data_post);
			redirect('pages/keywords/create','refresh');
		} else {
			redirect('login','refresh');
		}
	}

	public function delete_keyword() {
		if ($this->session->has_userdata('logged_in')) {
			$data_post['keywordid'] = $this->input->post('keywordid_delete_modal');
			$this->pages_model->delete_keyword($data_post);
			redirect('pages/keywords/delete','refresh');
		} else {
			redirect('login','refresh');
		}
	}

	public function update_keyword() {
		if ($this->session->has_userdata('logged_in')) {
			$data_post['keywordid'] = $this->input->post('keywordid_edit_modal');
			$data_post['keywordname'] = $this->input->post('keywordname_edit_modal');
			$data_post['keywordpriority'] = $this->input->post('keywordpriority_edit_modal');
			$this->pages_model->update_keyword($data_post);
			redirect('pages/keywords/update','refresh');
		} else {
			redirect('login','refresh');
		}
	}

	public function radios() {
		if ($this->session->has_userdata('logged_in')) {
			$id_user = $this->session->userdata('id_user');
			$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
			if ($id_group == 1) {
				$sessiondata = array(
					'view' => 'radios',
					'last_page' => base_url('pages/radios')
				);
				$this->session->set_userdata($sessiondata);

				$data['allradios'] = $this->pages_model->radios();

				$data['datatablename'] = 'table_radios';
				$data_navbar['selected_page'] = 'radios';
				$this->load->view('head');
				$this->load->view('navbar',$data_navbar);
				$this->load->view('radios',$data);
				$this->load->view('language_datatable',$data);
				$this->load->view('footer');
			} else {
				redirect(base_url(),'refresh');
			}
		} else {
			redirect('login','refresh');
		}
	}

	public function system($success_msg = '') {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'system',
				'last_page' => base_url('pages/system')
			);
			$this->session->set_userdata($sessiondata);

			$data_navbar['selected_page'] = 'users';
			$this->load->view('head');
			$this->load->view('navbar',$data_navbar);
			$data['users'] = $this->pages_model->users();
			$data['datatablename'] = 'table_system';
			if ($success_msg == 'create') {
				$data['success_msg'] = get_phrase('user_created_successfuly');
			}
			else if ($success_msg == 'delete') {
				$data['success_msg'] = get_phrase('user_deleted_successfuly');
			}
			else if ($success_msg == 'update') {
				$data['success_msg'] = get_phrase('user_updated_successfuly');
			}
			else if (!empty($success_msg)){
				redirect('pages/users','refresh');
			}
			$this->load->view('system',$data);
			$this->load->view('language_datatable',$data);
			$this->load->view('footer',$data_navbar);
		} else {
			redirect('login','refresh');
		}
	}

	public function users($success_msg = '') {
		if ($this->session->has_userdata('logged_in')) {
			$id_user = $this->session->userdata('id_user');
			$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
			if ($id_group == 1) {
				$sessiondata = array(
					'view' => 'users',
					'last_page' => base_url('pages/users')
				);
				$this->session->set_userdata($sessiondata);

				$data_navbar['selected_page'] = 'users';
				$this->load->view('head');
				$this->load->view('navbar',$data_navbar);
				$data['users'] = $this->pages_model->users();
				$data['groups'] = $this->pages_model->groups();
				$data['datatablename'] = 'table_users';
				if ($success_msg == 'create') {
					$data['success_msg'] = get_phrase('user_created_successfuly');
				}
				else if ($success_msg == 'delete') {
					$data['success_msg'] = get_phrase('user_deleted_successfuly');
				}
				else if ($success_msg == 'update') {
					$data['success_msg'] = get_phrase('user_updated_successfuly');
				}
				else if (!empty($success_msg)){
					redirect('pages/users','refresh');
				}
				$this->load->view('users',$data);
				$this->load->view('language_datatable',$data);
				$this->load->view('footer',$data_navbar);
			} else {
				redirect(base_url(),'refresh');
			}
		} else {
			redirect('login','refresh');
		}
	}

	public function create_user() {
		if ($this->session->has_userdata('logged_in')) {
			$data_post['username'] = $this->input->post('username_add_modal');
			$data_post['password'] = $this->input->post('passwd_add_modal');
			$data_post['email'] = $this->input->post('email_add_modal');
			$data_post['groupid'] = $this->input->post('group_add_modal');
			$this->pages_model->create_user($data_post);
			redirect('pages/users/create','refresh');
		} else {
			redirect('login','refresh');
		}
	}

	public function update_user() {
		if ($this->session->has_userdata('logged_in')) {
			$data_post['userid'] = $this->input->post('userid_edit_modal');
			$data_post['username'] = $this->input->post('username_edit_modal');
			$data_post['email'] = $this->input->post('useremail_edit_modal');
			$data_post['groupid'] = $this->input->post('usergroup_edit_modal');
			$this->pages_model->update_user($data_post);
			redirect('pages/users/update','refresh');
		} else {
			redirect('login','refresh');
		}
	}

	public function changepasswd_user() {
		if ($this->session->has_userdata('logged_in')) {
			$data_post['userid'] = $this->input->post('userid_passwd_modal');
			$data_post['password'] = $this->input->post('passwd_passwd_modal');
			if ($this->input->post('change_passwd_modal') == 'on') {
				$data_post['changepasswd'] = 1;
			}
			else if (is_null($this->input->post('change_passwd_modal'))) {
				$data_post['changepasswd'] = 0;
			}
			$this->pages_model->changepasswd_user($data_post);
			redirect('pages/users/update','refresh');
		} else {
			redirect('login','refresh');
		}
	}

	public function delete_user() {
		if ($this->session->has_userdata('logged_in')) {
			$data_post['userid'] = $this->input->post('userid_delete_modal');
			$this->pages_model->delete_user($data_post);
			redirect('pages/users/delete','refresh');
		} else {
			redirect('login','refresh');
		}
	}

	public function groups($success_msg = '') {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'groups',
				'last_page' => base_url('pages/groups')
			);
			$this->session->set_userdata($sessiondata);

			$data_navbar['selected_page'] = 'groups';
			$this->load->view('head');
			$this->load->view('navbar',$data_navbar);
			$data['groups'] = $this->pages_model->groups();
			$data['datatablename'] = 'table_groups';
			if ($success_msg == 'create') {
				$data['success_msg'] = get_phrase('group_created_successfuly');
			}
			else if ($success_msg == 'delete') {
				$data['success_msg'] = get_phrase('group_deleted_successfuly');
			}
			else if ($success_msg == 'update') {
				$data['success_msg'] = get_phrase('group_updated_successfuly');
			}
			else if (!empty($success_msg)){
				redirect('pages/groups','refresh');
			}
			$this->load->view('groups',$data);
			$this->load->view('language_datatable',$data);
			$this->load->view('footer',$data_navbar);
		} else {
			redirect('login','refresh');
		}
	}

	public function profile($success_msg = '') {
		if ($this->session->has_userdata('logged_in')) {
			$sessiondata = array(
				'view' => 'profile',
				'last_page' => base_url('pages/profile')
			);
			$this->session->set_userdata($sessiondata);

			$data_navbar['selected_page'] = 'profile';
			$this->load->view('head');
			$this->load->view('navbar',$data_navbar);
			$data['profile'] = $this->pages_model->profile();
			$data['datatablename'] = 'table_profile';
			if ($success_msg == 'create') {
				$data['success_msg'] = get_phrase('profile_created_successfuly');
			}
			else if ($success_msg == 'delete') {
				$data['success_msg'] = get_phrase('profile_deleted_successfuly');
			}
			else if ($success_msg == 'update') {
				$data['success_msg'] = get_phrase('profile_updated_successfuly');
			}
			else if (!empty($success_msg)){
				redirect('pages/profile','refresh');
			}
			$this->load->view('profile',$data);
			$this->load->view('language_datatable',$data);
			$this->load->view('footer',$data_navbar);
		} else {
			redirect('login','refresh');
		}
	}

	public function video() {
		$data['tvchannels'] = $this->pages_model->tvc();
		$this->load->view('video', $data);
		$this->load->view('player.js');
		$this->load->view('editor');
	}

	// public function videoplayer() {
		// if ($this->session->has_userdata('logged_in')) {
			// $this->load->view('player.js');
			// $playerfile = "/app/application/views/player.js";
			// print file_get_contents($playerfile);
		// } else {
			// redirect('login','refresh');
		// }
	// }

	// public function videoeditor() {
		// if ($this->session->has_userdata('logged_in')) {
			// $this->load->view('editor.js');
			// $playerfile = "/app/application/views/editor.js";
			// print file_get_contents($playerfile);
		// } else {
			// redirect('login','refresh');
		// }
	// }

	public function proxy($file = null) {
		if ($this->input->method(TRUE) == 'POST') {
			// $postdata = ($_POST = json_decode(file_get_contents("php://input"),true));
			// var_dump($postdata);

			$address = $this->input->post("address");
			$pattern = 'http://video.dataclip.com.br:8001';
			$replace = 'http://192.168.0.19';
			// $address = preg_replace($pattern, $replace,$address);
			$address = str_replace($pattern, $replace, $address);

			if (!is_null($this->input->post("vsource"))) {
				$vsource = $this->input->post("vsource");
				$files = $this->input->post("files");

				$data = array(
					'vsource' => $vsource,
					'files' => $files,
				);
				$data_string = json_encode($data);
				$header = array(
					'Content-Type: application/json',
					'Content-Length: '.strlen($data_string),
					'charset=UTF-8'
				);
				$ch = curl_init($address);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				header('Content-Type: application/json');
				print curl_exec($ch);
			} else {
				header('Content-Type: application/json');
				print file_get_contents($address);	
			}
		} else {
			$fileurl = "http://192.168.0.19/video/getvideo/".$file;

			header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
			header("Content-Type: video/mp4");
			header("Content-Transfer-Encoding: Binary");
			header("Content-Length:".filesize($fileurl));
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			echo $response;
			curl_close($ch);
		
			// header("Content-Disposition: attachment; filename=".$fileurl);
			// readfile($fileurl);
			// file_get_contents($fileurl);
		}
	}
}
?>