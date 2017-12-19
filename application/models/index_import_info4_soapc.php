<?php
header('Content-Type: text/html; charset=utf-8');

$ttime = microtime();
$ttime = explode(' ', $ttime);
$ttime = $ttime[1] + $ttime[0];
$tstart = $ttime;

//DB Connection
$servername='mysql';
$username='sim';
$password='sim';
$dbname='sim';

$dbcon = new mysqli($servername, $username, $password, $dbname);
if (!$dbcon) {
	die('Not possible to connect: '.mysql_error());
	mysqli_close($dbcon);
}else {
	echo 'Conexão bem sucedida'."\n";
}

//Solr Connection
$protocol='http';
$port='8983';
$host='172.17.0.3';

// soap
$addr01 = '189.3.21.194';
$addr02 = '186.216.193.146';
$addr03 = '187.16.240.186';

ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 900);
ini_set('default_socket_timeout', 15);
$options = array(
	'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',
	'style'=>SOAP_RPC,
	'use'=>SOAP_ENCODED,
	'soap_version'=>SOAP_1_1,
	'cache_wsdl'=>WSDL_CACHE_NONE,
	'connection_timeout'=>15,
	'trace'=>true,
	'encoding'=>'UTF-8',
	'exceptions'=>true
);


// $wsdl = "http://189.3.21.194:8030/MMS/WS/ChannelManager?wsdl";
// $params =  array(
// 	'userName' => 'info4ws',
// 	'password' => 'info4360ws',
// 	'request' => array()
// );
// try {
// 	$soap = new SoapClient($wsdl, $options);
// 	$data = $soap->getChannels($params);
// }
// catch(Exception $e) {
// 	die($e->getMessage()."\r\n");
// }

// $totalv = count($data->return);
// echo "Todal de veiculos: ".$totalv."\r\n";
// echo "\r\n";
// foreach ($data->return as $channel) {
// 	$vname = $channel->name;
// 	$vdesc = $channel->description;
// 	$vtype = $channel->type;

// 	echo "Veiculo: ".$vname."\r\n";
// 	echo "Descricao: ".$vdesc."\r\n";
// 	echo "Tipo: ".$vtype."\r\n";
// 	echo "\r\n";


// 	$wsdl = "http://189.3.21.194:8030/MMS/WS/EPG?wsdl";
// 	$params =  array(
// 	'userName' => 'info4ws',
// 	'password' => 'info4360ws',
// 		'request' => array (
// 			'channel' => $vname
// 		)
// 	);
// 	try {
// 		$soap = new SoapClient($wsdl, $options);
// 		$data = $soap->getPrograms($params);
// 	}
// 	catch(Exception $e) {
// 		die($e->getMessage()."\r\n");
// 	}

// 	foreach ($data as $program) {
// 		$vpname = $program->name;
// 		$vpdesc = $program->description;
// 		$vpstart = $program->startDate;
// 		$vpduration = $program->duration;
// 		echo "Editoria: ".$vpname."\r\n";
// 		echo "Descricao: ".$vpdesc."\r\n";
// 		echo "Inicio: ".date('Y-m-d H:i:s.z', $vpstart)."\r\n";
// 		echo "Duracao: ".(($vpduration/1000)/60)." Minutes"."\r\n";
// 	}
// }
// echo "Todal de veiculos: ".$totalv."\r\n";
// echo "\r\n";


$starts = strtotime("-15 minutes");
$ends = strtotime("-5 minutes");
$start = $starts * 1000;
$end = $ends * 1000;

$wsdl = 'http://'.$addr01.':8030/MMS/WS/StoryManager?wsdl';
$params =  array(
	'userName' => 'info4ws',
	'password' => 'info4360ws',
	'request' => array (
		'startDate' => $start,
		'endDate' => $end
	)
);

$now = date('Y-m-d H:i:s');
echo $now."  Script start"."\r\n";

try {
	$ctime = microtime();
	$ctime = explode(' ', $ctime);
	$ctime = $ctime[1] + $ctime[0];
	$cstart = $ctime;

	$now = date('Y-m-d H:i:s');
	echo $now."  Connecting to address ".$addr01."..."."\r\n";
	$soap = new SoapClient($wsdl, $options);
	$data = $soap->get($params);
}
catch(Exception $e) {
	$now = date('Y-m-d H:i:s');
	echo $now."  No success!"."\r\n";
	
	try {
       	$now = date('Y-m-d H:i:s');
        	echo $now."  Connecting to address ".$addr02."..."."\r\n";
		$wsdl = 'http://'.$addr02.':8030/MMS/WS/StoryManager?wsdl';
        	$soap = new SoapClient($wsdl, $options);
        	$data = $soap->get($params);
	}
	catch(Exception $ex) {
	        $now = date('Y-m-d H:i:s');
        	echo $now."  No success!"."\r\n";

		try {
	                $now = date('Y-m-d H:i:s');
        	        echo $now."  Connecting to address ".$addr03."..."."\r\n";
                	$wsdl = 'http://'.$addr03.':8030/MMS/WS/StoryManager?wsdl';
                	$soap = new SoapClient($wsdl, $options);
	                $data = $soap->get($params);
		}
		catch(Exception $exc) {
	               $now = date('Y-m-d H:i:s');
			echo $now."  No success!"."\r\n";
			die($exc->getMessage()."\r\n");
		}


	}	
	//$now = date('Y-m-d H:i:s');
	//echo $now."  Conecting now to address ".$addr02."..."."\r\n";
	//$wsdl = 'http://'.$addr02.':8030/MMS/WS/StoryManager?wsdl';
	//$soap = new SoapClient($wsdl, $options);
	//$data = $soap->get($params);
	// die($e->getMessage()."\r\n");
}
 
$ctime = microtime();
$ctime = explode(' ', $ctime);
$ctime = $ctime[1] + $ctime[0];
$cfinish = $ctime;
$ctotal_time = round(($cfinish - $cstart), 4);
echo $now."  Total connection time: ".$ctotal_time." seconds"."\r\n";

$totaln = $data->return->totalResults;
echo $now."  Import start date: ".date("Y-m-d H:i:s", ($start/1000))."\r\n";
echo $now."  Import end date: ".date("Y-m-d H:i:s", ($end/1000))."\r\n";
echo $now."  Total found: ".$totaln."\r\n";

foreach ($data->return as $list) {
	if (is_array($list)) {
		foreach ($list as $listitem) {
			if (isset($listitem->source)) {
				$hash = $listitem->hash;
				$taskidcreator = (int)$listitem->taskIdCreator;
				$date = $listitem->date;
				$insertiondate = $listitem->insertionDate;
				$veiculo = $listitem->source;
				$editoria = $listitem->name;
				$estartdate = (int)$listitem->startDate;
				$eenddate = (int)$listitem->endDate;

				if (preg_match('/^Radio /', $veiculo) == 1) {
					$typev = 'radio';

					$sourceexist = "SELECT id_radiosource, name FROM radiosource_info4 WHERE name = '".utf8_decode($veiculo)."'";
					$resultsource = $dbcon->query($sourceexist);
					if ($resultsource->num_rows == 0) {
						$insertsource = "INSERT INTO radiosource_info4 (name) VALUES ('".utf8_decode($veiculo)."')";
						$dbcon->query($insertsource);
					}

					$programexist = "SELECT name from radioprogram_info4 where name = '".utf8_decode($editoria)."'";
					$resultprogram = $dbcon->query($programexist);
					if ($resultprogram->num_rows == 0) {
						$sourceexist = "SELECT id_radiosource, name FROM radiosource_info4 WHERE name = '".utf8_decode($veiculo)."'";
						$resultsource = $dbcon->query($sourceexist);
						$rowradiosource = $resultsource->fetch_assoc();

						$insertprogram = "INSERT INTO radioprogram_info4 (name, id_radiosource) VALUES ('".utf8_decode($editoria)."', ".$rowradiosource['id_radiosource'].")";
						$dbcon->query($insertprogram);
					}
				} else {
					$typev = 'tv';

					$sourceexist = "SELECT id_tvsource, name FROM tvsource_info4 WHERE name = '".utf8_decode($veiculo)."'";
					$resultsource = $dbcon->query($sourceexist);
					if ($resultsource->num_rows == 0) {
						$insertsource = "INSERT INTO tvsource_info4 (name) VALUES ('".utf8_decode($veiculo)."')";
						$dbcon->query($insertsource);
					}

					$programexist = "SELECT name FROM tvprogram_info4 WHERE name = '".utf8_decode($editoria)."'";
					$resultprogram = $dbcon->query($programexist);
					if ($resultprogram->num_rows == 0) {
						$sourceexist = "SELECT id_tvsource, name FROM tvsource_info4 WHERE name = '".utf8_decode($veiculo)."'";
						$resultsource = $dbcon->query($sourceexist);
						$rowtvsource = $resultsource->fetch_assoc();

						$insertprogram = "INSERT INTO tvprogram_info4 (name, id_tvsource) VALUES ('".utf8_decode($editoria)."', ".$rowtvsource['id_tvsource'].")";
						$dbcon->query($insertprogram);
					}
				}

				$now = date('Y-m-d H:i:s');
				echo $now."  Channel: ".$veiculo."\r\n";
				echo $now."  Program: ".$editoria."\r\n";
				// echo $now."     Inserting segments";
				// echo $now."     Importing to Solr..."."\r\n";
				$segmentswords = null;
				foreach ($listitem->segments as $segment) {
					// echo ".";
					$segmentguid = $segment->guid;
					$segaudiotype = $segment->audioType;
					$segstartdate =  (int)$segment->startDate;
					$segenddate =  (int)$segment->endDate;
					$segdata =  $segment->data;
					if (!empty($segdata) or !is_null($segdata)) {
						$segmentswords .= $segdata;
						$segmentswords .= " ";
					}

					$path='/solr/mms'.$typev.'_segment';
					$url=$protocol."://".$host.":".$port.$path."/update?wt=json";
					//insert into Solr
					$data = array(
						"add" => array(
							"doc" => array(
								"hash_s" => $hash,
								"taskidcreator_l" => $taskidcreator,
								"segguid_i" => $segmentguid,
								"audiotype_s" => $segaudiotype,
								"startdate_l" => $segstartdate,
								"enddate_l" => $segenddate,
								"data_t" => $segdata
							),
						"commitWithin" => 1000,
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

					// echo "    Segmento: ".$segmentguid."\r\n";
					// echo "        Tipo: ".$segaudiotype."\r\n";
					// echo "        Inicio: ".$segstartdate."\r\n";
					// echo "        Fim: ".$segenddate."\r\n";
					// echo "        Inicio: ".date('Y-m-d H:i:s.U', $segstartdate)."\r\n";
					// echo "        Fim: ".date('Y-m-d H:i:s.U', $segenddate)."\r\n";
					// echo "        Texto: ".$segdata."\r\n";

					if (isset($segment->words) ? $segment->words : false) {
						// echo "\r\n";
						// $now = date('Y-m-d H:i:s');
						// echo $now."          Inserindo palavras";
						foreach ($segment->words as $word) {
							// echo ".";
							$segwword = $word->word;
							$segwstarttime = (int)$word->startTime;
							$segwendtime = (int)$word->endTime;
							$segwnorm = $word->norm;
							$segwpunt = $word->normPuntuation;
							
							$path='/solr/mms'.$typev.'_words';
							$url=$protocol."://".$host.":".$port.$path."/update?wt=json";
							//insert into Solr
							$data = array(
								"add" => array(
									"doc" => array(
										"hash_s" => $hash,
										"taskidcreator_l" => $taskidcreator,
										"segguid_i" => $segmentguid,
										"word_s" => $segwword,
										"starttime_l" => $segwstarttime,
										"endtime_l" => $segwendtime,
										"norm_s" => $segwnorm,
										"puntuation_s" => $segwpunt
									),
								"commitWithin" => 1000,
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

							// echo "            Palavra: ".$segwword."\r\n";
							// echo "            Inicio: ".$segwstarttime."\r\n";
							// echo "            Fim: ".$segwendtime."\r\n";
							// echo "            Inicio: ".date('H:i:s.z', $segwstarttime)."\r\n";
							// echo "            Fim: ".date('H:i:s.z', $segwendtime)."\r\n";
							// echo "\r\n";
						}
						// echo "\r\n";
						// echo "\r\n";
					}
				}
				// End segment forech

				// $path='/solr/mms'.$typev.'_text';
				// $url=$protocol."://".$host.":".$port.$path."/update?wt=json";
				// //insert into Solr
				// $data = array(
				// 	"add" => array(
				// 		"doc" => array(
				// 			"hash_s" => $hash,
				// 			"taskidcreator_l" => $taskidcreator,
				// 			"text_t" => $segmentswords
				// 		),
				// 	"commitWithin" => 1000,
				// 	),
				// );
				// $data_string = json_encode($data);
				// $header = array(
				// 	'Content-Type: application/json',
				// 	'Content-Length: '.strlen($data_string),
				// 	'charset=UTF-8'
				// );
				// $ch = curl_init($url);
				// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				// curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				// curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				// curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				// $resultcurl = curl_exec($ch);

				$path='/solr/mms'.$typev.'_story';
				$url=$protocol."://".$host.":".$port.$path."/update?wt=json";
				//insert into Solr
				$data = array(
					"add" => array(
						"doc" => array(
							"hash_s" => $hash,
							"taskidcreator_l" => $taskidcreator,
							"date_l" => $date,
							"insertiondate_l" => $insertiondate,
							"startdate_l" => $estartdate,
							"enddate_l" => $eenddate,
							"source_s" => $veiculo,
							"name_s" => $editoria,
							"text_t" => $segmentswords
						),
					"commitWithin" => 1000,
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

				// echo "\r\n";
				// echo "\r\n";
			}
		}
	}
}

$ttime = microtime();
$ttime = explode(' ', $ttime);
$ttime = $ttime[1] + $ttime[0];
$tfinish = $ttime;
$total_time = round(($tfinish - $tstart), 4);

$now = date('Y-m-d H:i:s');
// echo "Start timestamp: ".$start."\r\n";
// echo "End timestamp: ".$end."\r\n";
echo $now."  Import start date: ".date("Y-m-d H:i:s", ($start/1000))."\r\n";
echo $now."  Import end date: ".date("Y-m-d H:i:s", ($end/1000))."\r\n";
echo $now."  Total found: ".$totaln."\r\n";
echo $now."  Total connection time: ".$ctotal_time." seconds"."\r\n";
echo $now."  Total import time: ".$total_time." seconds"."\r\n";
echo $now."  Script end"."\r\n";
echo "\r\n";
echo "\r\n";

mysqli_close($dbcon);
die;

?>
