<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('America/Recife');
// date_default_timezone_set('America/Sao_Paulo');

function replace_chars($string) {
	// $pattern = "/\n|\\u0000/";
	$pattern = '/[\x00-\x1F\x7F]/u';
	$replacement = ' ';
	$result = preg_replace($pattern, $replacement, $string);
	return $result;
}

$ttime = microtime();
$ttime = explode(' ', $ttime);
$ttime = $ttime[1] + $ttime[0];
$tstart = $ttime;

//DB Connection
$servername = 'mysql';
$username = 'sim';
$password = 'sim';
$dbname = 'sim';

$dbcon = new mysqli($servername, $username, $password, $dbname);
if (!$dbcon) {
	mysqli_close($dbcon);
	die('Not possible to connect: '.mysqli_error());
} else {
	echo 'Conexão bem sucedida'."\n";
}

//Local Solr Connection
$protocol = 'http';
$port = '8983';
$host = '172.17.0.4';

//Get start and endtime
// $sstart = strtotime('2019-03-18 00:00:00');
// $send = strtotime('2018-10-23 02:59:59');
$sstart = strtotime("-10 minutes");
$send = strtotime("now");
$start = date('Y-m-d\TH:i:s', $sstart);
$end = date('Y-m-d\TH:i:s', $send);

$now = date('Y-m-d H:i:s');
echo $now."  Script start"."\r\n";

$ctime = microtime();
$ctime = explode(' ', $ctime);
$ctime = $ctime[1] + $ctime[0];
$cstart = $ctime;

$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_URL => "http://data.knewin.com/tv",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => '{"key": "1fb56fc7-a17f-4535-88a1-429d30565392","offset": 0,"filter": {"languages": ["pt"],"sinceCrawled": "'.$start.'", "untilCrawled": "'.$end.'"},"gmt": "-3","showTimes": true,"sort": {"field": "startTime","order": "asc"}}',
	CURLOPT_HTTPHEADER => array(
		"cache-control: no-cache",
		"content-type: application/json",
	),
));

$jresponse = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
	die();
} else {
	$ctime = microtime();
	$ctime = explode(' ', $ctime);
	$ctime = $ctime[1] + $ctime[0];
	$cfinish = $ctime;
	$ctotal_time = round(($cfinish - $cstart), 4);
	$response = json_decode($jresponse);
}

//info about the reponse
$datafound = $response->num_docs;
$datastart = $response->start;
$datacount = $response->count;

$now = date('Y-m-d H:i:s');
echo $now."  Import start date: ".date("Y-m-d H:i:s", ($sstart))."\r\n";
echo $now."  Import end date: ".date("Y-m-d H:i:s", ($send))."\r\n";
echo $now."  Total found: ".$datafound."\r\n";

if ($datafound > 10) {
	$totalpages = round($datafound / 10);
	for ($page = 0; $page <= $totalpages; $page++) {
		$ctime = microtime();
		$ctime = explode(' ', $ctime);
		$ctime = $ctime[1] + $ctime[0];
		$cstart = $ctime;

		$now = date('Y-m-d H:i:s');
		echo $now."  Current page: ".$page."\r\n";
		echo $now."  Start ".$datastart."\r\n";
		// echo $now."  Current exibition: ".$datacount."\r\n";
		echo "\r\n";

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://data.knewin.com/tv",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => '{"key": "1fb56fc7-a17f-4535-88a1-429d30565392","offset": '.$datastart.',"filter": {"languages": ["pt"],"sinceCrawled": "'.$start.'", "untilCrawled": "'.$end.'"},"gmt": "-3","showTimes": true,"sort": {"field": "startTime","order": "asc"}}',
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"content-type: application/json",
			),
		));

		$jresponse = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
			// die();
			break;
		} else {
			$ctime = microtime();
			$ctime = explode(' ', $ctime);
			$ctime = $ctime[1] + $ctime[0];
			$cfinish = $ctime;
			$ctotal_time = round(($cfinish - $cstart), 4);
			$response = json_decode($jresponse);
		}

		foreach ($response->hits as $data) {
			$did = (int)$data->id;
			$didsource = (int)$data->source_id;
			$dsource = $data->source;
			$dstarttime = $data->startTime;
			$dendtime = $data->endTime;
			$dcrawled = $data->crawled_date;
			$dduration = $data->duration;
			$dmurl = $data->mediaUrl;
			$dcontent = replace_chars($data->content);
			$dtimes = json_encode($data->times);
			$snow = strtotime("now");
			$dnow = date('Y-m-d\TH:i:s', $snow);

			echo "Content ID: ".$did."\r\n";
			echo "Source ID: ".$didsource."\r\n";
			echo "Source: ".$dsource."\r\n";
			echo "starttime: ".$dstarttime."\r\n";
			echo "endtime: ".$dendtime."\r\n";
			// echo "Media URL: ".$dmurl."\r\n";
			// echo "\r\n";
			// echo "\r\n";


			$sourceexist = "SELECT id_source FROM knewin_tv WHERE id_source = ".$didsource;
			$resultsource = $dbcon->query($sourceexist);
			if ($resultsource->num_rows == 0) {
				$insertsource = "INSERT INTO knewin_tv (id_source, source) VALUES (".$didsource.", '".utf8_decode($dsource)."')";
				$dbcon->query($insertsource);
			}

			$path='/solr/knewin_tv';
			$url=$protocol."://".$host.":".$port.$path."/select?wt=json";
			$data = array(
				"query" => "id_i:".$did
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
				// echo "Not found!\r\n";
				// INSERT
				$path='/solr/knewin_tv';
				$url=$protocol."://".$host.":".$port.$path."/update?wt=json";
				//insert into Solr
				$data = array(
					"add" => array(
						"doc" => array(
							"id_i" => $did,
							"id_source_i" => $didsource,
							"source_s" => $dsource,
							"starttime_dt" => $dstarttime,
							"endtime_dt" => $dendtime,
							"crawled_dt" => $dcrawled,
							"inserted_dt" => $dnow,
							"duration_i" => $dduration,
							"mediaurl_s" => $dmurl,
							"content_t" => $dcontent,
							"times_t" => $dtimes
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
				// var_dump($resultcurl);
				// INSERT
				echo "\n\r";
				echo "\n\r";
			} else {
				echo "Exists!";
				echo "\n\r";
				echo "\n\r";
			}
		}

		$datastart = $datastart + 10;
		$dstart = $datastart + 1;
	}
} else {
	$now = date('Y-m-d H:i:s');
	echo $now."  Import start date: ".date("Y-m-d H:i:s", ($sstart))."\r\n";
	echo $now."  Import end date: ".date("Y-m-d H:i:s", ($send))."\r\n";
	echo $now."  Total found: ".$datafound."\r\n";
	echo $now."  Current exibition: ".$datacount."\r\n";
	echo "\r\n";

	foreach ($response->hits as $data) {
		$did = $data->id;
		$didsource = $data->source_id;
		$dsource = $data->source;
		$dstarttime = $data->startTime;
		$dendtime = $data->endTime;
		$dcrawled = $data->crawled_date;
		$dduration = $data->duration;
		$dmurl = $data->mediaUrl;
		$dcontent = $data->content;
		$snow = strtotime("now");
		$dnow = date('Y-m-d\TH:i:s', $snow);

		echo "Content ID: ".$did."\r\n";
		echo "Source ID: ".$didsource."\r\n";
		echo "Source: ".$dsource."\r\n";
		echo "starttime: ".$dstarttime."\r\n";
		echo "endtime: ".$dendtime."\r\n";
		// echo "Media URL: ".$dmurl."\r\n";
		// echo "\r\n";
		// echo "\r\n";

		$sourceexist = "SELECT id_source FROM knewin_tv WHERE id_source = ".$didsource;
		$resultsource = $dbcon->query($sourceexist);
		if ($resultsource->num_rows == 0) {
			$insertsource = "INSERT INTO knewin_tv (id_source, source) VALUES (".$didsource.", '".utf8_decode($dsource)."')";
			$dbcon->query($insertsource);
		}

		$path='/solr/knewin_tv';
		$url=$protocol."://".$host.":".$port.$path."/select?wt=json";
		$data = array(
			"query" => "id_i:".$did
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
			// echo "Not found!\r\n";
			// INSERT
			$path='/solr/knewin_tv';
			$url=$protocol."://".$host.":".$port.$path."/update?wt=json";
			//insert into Solr
			$data = array(
				"add" => array(
					"doc" => array(
						"id_i" => $did,
						"id_source_i" => $didsource,
						"source_s" => $dsource,
						"starttime_dt" => $dstarttime,
						"endtime_dt" => $dendtime,
						"crawled_dt" => $dcrawled,
						"inserted_dt" => $dnow,
						"duration_i" => $dduration,
						"mediaurl_s" => $dmurl,
						"content_t" => $dcontent,
						"times_t" => $dtimes
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
			// var_dump($resultcurl);
			// INSERT
			echo "\n\r";
			echo "\n\r";
		} else {
			echo "Exists!";
			echo "\n\r";
			echo "\n\r";
		}
	}
}

$ttime = microtime();
$ttime = explode(' ', $ttime);
$ttime = $ttime[1] + $ttime[0];
$tfinish = $ttime;
$total_time = round(($tfinish - $tstart), 4);

$now = date('Y-m-d H:i:s');
echo $now."  Import start date: ".date("Y-m-d H:i:s", ($sstart))."\r\n";
echo $now."  Import end date: ".date("Y-m-d H:i:s", ($send))."\r\n";
echo $now."  Total found: ".$datafound."\r\n";
echo $now."  Total connection time: ".$ctotal_time." seconds"."\r\n";
echo $now."  Total import time: ".$total_time." seconds"."\r\n";
echo $now."  Script end"."\r\n";
echo "\r\n";
echo "\r\n";

mysqli_close($dbcon);
die();

?>
