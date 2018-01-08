<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages_model extends CI_Model {
	//return all the clients
	public function clients($limit = null, $offset = null, $vhtype) {
		// $this->db->order_by('priority','asc');
		// $this->db->order_by('name','asc');
		// if (!is_null($limit)) {
		// 	$this->db->limit($limit,$offset);
		// }
		// return $this->db->get('client')->result_array();

		if (!is_null($limit)) {
			if ($vhtype == 'radio') {
				$sqlquery = 'SELECT * FROM client WHERE radio = 1 ORDER BY priority,name ASC LIMIT '.$limit.','.$offset;
			} elseif ($vhtype == 'tv') {
				$sqlquery = 'SELECT * FROM client WHERE tv = 1 ORDER BY priority,name ASC LIMIT '.$limit.','.$offset;
			}	
		} else {
			if ($vhtype == 'radio') {
				$sqlquery = 'SELECT * FROM client WHERE radio = 1 ORDER BY priority,name ASC';
			} elseif ($vhtype == 'tv') {
				$sqlquery = 'SELECT * FROM client WHERE tv = 1 ORDER BY priority,name ASC';
			}	
		}
		return $this->db->query($sqlquery)->result_array();
	}

	public function client($id) {
		return $filedb = $this->db->get_where('client', array('id_client' => $id))->result_array();
	}

	//create client
	public function create_client($dataclient) {
		$data_insert_client = array(
			'name' => $dataclient['clientname'],
			'priority' => $dataclient['clientpriority']
		);
		$this->db->insert('client', $data_insert_client);

		//get last_id added
		$sqlquery = 'SELECT * FROM client ORDER BY id_client DESC LIMIT 1';
		$result = $this->db->query($sqlquery);
		$last_id_client = $result->row()->id_client;

		$ids_keywords = explode(",", $dataclient['clientidskeywords']);
		//insert the keywords for client
		foreach ($ids_keywords as $id_keyword) {
			$data_client_keyword = array(
				'id_client' => $last_id_client,
				'id_keyword' => $id_keyword
			);
			$this->db->insert('client_keyword',$data_client_keyword);
		}
	}

	//delete client
	public function delete_client($dataclient) {
		//first remove the relation of the client and the keywords on table client_keyword
		$this->db->delete('client_keyword', array('id_client' => $dataclient['clientid']));
		//finally delete the client of table client
		$this->db->delete('client', array('id_client' => $dataclient['clientid']));
	}

	//update client
	public function update_client($dataclient) {
		$this->db->set('name',$dataclient['clientname']);
		$this->db->set('priority',$dataclient['clientpriority']);
		$this->db->where('id_client',$dataclient['clientid']);
		$this->db->update('client');
	}

	//update the keywords of a specific client
	public function update_client_keyword($dataclient) {
		$arrkeywords = explode(',',$dataclient['keywordsids']);
		$arrkeywordsold = explode(',',$dataclient['keywordsidsold']);

		//count the ids of post
		$narrkeywords = count($arrkeywords);
		//count the ids of db
		$narrkeywordsold = count($arrkeywordsold);

		//if the quantity of ids is greather then ids of db, do de diff and add new ids
		if ($narrkeywords > $narrkeywordsold) {
			$arrdiffkwsids = array_diff($arrkeywords,$arrkeywordsold);
			$arrdiffkwsidsresult=array();
			foreach ($arrdiffkwsids as $kwid) {
				array_push($arrdiffkwsidsresult,$kwid);
			}
			// set the new keywords id to client
			foreach ($arrdiffkwsidsresult as $keywordid) {
				$data_clkw = array(
					'id_client' => $dataclient['clientid'],
					'id_keyword' => $keywordid
				);
				$this->db->insert('client_keyword',$data_clkw);
			}
		}
		//else if the quantity of ids is less then ids of db, do de diff and delete the ids
		else if ($narrkeywords < $narrkeywordsold) {
			$arrdiffkwsids = array_diff($arrkeywordsold,$arrkeywords);
			$arrdiffkwsidsresult=array();
			foreach ($arrdiffkwsids as $kwid) {
				array_push($arrdiffkwsidsresult,$kwid);
			}
			//set the ids to delete
			foreach ($arrdiffkwsidsresult as $keywordid) {
				$data_clkw = array(
					'id_client' => $dataclient['clientid'],
					'id_keyword' => $keywordid
				);
				$this->db->delete('client_keyword',$data_clkw);
			}
		}
	}

	//return all the keywords
	public function keywords() {
		$this->db->order_by('priority','asc');
		$this->db->order_by('keyword','asc');
		return $this->db->get('keyword')->result_array();
	}

	public function terms() {
		$this->db->order_by('priority','asc');
		$this->db->order_by('term','asc');
		return $this->db->get('term')->result_array();
	}

	//create keyword
	public function create_keyword($datakeyword) {
		$data_insert_keyword = array(
			'keyword' => $datakeyword['keywordname'],
			'priority' => $datakeyword['keywordpriority']
		);
		$this->db->insert('keyword', $data_insert_keyword);
	}

	//delete keyword
	public function delete_keyword($datakeyword) {
		$this->db->delete('keyword', array('id_keyword' => $datakeyword['keywordid']));
	}

	//update keyword
	public function update_keyword($datakeyword) {
		$this->db->set('keyword',$datakeyword['keywordname']);
		$this->db->set('priority',$datakeyword['keywordpriority']);
		$this->db->where('id_keyword',$datakeyword['keywordid']);
		$this->db->update('keyword');
	}

	//return all the radios
	public function radios() {
		$this->db->order_by('name','asc');
		return $this->db->get('radio')->result_array();
	}

	//return all tv channels
	public function tvc() {
		$this->db->order_by('name','asc');
		return $this->db->get('tvsource_info4')->result_array();
	}

	public function rac() {
		$this->db->order_by('name','asc');
		return $this->db->get('radiosource_info4')->result_array();
	}

	public function api_tvc() {
		$this->db->select('name');
		$this->db->order_by('name','asc');
		return $this->db->get('tvsource_info4')->result_array();
	}

	public function api_radioc() {
		$sqlquery = "SELECT name FROM radiosource_info4 WHERE name IN ('Radio CBN - RJ', 'Radio Band News - RJ')";
		return $this->db->query($sqlquery)->result_array();
	}

	//create file with all keywords order by keyword name
	public function keywords_name() {
		$jsonfile = '/app/assets/keyword.json';
		$count = 0;
		$sqlquery = 'SELECT keyword FROM keyword ORDER BY keyword ASC';
		$result = $this->db->query($sqlquery)->result_array();
		$resultcount = count($result);
		file_put_contents($jsonfile,'[');
		foreach ($result as $keyword) {
			$count++;
			if ($count == $resultcount ) {
				file_put_contents($jsonfile, '"'.$keyword['keyword'].'"'."\r\n",FILE_APPEND);
			}else {
				file_put_contents($jsonfile, '"'.$keyword['keyword'].'",'."\r\n",FILE_APPEND);
			}

		}
		file_put_contents($jsonfile, ']',FILE_APPEND);
	}

	//return all the keywords of specific client
	public function keywords_client($id_client) {
		$sqlquery =	'SELECT ck.id_client,c.name,ck.id_keyword,k.keyword,k.priority as keyword_priority
						FROM client_keyword ck
						JOIN client c ON ck.id_client=c.id_client
						JOIN keyword k ON ck.id_keyword=k.id_keyword
						WHERE ck.id_client='.$id_client.' ORDER BY k.keyword ASC';
		return $this->db->query($sqlquery)->result_array();
	}

	//load the next or previous files
	public function load_file($position, $timestamp, $id_radio) {
		if ($position == 'previous') {
			$sqlquery =	'SELECT f.id_file, f.path,f.filename,f.type,t.id_text,t.id_file_mp3, t.text_content,f.id_radio,r.name as radio ,r.state,f.timestamp
							FROM file f
							JOIN text t ON f.id_file=t.id_file
							JOIN radio r ON f.id_radio=r.id_radio
							WHERE f.id_radio = '.$id_radio.' AND
							f.timestamp < '.$timestamp.' ORDER BY f.timestamp DESC LIMIT 1';
			return $this->db->query($sqlquery)->result_array();
		} else if ($position == 'next') {
			$sqlquery =	'SELECT f.id_file, f.path,f.filename,f.type,t.id_text,t.id_file_mp3, t.text_content,f.id_radio,r.name as radio ,r.state,f.timestamp
							FROM file f
							JOIN text t ON f.id_file=t.id_file
							JOIN radio r ON f.id_radio=r.id_radio
							WHERE f.id_radio = '.$id_radio.' AND
							f.timestamp > '.$timestamp.' ORDER BY f.timestamp ASC LIMIT 1';
			return $this->db->query($sqlquery)->result_array();
		}
	}

	//return all the clients of specific keyword
	public function clients_keyword($keyword_id) {
		$sqlquery = 	"SELECT k.keyword,c.name
						FROM client_keyword ck
						JOIN client c
						ON ck.id_client=c.id_client
						JOIN keyword k
						ON ck.id_keyword=k.id_keyword
						WHERE ck.id_keyword=".$keyword_id;
		$result = $this->db->query($sqlquery)->result_array();
		return $result;
	}

	//return all the clients with the keywords
	public function clients_and_keywords() {
		$sqlquery =	'SELECT c.name as client, k.keyword
						FROM client c
						JOIN keyword k
						JOIN client_keyword ck
						ON c.id_client=ck.id_client AND k.id_keyword=ck.id_keyword';
		$result = $this->db->query($sqlquery)->result_array();
		return $result;
	}

	public function create_temptable_ksearch($tablename){
		$query = $this->db->query("SHOW TABLES LIKE '$tablename'");
		$resultquery = $query->num_rows();
		if ($resultquery == 0) {
			$this->load->dbforge();
			$fields = array(
							'id_file' => array(
											'type' => 'INT'
							),
							'id_text' => array(
											'type' => 'INT'
							),
							'file_type' => array(
											'type' => 'VARCHAR',
											'constraint' => '255'
							),
							'path' => array(
											'type' =>'VARCHAR',
											'constraint' => '255'
							),
							'filename' => array(
											'type' =>'VARCHAR',
											'constraint' => '255'
							),
							'timestamp' => array(
											'type' =>'INT'
							),
							'radio_name' => array(
											'type' =>'VARCHAR',
											'constraint' => '255'
							),
							'radio_state' => array(
											'type' =>'VARCHAR',
											'constraint' => '2'
							),
							'keyword' => array(
											'type' =>'VARCHAR',
											'constraint' => '255'
							),
							'text_content' => array(
											'type' =>'LONGTEXT'
							),
							'id_text' => array(
											'type' => 'INT'
							),
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table('$tablename');
		}
	}

	public function get_textsids_bydate() {
		$todaydate = strtotime('today 00:00:00');
		$sqlquery =	'SELECT f.id_file FROM file f
						JOIN text t ON f.id_file=t.id_file
						WHERE timestamp >= '.$todaydate.' AND t.discard_keyword = 0 ORDER BY f.timestamp DESC';
		$result = $this->db->query($sqlquery)->result_array();
		return $result;
	}

	public function getlast_textid_bydate() {
		$todaydate = strtotime('today 00:00:00');
		$sqlquery =	'SELECT f.id_file FROM file f
						JOIN text t ON f.id_file=t.id_file
						WHERE timestamp >= '.$todaydate.' AND t.discard_keyword = 0 ORDER BY f.timestamp DESC LIMIT 1';
		$result = $this->db->query($sqlquery);
		$row_id_file = $result->row()->id_file;
		return $row_id_file;
	}

	public function getlast_textid_temptable() {
		$sqlquery = 'SELECT id_file FROM temp_texts_keyword_found ORDER BY id_file DESC LIMIT 1';
		$result = $this->db->query($sqlquery);
		$row = $result->row()->id_file;
		return $row;
	}

	public function get_filesids_notinserted($id_file) {
		$sqlquery =	'SELECT f.id_file FROM file f
						JOIN text t ON f.id_file=t.id_file
						WHERE f.id_file > '.$id_file.'
						AND t.discard_keyword = 0
						ORDER BY f.timestamp ASC';
		$result = $this->db->query($sqlquery)->result_array();
		return $result;
	}

	public function insert_texts_temptable() {
		foreach ($data['keywords'] as $keyword) {
			$texts_result = text_keyword($keyword['keyword']);
			foreach ($texts_result as $text) {
				$data_insert = array(
					'id_file' => $text['id_file'],
					'id_text' => $text['id_text'],
					'file_type' => $text['type'],
					'path' => $text['path'],
					'filename' => $text['filename'],
					'timestamp' => $text['timestamp'],
					'radio_name' => $text['radio'],
					'radio_state' => $text['state'],
					'keyword' => $keyword['keyword'],
					'text_content' => $text['text_content']
				);
				$this->db->insert($temptablename, $data_insert);
			}
		}
	}

	//return the text by keyword
	public function text_keyword($keyword) {
		$todaydate = strtotime('today 00:00:00');

		$sqlquery =	"SELECT t.id_file,t.id_text,f.type,f.path,f.filename,f.timestamp,r.name as radio,r.state,t.text_content
						FROM text t
						JOIN file f ON t.id_file=f.id_file
						JOIN radio r ON f.id_radio=r.id_radio
						WHERE f.timestamp >= ".$todaydate." AND t.discard_keyword=0 AND (t.text_content LIKE '% ".$keyword." %')";
		// $this->db->cache_on();
		$result = $this->db->query($sqlquery)->result_array();
		// $this->db->cache_off();
		return $result;
	}

	public function text_keyword_id($id_file) {
		$sqlquery =	"SELECT t.id_file,t.id_text,f.type,f.path,f.filename,f.timestamp,r.name as radio,f.id_radio,r.state,t.text_content
						FROM text t
						JOIN file f ON t.id_file=f.id_file
						JOIN radio r ON f.id_radio=r.id_radio
						WHERE f.id_file IN (".$id_file.") ORDER BY f.timestamp DESC";
		// $this->db->cache_on();
		$result = $this->db->query($sqlquery)->result_array();
		// $this->db->cache_off();
		return $result;
	}

	public function solr_text_keyword_id($id_file) {
		$todaydate = strtotime('today 00:00:00');

		$sqlquery =	"SELECT t.id_file,t.id_text,f.type,f.path,f.filename,f.timestamp,r.name as radio,r.state,t.text_content
						FROM text t
						JOIN file f ON t.id_file=f.id_file
						JOIN radio r ON f.id_radio=r.id_radio
						WHERE f.id_file IN (".$id_file.")";
		$result = $this->db->query($sqlquery)->result_array();
		return $result;
	}

	//return the text by keyword in temp table
	public function text_keyword_temp($keyword) {
		$sqlquery =	"SELECT t.id_file,t.id_text,f.type,f.path,f.filename,f.timestamp,r.name as radio,r.state,t.text_content
						FROM temp_texts_keyword_found t
						JOIN file f ON t.id_file=f.id_file
						JOIN radio r ON f.id_radio=r.id_radio
						WHERE t.text_content REGEXP '[[:<:]]".$keyword."[[:>:]]' ORDER BY f.timestamp DESC";
		//$this->db->cache_on();
		$result = $this->db->query($sqlquery)->result_array();
		//$this->db->cache_off();
		return $result;
	}

	//return the id_text from discard_text by id_client and id_keyword
	public function discarded_texts($data_discarded) {
		$sqlquery =	'SELECT dk.id_text FROM discard_keyword dk
						JOIN text t ON dk.id_text = t.id_text
						JOIN file f ON t.id_file = f.id_file
						WHERE dk.id_client = '.$data_discarded['id_client'].' AND dk.id_keyword = '.$data_discarded['id_keyword'].' AND
						f.timestamp >= '.$data_discarded['startdate'].' AND f.timestamp <= '.$data_discarded['enddate'];
		return $this->db->query($sqlquery)->result_array();
	}

	public function texts_keyword_byid_solr($ids_text,$keyword,$startdate,$enddate){
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path='/solr/text/query?rows=500&wt=json&sort=id_text_i+desc';
		$url=$protocol."://".$host.":".$port.$path;

		$idsline = null;
		$cidsarr = count($ids_text);
		$ccount = 0;
		foreach ($ids_text as $id => $idstexts) {
			$ccount++;
			foreach ($idstexts as $idd => $id_text) {
				if ($ccount == $cidsarr) {
					$idsline .= "NOT ".$id_text;
				}
				else {
					$idsline .= "NOT ".$id_text." OR ";
				}
			}
		}

		if (!is_null($idsline)) {
			$data = array(
				'query' => '_text_:"'.$keyword.'"',
				'filter' => array(
					'{!join from=id_file_i to=id_file_i fromIndex=file}timestamp_i:['.$startdate.' TO '.$enddate.']',
					'id:('.$idsline.')'
				),
			);
		} else {
			$data = array(
				'query' => '_text_:"'.$keyword.'"',
				'filter' => '{!join from=id_file_i to=id_file_i fromIndex=file}timestamp_i:['.$startdate.' TO '.$enddate.']'
			);
		}


		$data_string = json_encode($data);
		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}

	public function text_keyword_solr($startdate,$enddate,$keyword){
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path='/solr/text/query?rows=500&wt=json&sort=id_text_i+desc';
		$url=$protocol."://".$host.":".$port.$path;

		$data = array(
			'query' => '_text_:"'.$keyword.'"',
			'filter' => '{!join from=id_file_i to=id_file_i fromIndex=file}timestamp_i:['.$startdate.' TO '.$enddate.']'
		);
		$data_string = json_encode($data);

		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}

	public function tv_texts_keyword_byid_solr($ids_text,$keyword,$startdate,$enddate){
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path='/solr/mmstv_story/query?rows=500&wt=json&sort=startdate_l+asc';
		$url=$protocol."://".$host.":".$port.$path;

		$idsline = null;
		// $cidsarr = count($ids_text);
		// $ccount = 0;
		// foreach ($ids_text as $id => $idstexts) {
		// 	$ccount++;
		// 	foreach ($idstexts as $idd => $id_text) {
		// 		if ($ccount == $cidsarr) {
		// 			$idsline .= "NOT ".$id_text;
		// 		}
		// 		else {
		// 			$idsline .= "NOT ".$id_text." OR ";
		// 		}
		// 	}
		// }

		if (!is_null($idsline)) {
			$data = array(
				'query' => '_text_:"'.$keyword.'"',
				'filter' => array(
					'{!join from=id_file_i to=id_file_i fromIndex=file}timestamp_i:['.$startdate.' TO '.$enddate.']',
					'id:('.$idsline.')'
				),
			);
		} else {
			$data = array(
			"query" => 'text_t:"'.$keyword.'"',
			"filter" => "startdate_l:[".$startdatem." TO ".$enddatem."]"
			);
		}


		$data_string = json_encode($data);
		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}

	public function tv_text_keyword_solr_info4($startdate,$enddate,$keyword){
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path='/solr/mmstv_story/query?rows=500&wt=json&sort=startdate_l+asc';
		$url=$protocol."://".$host.":".$port.$path;

		$startdatem = $startdate * 1000;
		$enddatem = $enddate * 1000;

		$data = array(
			"query" => 'text_t:"'.$keyword.'"',
			"filter" => "startdate_l:[".$startdatem." TO ".$enddatem."]"
		);
		$data_string = json_encode($data);

		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}
	
	public function tv_text_keyword_solr($startdate, $enddate, $keyword){
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path='/solr/knewin_tv/query?rows=500&wt=json&sort=starttime_dt+desc';
		$url=$protocol."://".$host.":".$port.$path;

		$data = array(
			"query" => 'content_t:"'.$keyword.'"',
			"filter" => "starttime_dt:[".$startdate."Z TO ".$enddate."Z]"
		);
		$data_string = json_encode($data);

		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}
	
	public function radio_text_keyword_solr($startdate, $enddate, $keyword){
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path='/solr/knewin_radio/query?rows=500&wt=json&sort=starttime_dt+desc';
		$url=$protocol."://".$host.":".$port.$path;

		$data = array(
			"query" => 'content_t:"'.$keyword.'"',
			"filter" => "starttime_dt:[".$startdate."Z TO ".$enddate."Z]"
		);
		$data_string = json_encode($data);

		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}

	public function radio_text_byid_solr($docid) {
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path='/solr/knewin_radio/query?wt=json';
		$url=$protocol."://".$host.":".$port.$path;

		$data = array(
			"query" => 'id_i:'.$docid
		);
		$data_string = json_encode($data);

		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}

	public function get_radio_bysd_solr($idsource, $startdate, $position) {
		//Solr Connection
		$protocol = 'http';
		$port = '8983';
		$host = '172.17.0.3';
		if ($position == 'previous') {
			$path='/solr/knewin_radio/query?wt=json&rows=1&sort=starttime_dt+desc';
			$data = array(
				'query' => 'id_source_i:'.$idsource,
				'filter' => 'endtime_dt:[* TO "'.$startdate.'"]'
			);
		} else if ($position == 'next') {
			$path = '/solr/knewin_radio/query?wt=json&rows=1&sort=starttime_dt+asc';
			$data = array(
				'query' => 'id_source_i:'.$idsource,
				'filter' => 'starttime_dt:["'.$startdate.'" TO *]'
			);
		}
		
		$url = $protocol."://".$host.":".$port.$path;
		$data_string = json_encode($data);
		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		// return json_decode(curl_exec($ch));
		return curl_exec($ch);
	}

	public function text_keywords_solr($startdate,$enddate){
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path='/solr/text/query?rows=1000&wt=json&sort=id_text_i+desc';
		$url=$protocol."://".$host.":".$port.$path;

		$allkeywords = $this->pages_model->keywords();
		$keywordsline = null;
		$ckeywordarr = count($allkeywords);
		$ccount = 0;
		foreach ($allkeywords as $keyword) {
			$ccount++;
			if ($ccount == $ckeywordarr) {
				$keywordsline .= "\"".$keyword['keyword']."\"";
			}
			else {
				$keywordsline .= "\"".$keyword['keyword']."\" OR ";
			}
		}

		$data = array(
			'query' => '_text_:('.$keywordsline.')',
			'filter' => '{!join from=id_file_i to=id_file_i fromIndex=file}timestamp_i:['.$startdate.' TO '.$enddate.']'
		);
		$data_string = json_encode($data);

		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}

	public function alltexts_keywords_solr($startdate,$enddate){
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path='/solr/text/query?rows=1000&wt=json&sort=id_text_i+desc';
		$url=$protocol."://".$host.":".$port.$path;

		$allkeywords = $this->pages_model->keywords();
		$keywordsline = null;
		$ckeywordarr = count($allkeywords);
		$ccount = 0;
		foreach ($allkeywords as $keyword) {
			$ccount++;
			if ($ccount == $ckeywordarr) {
				$keywordsline .= "\"".$keyword['keyword']."\"";
			}
			else {
				$keywordsline .= "\"".$keyword['keyword']."\" OR ";
			}
		}

		$data = array(
			'query' => '_text_:('.$keywordsline.')',
			'filter' => '{!join from=id_file_i to=id_file_i fromIndex=file}timestamp_i:['.$startdate.' TO '.$enddate.']'
		);
		$data_string = json_encode($data);

		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}

	public function alltexts_keyword_solr($startdate,$enddate,$keyword){
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path='/solr/text/query?rows=1000&wt=json&sort=id_text_i+desc';
		$url=$protocol."://".$host.":".$port.$path;

		$data = array(
			'query' => '_text_:"'.$keyword.'"',
			'filter' => array(
				'{!join from=id_file_i to=id_file_i fromIndex=file}timestamp_i:['.$startdate.' TO '.$enddate.']'
				),
			);
		$data_string = json_encode($data);

		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}

	public function text_keyword_byidfile($id_file,$keyword) {
		$sqlquery = "SELECT id_text,id_file,text_content FROM text WHERE id_file = ".$id_file." AND text_content REGEXP '[[:<:]]".$keyword."[[:>:]]'";
		// $this->db->cache_on();
		$result = $this->db->query($sqlquery)->result_array();
		// $this->db->cache_off();
		return $result;
	}

	//discard specific text from keyword search
	public function discard_text_keyword($id_text) {
		$this->db->set('discard_keyword',1);
		$this->db->where('id_text',$id_text);
		$this->db->update('text');
		$this->db->delete('temp_texts_keyword_found',array('id_text' => $id_text));
	}

	public function discard_text($data_discard) {
		$data_insert_discard = array(
			'id_text' => $data_discard['id_text'],
			'id_client' => $data_discard['id_client'],
			'id_keyword' => $data_discard['id_keyword'],
			'timestamp' => strtotime("now"),
			'id_user' => $data_discard['id_user']
		);
		$this->db->insert('discard_keyword', $data_insert_discard);
	}

	public function discard_doc_radio_knewin($data_discard) {
		$data_insert_discard = array(
			'id_doc' => $data_discard['id_doc'],
			'id_client' => $data_discard['id_client'],
			'id_keyword' => $data_discard['id_keyword'],
			'timestamp' => strtotime("now"),
			'id_user' => $data_discard['id_user']
		);
		$this->db->insert('discard_keyword_radio_knewin', $data_insert_discard);
	}

	public function discard_doc_tv_knewin($data_discard) {
		$data_insert_discard = array(
			'id_doc' => $data_discard['id_doc'],
			'id_client' => $data_discard['id_client'],
			'id_keyword' => $data_discard['id_keyword'],
			'timestamp' => strtotime("now"),
			'id_user' => $data_discard['id_user']
		);
		$this->db->insert('discard_keyword_tv_knewin', $data_insert_discard);
	}

	public function client_vhtype($data) {
		if ($data['vhtype'] == "radio") {
			$this->db->set('radio',$data['checked']);
			$this->db->where('id_client',$data['id_client']);
			$this->db->update('client');
		} elseif ($data['vhtype'] == "tv") {
			$this->db->set('tv',$data['checked']);
			$this->db->where('id_client',$data['id_client']);
			$this->db->update('client');
		}
	}

	//crop the audio and send do download
	public function crop($starttime, $endtime, $mp3pathfilename) {
		$soxpath = "/usr/bin/sox";
		$temppathurl = base_url('assets/temp/');
		$temppath = '/app/assets/temp/';
		$duration = $endtime - $starttime;

		if (strpos($mp3pathfilename, 'join_') !== FALSE) {
			$filepath = "/app/assets/temp/".mb_substr($mp3pathfilename, 41);
			$filename = mb_substr($mp3pathfilename, 41);
		} else {
			$filepath = "/app/application/repository/".mb_substr($mp3pathfilename, 47);
			$filename = mb_substr($mp3pathfilename, 62);
			copy($filepath, $temppath."/".$filename.".mp3");
			$filename = mb_substr($mp3pathfilename, 62).".mp3";
		}

		exec($soxpath." ".$temppath.$filename." ".$temppath."crop_".$filename." trim ".$starttime." ".$duration);

		if (strpos($filename, '.mp3') !== FALSE) {
			$finaltempurl = $temppathurl."crop_".$filename;
		} else {
			$finaltempurl = $temppathurl."crop_".$filename.".mp3";
		}
		return $finaltempurl;
	}

	public function crop_knewin($starttime, $endtime, $urlmp3) {
		$soxpath = "/usr/bin/sox";
		$temppathurl = base_url('assets/temp/');
		$temppath = '/app/assets/temp/';
		$duration = $endtime - $starttime;

		$dfilename = "download_".strtotime("now").".mp3";
		$cropfilename = "download_".strtotime("now")."_crop.mp3";
		file_put_contents($temppath.$dfilename, fopen($urlmp3, 'r'));

		exec($soxpath." ".$temppath.$dfilename." ".$temppath.$cropfilename." trim ".$starttime." ".$duration);
		// echo $soxpath." ".$temppath.$dfilename." ".$temppath.$cropfilename." trim ".$starttime." ".$duration;

		$finaltempurl = $temppathurl.$cropfilename;
		return $finaltempurl;
	}

	public function crop_info($data) {
		$data_insert_info = array(
			'id_file' => $data['id_file'],
			'id_user' => $data['id_user'],
			'id_client' => $data['id_client'],
			'id_keyword' => $data['id_keyword'],
			'timestamp' => strtotime("now")
		);
		$this->db->insert('crop_info', $data_insert_info);
	}

	public function join_mp3files($idsfilesmp3) {
		$soxpath = "/usr/bin/sox";
		$temppathurl = base_url('assets/temp/');
		$temppath = '/app/assets/temp/';

		$files = array();
		$filesline = null;
		$countf = 0;
		$idsfilesarr = explode(',', $idsfilesmp3);
		$countfarr = count($idsfilesarr);
		foreach ($idsfilesarr as $idfile) {
			$countf++;
			$filedb = $this->db->get_where('file', array('id_file' => $idfile))->result_array();
			$file = $filedb[0]['path'].'/'.$filedb[0]['filename'];
			copy($file, $temppath.$filedb[0]['filename'].'.mp3');
			if ($countf == $countfarr) {
				$filesline .= $temppath.$filedb[0]['filename'].'.mp3';
				$radio = $this->db->get_where('radio',array('id_radio' => $filedb[0]['id_radio']))->row()->name;
				$date = $filedb[0]['timestamp'];
			} else {
				$filesline .= $temppath.$filedb[0]['filename'].'.mp3 ';
			}
		}
		$joinfile = 'join_'.date('d-m-Y_His', $date).'_'.$radio.'.mp3';
		exec($soxpath.' '.$filesline.' '.$temppath.$joinfile, $execlog, $execoutput);
		$finaltempurl = $temppathurl.$joinfile;
		return $finaltempurl;
	}
	
	public function join_radio_knewin($idsdocs) {
		$soxpath = "/usr/bin/sox";
		$temppathurl = base_url('assets/temp/');
		$temppath = '/app/assets/temp/';

		$filesline = null;
		$datadoc['content_t'] = null;
		$countf = 0;
		$countfarr = count($idsdocs);
		$docinfo = $this->radio_text_byid_solr($idsdocs[0]);
		$datadoc['source_s'] = $docinfo->response->docs[0]->source_s;
		$datadoc['starttime_dt'] = $docinfo->response->docs[0]->starttime_dt;
		foreach ($idsdocs as $iddoc) {
			$countf++;

			$docinfo = $this->radio_text_byid_solr($iddoc);
			$datadoc['content_t'] .= $docinfo->response->docs[0]->content_t[0];
			// var_dump($docinfo);
			
			$dfilename = "jdownload_".strtotime("now").".mp3";
			file_put_contents($temppath.$dfilename, fopen($docinfo->response->docs[0]->mediaurl_s, 'r'));

			if ($countf == $countfarr) {
				$filesline .= $temppath.$dfilename;
				$datadoc['endtime_dt'] = $docinfo->response->docs[0]->endtime_dt;
			} else {
				$filesline .= $temppath.$dfilename.' ';
			}
		}
		
		$joinfile = 'join_'.date('d-m-Y_His', strtotime("now")).'.mp3';
		
		exec($soxpath.' '.$filesline.' '.$temppath.$joinfile, $execlog, $execoutput);
		$datadoc['finalurl'] = $temppathurl.$joinfile;

		return $datadoc;
	}

	public function search_result($vtype, $datasearch, $start) {
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';

		if (empty($start)) {
			$start = 0;
		}

		if (is_array($datasearch)) {
			$starttime = $datasearch['starttime'];
			$endtime = $datasearch['endtime'];
			$startdate = strtotime(str_replace('/','-',$datasearch['startdate']).' '.$starttime);
			$enddate = strtotime(str_replace('/','-',$datasearch['enddate']).' '.$endtime);
			$keyword = $datasearch['keyword'];

			if (isset($datasearch['clientid'])) {
				$idclient = $datasearch['clientid'];
			} else {
				$idclient = '';
			}

			if (empty($datasearch['startrow'])) {
				$datasearch['startrow'] = 0;
			}
			if (empty($datasearch['numrows'])) {
				$datasearch['numrows'] = 10;
			}

			if ($datasearch['vtype'] == 'radio') {
				$path='/solr/text/query?wt=json&start='.$start.'&sort=id_text_i+asc';
				$url=$protocol."://".$host.":".$port.$path;

				// $clientkeyword = $datasearch['clientkeywordid'];
				if (isset($datasearch['clientkeywordid']) and !empty($idclient)) {
					//get all keywords of client
					$sqlquery =	'SELECT ck.id_client,c.name,ck.id_keyword,k.keyword
									FROM client_keyword ck
									JOIN client c ON ck.id_client=c.id_client
									JOIN keyword k ON ck.id_keyword=k.id_keyword
									WHERE ck.id_client = '.$idclient.' ORDER BY k.keyword ASC';
					$result = $this->db->query($sqlquery)->result_array();
					// $ckline = null;
					$ckcount = 0;
					$ckarrcount = count($result);
					foreach ($result as $keyword) {
						$ckcount++;
						if ($ckcount == $ckarrcount) {
							$clientkeyword .= $keyword['keyword'];
						} else {
							$clientkeyword .= $keyword['keyword'].'" OR "';
						}
					}
				} else {
					// $clientkeyword = $this->db->get_where('keyword', array('id_keyword' => $datasearch['clientkeywordid']))->row()->keyword;
					$clientkeyword = null;
				}

				$idradioarr = $datasearch['id_radio'];
				$idradioc = count($idradioarr);
				if ($idradioc > 1) {
					$idradio = implode(',',$idradioarr);
				} else {
					$idradio = $idradioarr;
				}

				//search with startdate and enddate
				if (empty($idclient) and empty($datasearch['clientkeywordid']) and empty($idradio) and empty($keyword)) {
					$data = array(
						"query"  => "{!join from=id_file_i to=id_file_i fromIndex=file}timestamp_i:[".$startdate." TO ".$enddate."]"
					);
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with clientid, all keywords from clientid, startdate and enddate
				else if (!empty($idclient) and empty($datasearch['clientkeywordid']) and empty($idradio) and empty($keyword)) {
					$data = array(
						"query" => "_text_:(\"".$clientkeyword."\")",
						"filter" => "{!join from=id_file_i to=id_file_i fromIndex=file}timestamp_i:[".$startdate." TO ".$enddate."]"
					);
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with clientid, specific keywords from clientid, startdate and enddate
				else if (!empty($idclient) and !empty($datasearch['clientkeywordid']) and empty($idradio) and empty($keyword)) {
					$data = array(
						"query" => "_text_:\"".$clientkeyword."\"",
						"filter" => "{!join from=id_file_i to=id_file_i fromIndex=file}timestamp_i:[".$startdate." TO ".$enddate."]"
					);
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with clientid, radio, startdate and enddate
				else if (!empty($idclient) and empty($datasearch['clientkeywordid']) and !empty($idradio) and empty($keyword)) {
					$idsradios = str_replace(',', ' OR ', $idradio);

					$data = array(
						"query" =>  "_text_:(\"".$clientkeyword."\")",
						"filter" => "{!join from=id_file_i to=id_file_i fromIndex=file}id_radio_i:(".$idsradios.") AND timestamp_i:[".$startdate." TO ".$enddate."]"
					);
					var_dump($data);
					exit();
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with radioid, keyword, startdate and enddate
				else if (empty($idclient) and empty($datasearch['clientkeywordid']) and !empty($idradio) and !empty($keyword)) {
					$idsradios = str_replace(',', ' OR ', $idradio);

					$data = array(
						"query" =>  "_text_:\"".$keyword."\"",
						"filter" => "{!join from=id_file_i to=id_file_i fromIndex=file}id_radio_i:(".$idsradios.") AND timestamp_i:[".$startdate." TO ".$enddate."]"
					);
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with radioid, startdate and enddate
				else if (empty($idclient) and empty($datasearch['clientkeywordid']) and !empty($idradio)  and empty($keyword)) {
					$idsradios = str_replace(',', ' OR ', $idradio);

					$data = array(
						"query" =>  "{!join from=id_file_i to=id_file_i fromIndex=file}id_radio_i:(".$idsradios.") AND timestamp_i:[".$startdate." TO ".$enddate."]"
					);
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with keyword, startdate and endate
				else if (empty($idclient) and empty($datasearch['clientkeywordid']) and empty($idradio)  and !empty($keyword)) {
					$data = array(
						"query" => "_text_:\"".$keyword."\"",
						"filter" => "{!join from=id_file_i to=id_file_i fromIndex=file}timestamp_i:[".$startdate." TO ".$enddate."]"
					);
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with client, client_keyword, startdate and enddate
				// else if (!empty($idclient) and !empty($datasearch['clientkeywordid']) and empty($idradio) and !empty($keyword)) {
				// 	$sqlquery =	"SELECT t.id_file,f.timestamp,f.path,f.filename,t.text_content, r.name as radio,r.state
				// 					FROM text t
				// 					JOIN file f ON t.id_file=f.id_file
				// 					JOIN radio r ON f.id_radio=r.id_radio
				// 					WHERE f.timestamp >= ".$startdate."
				// 					AND f.timestamp <=".$enddate."
				// 					AND r.id_radio IN (".$idradio.")
				// 					AND t.text_content REGEXP '[[:<:]]".$keyword."[[:>:]]'";
				// 	return $this->db->query($sqlquery)->result_array();
				// }
			} else if ($datasearch['vtype'] == 'tv') {
				$knewinday = strtotime('29-11-2017 12:00');
				
				if ($startdate < $knewinday) {
					$startdatem = $startdate * 1000;
					$enddatem = $enddate * 1000;
					$path = '/solr/mmstv_story/query?wt=json&start='.$start.'&sort=source_s+asc,startdate_l+asc';
					$datastr = 1;
				} else {
					$ds = new DateTime(date('Y-m-d H:i:s', $startdate));
					$de = new DateTime(date('Y-m-d H:i:s', $enddate));
					$startdatem = $ds->format('Y-m-d\TH:i:s\Z');
					$enddatem = $de->format('Y-m-d\TH:i:s\Z');
					$path = '/solr/knewin_tv/query?wt=json&start='.$start.'&sort=source_s+asc,starttime_dt+asc';
					$datastr = 2;
				}

				//search with startdate and enddate
				if (empty($idclient) and empty($datasearch['clientkeywordid']) and empty($datasearch['tvchannel']) and empty($keyword)) {
					//$path = '/solr/mmstv_story/query?wt=json&start='.$start.'&sort=source_s+asc,startdate_l+asc';
					$url = $protocol."://".$host.":".$port.$path;

					if ($datastr == 1) {
						$data = array(
							"query"  => "startdate_l:[".$startdatem." TO ".$enddatem."]"
						);
					} else {
						$data = array(
							"query"  => "starttime_dt:[".$startdatem." TO ".$enddatem."]"
						);
					}
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with keyword, startdate and endate
				else if (empty($idclient) and empty($datasearch['clientkeywordid']) and empty($datasearch['tvchannel']) and !empty($keyword)) {
					//$path = '/solr/mmstv_story/query?wt=json&start='.$start.'&sort=source_s+asc,startdate_l+asc';
					$url = $protocol."://".$host.":".$port.$path;

					if ($datastr == 1) {
						$data = array(
							"query" => 'text_t:"'.$keyword.'"',
							"filter" => "startdate_l:[".$startdatem." TO ".$enddatem."]"
						);
					} else {
						$data = array(
							"query" => 'content_t:"'.$keyword.'"',
							"filter" => "starttime_dt:[".$startdatem." TO ".$enddatem."]"
						);
					}
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with tv channel, startdate and enddate
				else if (empty($idclient) and empty($datasearch['clientkeywordid']) and !empty($datasearch['tvchannel']) and empty($keyword)) {
					//$path = '/solr/mmstv_story/query?wt=json&start='.$start.'&sort=source_s+asc,startdate_l+asc';
					$url = $protocol."://".$host.":".$port.$path;

					// $tvchannels = str_replace(',', ' OR ', $datasearch['tvchannel']);

					$channelsline = null;
					$channels = explode(",", $datasearch['tvchannel']);
					$channelscarr = count($channels);
					$channelsc = 0;
					foreach ($channels as $channel) {
						$channelsc++;
						if ($channelsc == $channelscarr) {
							$channelsline .= '"'.$channel.'"';
						} else {
							$channelsline .= '"'.$channel.'" OR ';
						}
					}

					if ($datastr == 1) {
						$data = array(
							"query" => "startdate_l:[".$startdatem." TO ".$enddatem."]",
							"filter" => 'source_s:'.$channelsline
						);
					} else {
						$data = array(
							"query" => "starttime_dt:[".$startdatem." TO ".$enddatem."]",
							"filter" => 'source_s:'.$channelsline
						);
					}
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with tv channel, keyword, startdate and enddate
				else if (empty($idclient) and empty($datasearch['clientkeywordid']) and !empty($datasearch['tvchannel']) and !empty($keyword)) {
					//$path = '/solr/mmstv_story/query?wt=json&start='.$start.'&sort=source_s+asc,startdate_l+asc';
					$url = $protocol."://".$host.":".$port.$path;

					$channelsline = null;
					$channels = explode(",", $datasearch['tvchannel']);
					$channelscarr = count($channels);
					$channelsc = 0;
					foreach ($channels as $channel) {
						$channelsc++;
						if ($channelsc == $channelscarr) {
							$channelsline .= '"'.$channel.'"';
						} else {
							$channelsline .= '"'.$channel.'" OR ';
						}
					}

					if ($datastr == 1) {
						$data = array(
							"query" => 'text_t:"'.$keyword.'"',
							"filter" => array(
								'startdate_l:['.$startdatem.' TO '.$enddatem.']',
								'source_s:'.$channelsline
							)	
						);
					} else {
						$data = array(
							"query" => 'content_t:"'.$keyword.'"',
							"filter" => array(
								'starttime_dt:['.$startdatem.' TO '.$enddatem.']',
								'source_s:'.$channelsline
							)	
						);
					}
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
			} else if ($datasearch['vtype'] == 'radioinfo4') {
				$startdatem = $startdate * 1000;
				$enddatem = $enddate * 1000;

				//search with startdate and enddate
				if (empty($idclient) and empty($datasearch['clientkeywordid']) and empty($datasearch['tvchannel']) and empty($keyword)) {
					// $path = '/solr/mmstv_segments/query?wt=json&start='.$start.'&sort=startdate_l+asc';
					$path = '/solr/mmsradio_story/query?wt=json&start='.$start.'&sort=source_s+asc,startdate_l+asc';
					// $path = '/solr/mmstv_words/query?wt=json&start='.$start.'&rows=80000&sort=starttime_i+asc';
					$url = $protocol."://".$host.":".$port.$path;

					$data = array(
						"query"  => "startdate_l:[".$startdatem." TO ".$enddatem."]"
					);
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with keyword, startdate and endate
				else if (empty($idclient) and empty($datasearch['clientkeywordid']) and empty($datasearch['tvchannel']) and !empty($keyword)) {
					$path = '/solr/mmsradio_story/query?wt=json&start='.$start.'&sort=source_s+asc,startdate_l+asc';
					$url = $protocol."://".$host.":".$port.$path;

					$data = array(
						"query" => 'text_t:"'.$keyword.'"',
						"filter" => "startdate_l:[".$startdatem." TO ".$enddatem."]"
					);
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with tv channel, startdate and enddate
				else if (empty($idclient) and empty($datasearch['clientkeywordid']) and !empty($datasearch['tvchannel']) and empty($keyword)) {
					$path = '/solr/mmsradio_story/query?wt=json&start='.$start.'&sort=source_s+asc,startdate_l+asc';
					$url = $protocol."://".$host.":".$port.$path;

					// $tvchannels = str_replace(',', ' OR ', $datasearch['tvchannel']);

					$channelsline = null;
					$channels = explode(",", $datasearch['tvchannel']);
					$channelscarr = count($channels);
					$channelsc = 0;
					foreach ($channels as $channel) {
						$channelsc++;
						if ($channelsc == $channelscarr) {
							$channelsline .= '"'.$channel.'"';
						} else {
							$channelsline .= '"'.$channel.'" OR ';
						}
					}

					$data = array(
						"query" => "startdate_l:[".$startdatem." TO ".$enddatem."]",
						"filter" => 'source_s:'.$channelsline
					);
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
				//search with tv channel, keyword, startdate and enddate
				else if (empty($idclient) and empty($datasearch['clientkeywordid']) and !empty($datasearch['tvchannel']) and !empty($keyword)) {
					$path = '/solr/mmsradio_story/query?wt=json&start='.$start.'&sort=source_s+asc,startdate_l+asc';
					$url = $protocol."://".$host.":".$port.$path;

					// $tvchannels = str_replace(',', ' OR ', $datasearch['tvchannel']);

					$channelsline = null;
					$channels = explode(",", $datasearch['tvchannel']);
					$channelscarr = count($channels);
					$channelsc = 0;
					foreach ($channels as $channel) {
						$channelsc++;
						if ($channelsc == $channelscarr) {
							$channelsline .= '"'.$channel.'"';
						} else {
							$channelsline .= '"'.$channel.'" OR ';
						}
					}

					$data = array(
						"query" => 'text_t:"'.$keyword.'"',
						"filter" => array(
							'startdate_l:['.$startdatem.' TO '.$enddatem.']',
							'source_s:'.$channelsline
						)	
					);
					$data_string = json_encode($data);

					$header = array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data_string),
						'charset=UTF-8'
					);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

					return json_decode(curl_exec($ch));
				}
			}
		} else {
			if ($vtype == 'radio') {
				$path='/solr/text/query?wt=json&start='.$start.'&sort=id_text_i+asc';
				$url=$protocol."://".$host.":".$port.$path;
				$data_string = $datasearch;
				$header = array(
					'Content-Type: application/json',
					'Content-Length: '.strlen($data_string),
					'charset=UTF-8'
				);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

				return json_decode(curl_exec($ch));
			}
			else if ($vtype == 'tv') {
				if (preg_match('/starttime_dt/', $datasearch) == 0) {
					// $startdatem = $startdate * 1000;
					// $enddatem = $enddate * 1000;
					$path = '/solr/mmstv_story/query?wt=json&start='.$start.'&sort=source_s+asc,startdate_l+asc';
					$datastr = 1;
				} else {
					// $ds = new DateTime(date('Y-m-d H:i:s', $startdate));
					// $de = new DateTime(date('Y-m-d H:i:s', $enddate));
					// $startdatem = $ds->format('Y-m-d\TH:i:s\Z');
					// $enddatem = $de->format('Y-m-d\TH:i:s\Z');
					$path = '/solr/knewin_tv/query?wt=json&start='.$start.'&sort=source_s+asc,starttime_dt+asc';
					$datastr = 2;
				}
				
				
				// $path='/solr/mmstv_story/query?wt=json&start='.$start.'&sort=source_s+asc,startdate_l+asc';
				$url=$protocol."://".$host.":".$port.$path;
				$data_string = $datasearch;
				$header = array(
					'Content-Type: application/json',
					'Content-Length: '.strlen($data_string),
					'charset=UTF-8'
				);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

				return json_decode(curl_exec($ch));
			}
			else if ($vtype == 'radioinfo4') {
				$path='/solr/mmsradio_story/query?wt=json&start='.$start.'&sort=source_s+asc,startdate_l+asc';
				$url=$protocol."://".$host.":".$port.$path;
				$data_string = $datasearch;
				$header = array(
					'Content-Type: application/json',
					'Content-Length: '.strlen($data_string),
					'charset=UTF-8'
				);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

				return json_decode(curl_exec($ch));
			}
		}
	}

	public function tv_words($hash, $starttime, $endtime) {
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path = '/solr/mmstv_words/query?wt=json&rows=100&sort=starttime_l+asc';
		$url = $protocol."://".$host.":".$port.$path;

		// $data = array(
		// 	"query" => "hash_s:\"".$hash."\"",
		// 	"filter" => array(
		// 		"taskidcreator_l:".$taskidcreator,
		// 		"segguid_i:".$guid
		// 	)
		// );
		$data = array(
			"query" => "hash_s:\"".$hash."\"",
			"filter" => "starttime_l:[".$starttime." TO ".$endtime."]"
		);

		$data_string = json_encode($data);

		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}

	public function tv_segments($hash, $taskidcreator) {
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path = '/solr/mmstv_segments/query?wt=json';
		$url = $protocol."://".$host.":".$port.$path;

		$data = array(
			"query" => "startdate_l:[".$startdate." TO ".$enddate."]",
		);
		$data_string = json_encode($data);

		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}

	public function tv_story($source, $startdate, $enddate) {
		//Solr Connection
		$protocol='http';
		$port='8983';
		$host='172.17.0.3';
		$path = '/solr/mmstv_story/query?wt=json&sort=startdate_l+asc';
		$url = $protocol."://".$host.":".$port.$path;

		$data = array(
			"query" => "source_s:".$source,
			// "query" => "\"".$source."\"",
			"filter" => "startdate_l:[".$startdate." TO ".$enddate."]"
		);
		$data_string = json_encode($data);

		$header = array(
			'Content-Type: application/json',
			'Content-Length: '.strlen($data_string),
			'charset=UTF-8'
		);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		return json_decode(curl_exec($ch));
	}

	public function reports_queries($info,$type,$iduser,$startdate,$enddate) {
		if ($info == 'all_discard') {
			if ($type == 'day'){
				$sqlquery =	'SELECT FROM_UNIXTIME(dk.timestamp,\'%Y-%m-%d\') as date
								FROM discard_keyword dk
								JOIN `user` u ON dk.id_user=u.id_user
								JOIN client cl ON dk.id_client=cl.id_client
								JOIN keyword kw ON dk.id_keyword=kw.id_keyword
								JOIN text tx ON dk.id_text=tx.id_text
								JOIN file fl ON tx.id_file=fl.id_file
								WHERE dk.timestamp >= '.$startdate.' AND dk.timestamp <= '.$enddate.' GROUP BY date';
				return $this->db->query($sqlquery)->result_array();
			} else if ($type == 'users') {
				$sqlquery =	'SELECT u.id_user, u.username
								FROM discard_keyword dk
								JOIN `user` u ON dk.id_user=u.id_user
								JOIN client cl ON dk.id_client=cl.id_client
								JOIN keyword kw ON dk.id_keyword=kw.id_keyword
								JOIN text tx ON dk.id_text=tx.id_text
								JOIN file fl ON tx.id_file=fl.id_file
								WHERE
								dk.timestamp >= UNIX_TIMESTAMP(STR_TO_DATE(\''.$startdate.' - 00:00:00\', \'%Y-%m-%d - %H:%i:%s\')) AND
								dk.timestamp <= UNIX_TIMESTAMP(STR_TO_DATE(\''.$enddate.' - 23:59:59\', \'%Y-%m-%d - %H:%i:%s\')) GROUP BY username';
				return $this->db->query($sqlquery)->result_array();
			} else if ($type == 'user') {
				$sqlquery =	'SELECT u.id_user, u.username, COUNT(dk.id_discard) as discard_count
								FROM discard_keyword dk
								JOIN `user` u ON dk.id_user=u.id_user
								WHERE dk.id_user = '.$iduser.' AND
								dk.timestamp >= UNIX_TIMESTAMP(STR_TO_DATE(\''.$startdate.' - 00:00:00\', \'%Y-%m-%d - %H:%i:%s\')) AND
								dk.timestamp <= UNIX_TIMESTAMP(STR_TO_DATE(\''.$enddate.' - 23:59:59\', \'%Y-%m-%d - %H:%i:%s\'))';
				return $this->db->query($sqlquery)->result_array();
			}
		}
		else if ($info == 'all_crop') {
			if ($type == 'day'){
				$sqlquery =	'SELECT FROM_UNIXTIME(ci.timestamp,\'%Y-%m-%d\') as date
								FROM crop_info ci
								JOIN `user` u ON ci.id_user=u.id_user
								JOIN client cl ON ci.id_client=cl.id_client
								JOIN keyword kw ON ci.id_keyword=kw.id_keyword
								WHERE ci.timestamp >= '.$startdate.' AND ci.timestamp <= '.$enddate.' GROUP BY date';
				return $this->db->query($sqlquery)->result_array();
			} else if ($type == 'users') {
				$sqlquery =	'SELECT ci.id_user, u.username
								FROM crop_info ci
								JOIN `user` u ON ci.id_user=u.id_user
								JOIN client cl ON ci.id_client=cl.id_client
								JOIN keyword kw ON ci.id_keyword=kw.id_keyword
								WHERE
								ci.timestamp >= UNIX_TIMESTAMP(STR_TO_DATE(\''.$startdate.' - 00:00:00\', \'%Y-%m-%d - %H:%i:%s\')) AND
								ci.timestamp <= UNIX_TIMESTAMP(STR_TO_DATE(\''.$enddate.' - 23:59:59\', \'%Y-%m-%d - %H:%i:%s\')) GROUP BY username';
				return $this->db->query($sqlquery)->result_array();
			} else if ($type == 'user') {
				$sqlquery =	'SELECT u.id_user, u.username, COUNT(ci.id_crop_info) as crop_count
								FROM crop_info ci
								JOIN `user` u ON ci.id_user=u.id_user
								WHERE ci.id_user = '.$iduser.' AND
								ci.timestamp >= UNIX_TIMESTAMP(STR_TO_DATE(\''.$startdate.' - 00:00:00\', \'%Y-%m-%d - %H:%i:%s\')) AND
								ci.timestamp <= UNIX_TIMESTAMP(STR_TO_DATE(\''.$enddate.' - 23:59:59\', \'%Y-%m-%d - %H:%i:%s\'))';
				return $this->db->query($sqlquery)->result_array();
			}
		}
		else if ($info == 'all_day') {
			// $sqlquery =	'(SELECT u.id_user, u.username, cl.name as client, kw.keyword,
			// 				tx.id_file_mp3, fl.id_file as id_file_xml, dk.id_text,
			// 				tx.text_content, dk.timestamp
			// 				FROM discard_keyword dk
			// 				JOIN `user` u ON dk.id_user=u.id_user
			// 				JOIN client cl ON dk.id_client=cl.id_client
			// 				JOIN keyword kw ON dk.id_keyword=kw.id_keyword
			// 				JOIN text tx ON dk.id_text=tx.id_text
			// 				JOIN file fl ON tx.id_file=fl.id_file
			// 				WHERE
			// 				dk.timestamp >= '.$startdate.' AND dk.timestamp <= '.$enddate.')
			// 				UNION
			// 				(SELECT u.id_user, u.username, cl.name as client, kw.keyword,
			// 				tx.id_file_mp3 as id_file_mp3, fl.id_file as id_file_xml, tx.id_text, tx.text_content,
			// 				ci.timestamp
			// 				FROM crop_info ci
			// 				JOIN `user` u ON ci.id_user=u.id_user
			// 				JOIN client cl ON ci.id_client=cl.id_client
			// 				JOIN keyword kw ON ci.id_keyword=kw.id_keyword
			// 				JOIN text tx ON ci.id_file=tx.id_file
			// 				JOIN file fl ON tx.id_file=fl.id_file
			// 				WHERE
			// 				ci.timestamp >= '.$startdate.' AND ci.timestamp <= '.$enddate.')
			// 				ORDER BY timestamp ASC';
			$sqlquery =	'(SELECT
							dk.id_discard,dk.id_text as id_text_discard,dk.id_keyword as id_keyword_discard,dk.timestamp as timestamp_discard,
							null as id_crop_info,null id_text_crop,null as id_keyword_crop,null as timestamp_crop
							FROM discard_keyword dk
							WHERE dk.id_user='.$iduser.' AND
							dk.timestamp >= '.$startdate.' AND dk.timestamp <= '.$enddate.'
							ORDER BY dk.timestamp ASC)
							UNION ALL
							(SELECT
							null as id_discard,null as id_text_discard,null as id_keyword_discard,null as timestamp_discard,
							ci.id_crop_info,ci.id_text as id_text_crop,ci.id_keyword as id_keyword_crop,ci.timestamp as timestamp_crop
							FROM crop_info ci
							WHERE ci.id_user='.$iduser.' AND
							ci.timestamp >= '.$startdate.' AND ci.timestamp <= '.$enddate.'
							ORDER BY ci.timestamp ASC)';
				return $this->db->query($sqlquery)->result_array();
		}
	}

	public function report_users($type,$startdate,$enddate) {
		if ($type == 'discard') {
			if (is_null($startdate) || is_null($enddate)) {
				$sqlquery =	'SELECT u.id_user, u.username,
								COUNT(dk.id_discard) as discard_count
								FROM discard_keyword dk
								JOIN `user` u ON dk.id_user=u.id_user
								GROUP BY dk.id_user';
				return $this->db->query($sqlquery)->result_array();
			} else {
				$sqlquery =	'SELECT u.id_user, u.username, COUNT(dk.id_discard) as discard_count
								FROM discard_keyword dk
								JOIN `user` u ON dk.id_user=u.id_user
								WHERE dk.timestamp >= '.$startdate.' AND dk.timestamp <= '.$enddate.' GROUP BY dk.id_user ORDER BY username ASC';
				return $this->db->query($sqlquery)->result_array();
			}
		}
		else if ($type == 'all_discard') {
			$sqlquery =	'SELECT u.id_user, u.username, COUNT(dk.id_user) as quantity, FROM_UNIXTIME(dk.timestamp,\'%Y-%m-%d\') as date
							FROM discard_keyword dk
							JOIN `user` u ON dk.id_user=u.id_user
							JOIN client cl ON dk.id_client=cl.id_client
							JOIN keyword kw ON dk.id_keyword=kw.id_keyword
							JOIN text tx ON dk.id_text=tx.id_text
							JOIN file fl ON tx.id_file=fl.id_file
							WHERE dk.timestamp >= '.$startdate.' AND dk.timestamp <= '.$enddate.' GROUP BY date ORDER BY date ASC';
			return $this->db->query($sqlquery)->result_array();
		}
		else if ($type == 'crop') {
			if (is_null($startdate) || is_null($enddate)) {
				$sqlquery =	'SELECT u.id_user, u.username,
								COUNT(ci.id_crop_info) as crop_count
								FROM crop_info ci
								JOIN `user` u ON ci.id_user=u.id_user
								GROUP BY ci.id_user';
				return $this->db->query($sqlquery)->result_array();
			} else {
				$sqlquery =	'SELECT u.id_user, u.username, COUNT(ci.id_crop_info) as crop_count
								FROM crop_info ci
								JOIN `user` u ON ci.id_user=u.id_user
								WHERE ci.timestamp >= '.$startdate.' AND ci.timestamp <= '.$enddate.' GROUP BY ci.id_user ORDER BY username ASC';
				return $this->db->query($sqlquery)->result_array();
			}
		}
		else if ($type == 'all_crop') {
			$sqlquery =	'SELECT u.id_user, u.username, tx.id_file_mp3, fl.id_file as id_file_xml, tx.id_text, cl.name as client, kw.keyword, tx.text_content,ci.timestamp
							FROM crop_info ci
							JOIN `user` u ON ci.id_user=u.id_user
							JOIN client cl ON ci.id_client=cl.id_client
							JOIN keyword kw ON ci.id_keyword=kw.id_keyword
							JOIN text tx ON ci.id_file=tx.id_file
							JOIN file fl ON tx.id_file=fl.id_file
							WHERE ci.timestamp >= '.$startdate.' AND ci.timestamp <= '.$enddate.' ORDER BY ci.timestamp ASC';
			return $this->db->query($sqlquery)->result_array();
		}
		else if ($type == 'all') {
			$sqlquery =	'SELECT id_user,username,SUM(user_count) as total_count FROM
							((SELECT u.id_user, u.username, COUNT(dk.id_discard) as user_count
							FROM discard_keyword dk
							JOIN `user` u ON dk.id_user=u.id_user
							WHERE dk.timestamp >= '.$startdate.' AND dk.timestamp <= '.$enddate.' GROUP BY dk.id_user ORDER BY username ASC)
							UNION ALL
							(SELECT u.id_user, u.username, COUNT(ci.id_crop_info) as user_count
							FROM crop_info ci
							JOIN `user` u ON ci.id_user=u.id_user
							WHERE ci.timestamp >= '.$startdate.' AND ci.timestamp <= '.$enddate.' GROUP BY ci.id_user ORDER BY username ASC))
							x GROUP BY username ORDER BY username ASC';
			return $this->db->query($sqlquery)->result_array();
		}
	}

	public function report_user($type,$iduser,$startdate,$enddate) {
		if ($type == 'discard') {
			$sqlquery =	'SELECT u.id_user, u.username, tx.id_file_mp3, fl.id_file as id_file_xml, dk.id_text, cl.name as client, kw.keyword,dk.timestamp
							FROM discard_keyword dk
							JOIN `user` u ON dk.id_user=u.id_user
							JOIN client cl ON dk.id_client=cl.id_client
							JOIN keyword kw ON dk.id_keyword=kw.id_keyword
							JOIN text tx ON dk.id_text=tx.id_text
							JOIN file fl ON tx.id_file=fl.id_file
							WHERE dk.id_user = '.$iduser.' AND dk.timestamp >= '.$startdate.'  AND dk.timestamp <= '.$enddate;
			return $this->db->query($sqlquery)->result_array();
		}
		else if ($type == 'crop') {
			$sqlquery =	'SELECT u.id_user, u.username, ci.id_client, cl.name as client, ci.id_file, kw.keyword, ci.id_text, ci.text_cropped,ci.timestamp
							FROM crop_info ci
							JOIN `user` u ON ci.id_user=u.id_user
							JOIN client cl ON ci.id_client=cl.id_client
							JOIN keyword kw ON ci.id_keyword=kw.id_keyword
							WHERE ci.id_user = '.$iduser.' AND ci.timestamp >= '.$startdate.' AND ci.timestamp <= '.$enddate;
			return $this->db->query($sqlquery)->result_array();
		}
		else if ($type == 'all') {
			$sqlquery =	'(SELECT
							dk.id_discard,us.username as username_discard,cl.name as client_discard,kw.keyword as keyword_discard,tx.text_content as text_discard,dk.timestamp as timestamp_discard,
							null as id_crop_info,null as username_crop,null as client_crop,null as keyword_crop,null as text_crop,null as timestamp_crop
							FROM discard_keyword dk
							JOIN client cl ON dk.id_client=cl.id_client
							JOIN keyword kw ON dk.id_keyword=kw.id_keyword
							JOIN text tx ON dk.id_text=tx.id_text
							JOIN `user` us ON dk.id_user=us.id_user
							WHERE dk.id_user='.$iduser.' AND
							dk.timestamp >= '.$startdate.' AND
							dk.timestamp <= '.$enddate.'
							ORDER BY dk.timestamp ASC)
							UNION ALL
							(SELECT
							null as id_discard,null as username_discard,null as client_discard,null as keyword_discard,null as text_discard,null as timestamp_discard,
							ci.id_crop_info,us.username as username_crop,cl.name as client_crop,kw.keyword as keyword_crop,ci.id_text as text_crop,ci.timestamp as timestamp_crop
							FROM crop_info ci
							JOIN client cl ON ci.id_client=cl.id_client
							JOIN keyword kw ON ci.id_keyword=kw.id_keyword
							LEFT JOIN text tx ON ci.id_text=tx.id_text
							JOIN `user` us ON ci.id_user=us.id_user
							WHERE ci.id_user='.$iduser.' AND
							ci.timestamp >= '.$startdate.' AND
							ci.timestamp <= '.$enddate.'
							ORDER BY ci.timestamp ASC)';
				return $this->db->query($sqlquery)->result_array();
		}
	}

	public function rradios() {
		$sqlquery = 'SELECT rd.id_radio FROM radio rd WHERE rd.name <> \'\' AND rd.name <> \'NO-NAME\' ORDER BY rd.name';
		return $this->db->query($sqlquery)->result_array();
	}

	public function report_radios() {
		// $sqlquery =	'SELECT fl.id_file,fl.id_radio,
		// 				CONCAT(rd.name,\'-\',rd.state) as radio,
		// 				fl.path, fl.filename,
		// 				fl.timestamp, fl.timestamp_add
		// 				FROM file fl
		// 				JOIN radio rd ON fl.id_radio=rd.id_radio
		// 				WHERE fl.type = \'XML\' AND
		// 				fl.id_radio IN
		// 				(SELECT rd.id_radio
		// 				FROM radio rd
		// 				WHERE rd.name <> \'\' AND rd.name <> \'NO-NAME\')
		// 				ORDER BY fl.timestamp_add DESC LIMIT 20';
		$sqlquery =	'SELECT fl.id_file,fl.id_radio,
						CONCAT(rd.name,\'-\',rd.state) as radio,
						fl.path, fl.filename,
						fl.timestamp, fl.timestamp_add
						FROM file fl
						JOIN radio rd ON fl.id_radio=rd.id_radio
						WHERE fl.type = \'XML\' AND
						fl.id_radio IN
						(SELECT rd.id_radio
						FROM radio rd
						WHERE rd.name <> \'\' AND rd.name <> \'NO-NAME\')
						ORDER BY fl.timestamp_add DESC LIMIT 10';
		return $this->db->query($sqlquery)->result_array();
	}

	public function report_byradio($idradio) {
		$sqlquery =	'SELECT fl.id_file,fl.id_radio,
						CONCAT(rd.name,\'-\',rd.state) as radio,
						fl.path, fl.filename, fl.timestamp, fl.timestamp_add
						FROM file fl JOIN radio rd ON fl.id_radio=rd.id_radio
						WHERE fl.type = \'XML\' AND fl.id_radio = '.$idradio.' ORDER BY fl.timestamp_add DESC LIMIT 1';
		return $this->db->query($sqlquery)->result_array();
	}

	//return all users
	public function users() {
		$this->db->order_by('username','asc');
		return $this->db->get('user')->result_array();
	}

	//get all the users connected today
	public function connected_users() {
		$todaystart = strtotime('today 00:00:00');
		$todayend = strtotime('today 23:59:59');
		$sqlquery = 	'SELECT * FROM ci_sessions
						WHERE data LIKE \'%;logged_in|b:1;%\' AND
						timestamp >= '.$todaystart.' AND timestamp <= '.$todayend;
		return $this->db->query($sqlquery)->result_array();
	}

	public function create_user($datauser) {
		$data_insert_user = array(
			'username' => $datauser['username'],
			'password' => md5($datauser['password']),
			'email' => $datauser['email'],
			'change_password' => 1,
			'id_group' => $datauser['groupid']
		);
		$this->db->insert('user', $data_insert_user);
	}

	public function update_user($datauser) {
		$this->db->set('username',$datauser['username']);
		$this->db->set('email',$datauser['email']);
		$this->db->set('id_group',$datauser['groupid']);
		$this->db->where('id_user',$datauser['userid']);
		$this->db->update('user');
	}

	public function changepasswd_user($datauser) {
		$this->db->set('password',md5($datauser['password']));
		$this->db->set('change_password',$datauser['changepasswd']);
		$this->db->where('id_user',$datauser['userid']);
		$this->db->update('user');
	}

	public function changepasswd($datauser) {
		$this->db->set('password', md5($datauser['password']));
		$this->db->set('change_password', 0);
		$this->db->where('id_user', $datauser['userid']);
		$this->db->update('user');
	}

	public function delete_user($datauser) {
		$this->db->delete('user', array('id_user' => $datauser['userid']));
	}

	public function groups() {
		$this->db->order_by('name','asc');
		return $this->db->get('group')->result_array();
	}

	public function api_veiculos_get($data) {
		$data_insert = array(
			'id_user' => $data['id_user'],
			'tipoveiculo' => $data['tipoveiculo'],
			'timestamp' => strtotime("now"),
			'ipaddress' => $data['ipaddress']
		);
		$this->db->insert('api_veiculos', $data_insert);
	}

	public function api_docs_get($data) {
		$data_insert = array(
			'id_user' => $data['id_user'],
			'inicio' => $data['inicio'],
			'fim' => $data['fim'],
			'tipoveiculo' => $data['tipoveiculo'],
			'veiculo' => $data['veiculo'],
			'pagina' => $data['pagina'],
			'timestamp' => strtotime("now"),
			'ipaddress' => $data['ipaddress']
		);
		$this->db->insert('api_docs', $data_insert);
	}
}
?>