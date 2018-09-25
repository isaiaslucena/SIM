<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP')) {
			$ipaddress = getenv('HTTP_CLIENT_IP');
		} else if(getenv('HTTP_X_FORWARDED_FOR')) {
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		} else if(getenv('HTTP_X_FORWARDED')) {
			$ipaddress = getenv('HTTP_X_FORWARDED');
		} else if(getenv('HTTP_FORWARDED_FOR')) {
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		} else if(getenv('HTTP_FORWARDED')) {
			$ipaddress = getenv('HTTP_FORWARDED');
		} else if(getenv('REMOTE_ADDR')) {
			$ipaddress = getenv('REMOTE_ADDR');
		} else {
			$ipaddress = 'UNKNOWN';
		}
		return $ipaddress;
	}

	function replace_chars($string) {
		// $pattern = "/\n|\\u0000/";
		$pattern = '/[\x00-\x1F\x7F]/u';
		$replacement = ' ';
		$result = preg_replace($pattern, $replacement, $string);
		return $result;
	}

	public function index() {
		header('Content-Type: application/json');
		$message = "Não permitido!";
		print json_encode($message, JSON_PRETTY_PRINT);
	}

	public function veiculos() {
		if ($this->input->method(TRUE) == 'POST') {
			$postdata = ($_POST = json_decode(file_get_contents("php://input"),true));
			$datalogin['username'] = $postdata['usuario'];
			$datalogin['password'] = md5($postdata['senha']);
			$datalogin['rememberme'] = 0;
			$this->load->model('login_model');
			$login = $this->login_model->validate_login($datalogin);
			if ($login) {
				$this->load->model('pages_model');
				if ($postdata['tipoveiculo'] == 'radio') {
					$veiculos = $this->pages_model->api_radioc();
				} else if ($postdata['tipoveiculo'] == 'tv') {
					$veiculos = $this->pages_model->api_tvc();
				}
				$dataveic['ipaddress'] = $this->get_client_ip();
				$dataveic['id_user'] = $this->db->get_where('user', array('username' => $datalogin['username'] , 'password' => $datalogin['password']))->row()->id_user;
				$dataveic['tipoveiculo'] = $postdata['tipoveiculo'];
				$this->pages_model->api_veiculos_get($dataveic);

				header('Content-Type: application/json');
				print json_encode($veiculos, JSON_PRETTY_PRINT);
			} else {
				header('Content-Type: application/json');
				$message = "Usuário ou senha incorretos!";
				print json_encode($message, JSON_PRETTY_PRINT);
			}
		} else {
			header('Content-Type: application/json');
			$message = "Método não permitido!";
			print json_encode($message, JSON_PRETTY_PRINT);
		}
	}

	public function docs() {
		if ($this->input->method(TRUE) == 'POST') {
			$postdata = ($_POST = json_decode(file_get_contents("php://input"),true));
			$datalogin['username'] = $postdata['usuario'];
			$datalogin['password'] = md5($postdata['senha']);
			$datalogin['rememberme'] = 0;
			$this->load->model('login_model');
			$login = $this->login_model->validate_login($datalogin);
			if ($login) {
				$startts = $postdata['inicio'];
				$endts = $postdata['fim'];

				$datadocs['tipoveiculo'] = $postdata['tipoveiculo'];
				$datadocs['veiculo'] = $postdata['veiculo'];
				$datadocs['pagina'] = $postdata['pagina'];

				if (is_null($startts) or is_null($endts) or empty($startts) or empty($endts) or !isset($postdata['inicio']) or !isset($postdata['inicio'])) {
					header('Content-Type: application/json');
					$message = "Campo de início e fim obrigatórios!";
					print json_encode($message, JSON_PRETTY_PRINT);
					exit();
				} else if (is_null($datadocs['tipoveiculo']) or empty($datadocs['tipoveiculo']) or !isset($postdata['tipoveiculo'])) {
					header('Content-Type: application/json');
					$message = "Campo tipo de veículo obrigatório!";
					print json_encode($message, JSON_PRETTY_PRINT);
				} else {
					$datasearch['startdate'] = date('Y-m-d', $startts);
					$datasearch['enddate'] = date('Y-m-d', $endts);
					$datasearch['starttime'] = date('H:i:s', $startts);
					$datasearch['endtime'] = date('H:i:s', $endts);
					$datasearch['keyword'] = '';
					$datasearch['vsrctype'] = 'knewin';

					$vtype = $postdata['tipoveiculo'];
					if ($vtype == 'radio') {
						$datasearch['vtype'] = 'radio_knewin';
						if (empty($postdata['veiculo'])) {
							header('Content-Type: application/json');
							print json_encode("Informe um veiculo!", JSON_PRETTY_PRINT);
							exit();
						} else {
							$datasearch['tvchannel'] = $postdata['veiculo'];
						}
					} else if ($vtype == 'tv') {
						$datasearch['vtype'] = $vtype;
						$datasearch['tvchannel'] = $postdata['veiculo'];
					}

					$start = $postdata['pagina'];
					$datasearch['clientkeywordid'] = '';
					$datasearch['id_radio'] = '';

					$this->load->model('pages_model');
					$searchresult = $this->pages_model->search_result($vtype, $datasearch, $start);

					$result['status'] = $searchresult->responseHeader->status;
					$result['tempo'] = $searchresult->responseHeader->QTime;
					$result['resultado'] = array(
						'encontrado' => $searchresult->response->numFound,
						'pagina' => $searchresult->response->start,
						'docs' => array()
					);

					foreach ($searchresult->response->docs as $doc) {
						$docarray = array(
							'id' => $doc->id_i,
							'tsinicio' => $doc->starttime_dt,
							'tsfim' => $doc->endtime_dt,
							'veiculo' => $doc->source_s,
							'texto' => $doc->content_t
						);
						array_push($result['resultado']['docs'], $docarray);
					}

					$datadocs['id_user'] = $this->db->get_where('user', array('username' => $datalogin['username'] , 'password' => $datalogin['password']))->row()->id_user;
					$datadocs['inicio'] = $startts;
					$datadocs['fim'] = $endts;

					$datadocs['ipaddress'] = $this->get_client_ip();
					$this->pages_model->api_docs_get($datadocs);

					header('Content-Type: application/json');
					print json_encode($result, JSON_PRETTY_PRINT);
				}
			} else {
				header('Content-Type: application/json');
				$message = "Usuário ou senha incorretos!";
				print json_encode($message, JSON_PRETTY_PRINT);
			}
		} else {
			header('Content-Type: application/json');
			$message = "Método não permitido!";
			print json_encode($message, JSON_PRETTY_PRINT);
		}
	}

	public function save_trans() {
		if ($this->input->method(TRUE) == 'POST') {
			$postdata = ($_POST = json_decode(file_get_contents("php://input"),true));

			$protocol = 'http';
			$port = '8983';
			$host = '172.17.0.3';
			if ($postdata['type'] == 'radio') {
				$path = '/solr/radio';
			} else {
				$path = '/solr/tv';
			}
			$url = $protocol."://".$host.":".$port.$path."/select?wt=json";

			$namehash = sha1($postdata['filename']);

			$data = array(
				"query" => "hash_s:".$namehash
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
			$resultselect = json_decode(curl_exec($ch));

			$idfound = (int)$resultselect->response->numFound;
			if ($idfound == 0) {
				$url = $protocol."://".$host.":".$port.$path."/select?rows=1&sort=id_i+desc&wt=json";
				$data = array(
					"query" => "*:*"
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
				$resultidselect = json_decode(curl_exec($ch));

				if (isset($resultidselect->response->docs[0]->id_i)) {
					$idresult = $resultidselect->response->docs[0]->id_i;
				} else {
					$idresult = 0;
				}

				$filearr = explode("_", $postdata['filename']);
				$radiodisk = $filearr[0];
				$radioname = $filearr[3];
				$radiostate = $filearr[4];
				$radiostartd = $filearr[1];
				$radiostartt = str_replace("-", ":", $filearr[2]);
				$radiostarttime = $radiostartd.'T'.$radiostartt.'Z';

				$radiodata = array(
					'name' => $radioname,
					'state' => $radiostate
				);

				$radioiddb = $this->pages_model->get_radio($radiodata);

				if (count($radioiddb) == 0) {
					$radiosourceid = $this->pages_model->add_radio($radiodata);
				} else {
					$radiosourceid = $radioiddb[0]['id_radio'];
				}

				$secdur = substr($postdata['duration'], 0, 3);
				$duration = new DateInterval('PT'.$secdur.'S');
				$st = new DateTime($radiostarttime);
				$et = $st->add($duration)->format('Y-m-d\TH:i:s\Z');

				$did = $idresult + 1;
				$didsource = $radiosourceid;
				$dsource = $radioname.'_'.$radiostate;
				$dstarttime = $radiostarttime;
				$dendtime = $et;
				$dcrawled = $radiostarttime;
				$dtimestart = $postdata['timestart'];
				$dtimeend = $postdata['timeend'];
				$dduration = (int)$secdur;
				$dmurl = $postdata['filename'];
				$dcontent = $this->replace_chars($postdata['text']);
				$dtimes = json_encode($postdata['parts']);
				$snow = strtotime("now");
				$dnow = date('Y-m-d\TH:i:s\Z', $snow);

				$url = $protocol."://".$host.":".$port.$path."/update?wt=json";
				//insert into Solr
				$data = array(
					"add" => array(
						"doc" => array(
							"hash_s" => $namehash,
							"id_i" => $did,
							"id_source_i" => (int)$didsource,
							"source_s" => $dsource,
							"starttime_dt" => $dstarttime,
							"endtime_dt" => $dendtime,
							"transcstart_dt" => $dtimestart,
							"transcend_dt" => $dtimeend,
							"crawled_dt" => $dcrawled,
							"inserted_dt" => $dnow,
							"duration_i" => $dduration,
							"mediaurl_s" => $dmurl,
							"content_t" => $dcontent,
							"times_t" => $dtimes
						),
					"commitWithin" => 200,
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
				$resultcurl = curl_exec($ch);

				header('Content-Type: application/json');
				$message = "File created with id ".$did;
				print json_encode($message);
			} else {
				header('Content-Type: application/json');
				$message = "File already on database!";
				print json_encode($message);
			}
		} else {
			header("HTTP/1.1 403 Forbidden");
		}
	}

	public function check_trans() {
		// if ($this->input->method(TRUE) == 'POST') {
			$postdata = ($_POST = json_decode(file_get_contents("php://input"),true));

			$postdata['type'] = $this->input->get('type');
			$postdata['filename'] = $this->input->get('filename');

			$protocol = 'http';
			$port = '8983';
			$host = '172.17.0.3';
			if ($postdata['type'] == 'radio') {
				$path = '/solr/radio';
			} else {
				$path = '/solr/tv';
			}
			$url = $protocol."://".$host.":".$port.$path."/select?wt=json";

			$namehash = sha1($postdata['filename']);

			$data = array(
				"query" => "hash_s:".$namehash
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
			$resultselect = json_decode(curl_exec($ch));

			header('Content-Type: application/json');
			$idfound = (int)$resultselect->response->numFound;
			if ($idfound == 0) {
				print json_encode($message["file_exist"] = 0);
			} else {
				print json_encode($message["file_exist"] = 1);
			}
		// } else {
			// header("HTTP/1.1 403 Forbidden");
		// }
	}
}
?>