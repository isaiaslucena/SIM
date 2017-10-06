<?php

$request = new HttpRequest();
$request->setUrl('http://189.3.21.194:8030/MMS/WS/StoryManager');
$request->setMethod(HTTP_METH_POST);

$request->setHeaders(array(
	'postman-token' => '7ccf9995-c184-d9b3-8698-9a98cb211c26',
	'cache-control' => 'no-cache',
	'connection' => 'Keep-Alive',
	'soapaction' => '\\"\\"',
	'content-type' => 'application/xml',
	'accept-encoding' => 'gzip,deflate'
));

$request->setBody('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:voic="http://voiceinteraction.pt">
	 <soapenv:Header/>
	 <soapenv:Body>
			<voic:get>
				 <Login>
					<userName>info4ws</userName>
					<password>info4360ws</password>
				 </Login>
				 <request>
					<!--<hash>855983532</hash>-->
					<!--<taskIdCreator>1093573680534824</taskIdCreator>-->
					<source>SBT - RJ</source>
					<startDate>1499850100000</startDate>
					<endDate>1499850110000</endDate>
					<!--<taskIdCreator>1089973838390171</taskIdCreator>-->
				 </request>
			</voic:get>
	 </soapenv:Body>
</soapenv:Envelope>');

try {
	$response = $request->send();

	echo $response->getBody();
} catch (HttpException $ex) {
	echo $ex;
}

?>