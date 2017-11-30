<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends CI_Controller {
	public function deletepage_dbcache($controller,$view)	{
		$this->db->cache_delete($controller, $view);
		echo "Cache db of page ".$controller."/".$view." sucesssfuly deleted!"."\r\n";
	}

	public function deleteall_dbcache() {
		$this->db->cache_delete_all();
		echo "Database cache sucesssfuly deleted!"."\r\n";
	}

	public function update_dbcache() {
		//$this->db->delete_cache();
		//$this->db->cache_on();
		//$keywords = $this->pages_model->keywords();
		// //var_dump($keywords);
		foreach ($keywords as $key) {
			$this->pages_model->text_keyword($key['keyword']);
		}
		//$this->db->cache_off();
	}

	public function update_viewcache() {
		// $this->db->delete_cache();
		// $this->output->cache_on();
		// $keywords = $this->pages_model->keywords();
		// var_dump($keywords);
		foreach ($keywords as $key) {
			$this->pages_model->text_keyword($key['keyword']);
		}
		// $this->output->cache_off();
	}

	public function deleteall_outputcache()	{
		$this->output->delete_cache();
		echo "Done"."\r\n";
	}

	public function deletepage_pagecache($page = '')	{
		$this->output->delete_cache("/".$page);
		//$this->output->delete_cache('cachecontroller');
		echo "Cache output of page ".$page." sucesssfuly deleted!"."\r\n";
	}

	public function truncate_table($tablename) {
		if (preg_match('/^temp/i',$tablename)) {
			echo "Permitted!"."\r\n";
			$this->db->truncate($tablename);
			echo $tablename." truncated"."\r\n";
		} else {
			echo "Not Permitted!";
		};
	}

	public function get_texts_by_keyword() {
		$todaytextsids = $this->pages_model->get_textsids_bydate();
		foreach ($todaytextsids as $text) {
			$keyws = $this->pages_model->keywords();
			$f = count($keyws);
			$f2 = $f-1;
			$words = null;
			for ($i=0; $i < $f ; $i++) {
				if ($i == $f2) {
					$words .= '[[:<:]]'.$keyws[$i]['keyword'].'[[:>:]]';
				} else {
					$words .= '[[:<:]]'.$keyws[$i]['keyword'].'[[:>:]]|';
				}
			}
			$textsksearch = $this->pages_model->text_keyword_byidfile($text['id_file'],$words);
			foreach ($textsksearch as $textksearch) {
				$data_insert = array(
					'id_file' => $textksearch['id_file'],
					'id_text' => $textksearch['id_text'],
					'text_content' => $textksearch['text_content']
				);
				$this->db->insert('temp_texts_keyword_found', $data_insert);
			}
		}
		echo "Done"."\r\n";
	}

	public function update_texts_by_keyword() {
		$temptablelasttextid = $this->pages_model->getlast_textid_temptable();
		$todaylasttextid = $this->pages_model->getlast_textid_bydate();
		$filesnotinserted = $this->pages_model->get_filesids_notinserted($temptablelasttextid);
		foreach ($filesnotinserted as $file) {
			$keyws = $this->pages_model->keywords();
			$f = count($keyws);
			$f2 = $f-1;
			$words = null;
			for ($i=0; $i < $f ; $i++) {
				if ($i == $f2) {
					$words .= '[[:<:]]'.$keyws[$i]['keyword'].'[[:>:]]';
				} else {
					$words .= '[[:<:]]'.$keyws[$i]['keyword'].'[[:>:]]|';
				}
			}
			$textsksearch = $this->pages_model->text_keyword_byidfile($file['id_file'],$words);
			foreach ($textsksearch as $textksearch) {
				$data_insert = array(
					'id_file' => $textksearch['id_file'],
					'id_text' => $textksearch['id_text'],
					'text_content' => $textksearch['text_content']
				);
				$this->db->insert('temp_texts_keyword_found', $data_insert);
			}
		}
		echo "Done"."\r\n";
	}

	public function update_keyword() {
		$keywords = $this->pages_model->keywords();
		foreach ($keywords as $keyword) {
			$this->pages_model->text_keyword_temp($keyword['keyword']);
		}
	}

	public function update_keyword_home() {
		$data_navbar['selected_page'] = 'home';
		$this->load->view('head');
		$this->load->view('navbar',$data_navbar);
		$data['clients'] = $this->pages_model->clients();
		$data['keywords'] = $this->pages_model->keywords();
		$data['today'] = date('d/m/Y');
		$this->load->view('home',$data);
		$this->load->view('footer',$data_navbar);
	}
}