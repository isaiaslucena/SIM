<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	public function index() {
		header('Content-Type: application/json');
		$message = "Não permitido!";
		print json_encode($message, JSON_PRETTY_PRINT);
	}

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

					$vtype = $postdata['tipoveiculo'];
					if ($vtype == 'radio') {
						$datasearch['vtype'] = 'radioinfo4';
						if (empty($postdata['veiculo'])) {
							$datasearch['tvchannel'] = "Radio CBN - RJ,Radio Band News - RJ";
						} else if (($postdata['veiculo'] == "Radio CBN - RJ") or ($postdata['veiculo'] == "Radio Band News - RJ")) {
							$datasearch['tvchannel'] = $postdata['veiculo'];
						} else {
							header('Content-Type: application/json');
							print json_encode("Não permitido!", JSON_PRETTY_PRINT);
							exit();
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
							'id' => $doc->id,
							'tsinicio' => $doc->startdate_l,
							'tsfim' => $doc->enddate_l,
							'veiculo' => $doc->source_s,
							'editoria' => $doc->name_s,
							'texto' => $doc->text_t
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
}
?>