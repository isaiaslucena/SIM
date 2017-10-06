<?php

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

$wsdl = "http://189.3.21.194:8030/MMS/WS/EPG?wsdl";
$params =  array(
'userName' => 'info4ws',
'password' => 'info4360ws',
	'request' => array (
		'channel' => 'TV Globo - RJ'
	)
);
try {
	$soap = new SoapClient($wsdl, $options);
	$data = $soap->getPrograms($params);
}
catch(Exception $e) {
	die($e->getMessage()."\r\n");
}

foreach ($data as $program) {
	$vpname = $program->name;
	$vpdesc = $program->description;
	$vpstart = $program->startDate;
	$vpduration = $program->duration;
	echo "Editoria: ".$vpname."\r\n";
	echo "Descricao: ".$vpdesc."\r\n";
	echo "Inicio: ".date('Y-m-d H:i:s.z', $vpstart)."\r\n";
	echo "Duracao: ".(($vpduration/1000)/60)." Minutes"."\r\n";
}

?>