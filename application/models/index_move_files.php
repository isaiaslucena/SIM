<?php
	header('Content-Type: text/html; charset=utf-8');

	echo "Script start"."\n";

	//DB Connection
	$servername='mysql';
	$username='sim';
	$password='sim';
	$dbname='sim';

	//Solr Connection
	$protocol='http';
	$port='8983';
	$host='192.168.0.13';

	$dbcon = new mysqli($servername, $username, $password, $dbname);
	if (!$dbcon) {
		mysqli_close($dbcon);
		die('Not possible to connect: '.mysql_error());
	}else {
		echo 'Connected to database '.$dbname."\n";
	}

	#date_default_timezone_set("America/Sao_Paulo");
	$sourcedir = '/mnt/files/';
	$destdir = '/app/application/repository/01_files/';
	$filespdir = 1000;

	//get mp3 files in source directory
	$mp3filesindir=array_map('basename',glob($sourcedir.'*.{mp3,MP3}',GLOB_BRACE));
	//get filename only
	$mp3filesnames=array();
	foreach ($mp3filesindir as $name) {
		array_push($mp3filesnames,basename($name,".mp3"));
	}

	//get xml files in source directory
	$xmlfilesindir=array_map('basename',glob($sourcedir.'*.{xml,XML}',GLOB_BRACE));
	//get filename only
	$xmlfilesnames=array();
	foreach ($xmlfilesindir as $name) {
		array_push($xmlfilesnames,basename($name,".xml"));
	}

	//chek if the mp3 has xml
	$filesndiffmp3=array_diff($mp3filesnames,$xmlfilesnames);
	//store the filenames with no xml
	$mp3filesnoxml=array();
	foreach ($filesndiffmp3 as $mp3file) {
		//echo $mp3file.".mp3 has no xml!"."\n";
		array_push($mp3filesnoxml,$mp3file);
	}

	//check if the xml has mp3
	$filesndiffxml=array_diff($xmlfilesnames,$mp3filesnames);
	//store the filenames with no mp3
	$xmlfilesnomp3=array();
	foreach ($filesndiffxml as $xmlfile) {
		//echo $xmlfile.".xml has no mp3!"."\n";
		array_push($xmlfilesnomp3,$xmlfile);
	}

	//set the filesnames to move that has mp3 and xml
	$filestomove=array_diff($mp3filesnames,$mp3filesnoxml);

	//build array in sequence with the filesnames to move that has mp3 and xml
	$filestomoveseq=array();
	foreach ($filestomove as $val) {
		array_push($filestomoveseq,$val);
	}

	//count the array values and multiplicate to get the total quantity of files to move
	$filestomoveq=count($filestomoveseq)*2;
	echo "\n";
	echo "Total files: ".$filestomoveq;
	echo "\n";
	//sleep (3);

	$filestomoveexists=array();
	//check if the filename exists on DB
	foreach ($filestomoveseq as $file) {
		//get th filename hash in sha256
		$filenamehash=hash('sha256',$file);
		//query to verify the filename hash (sha256)
		$hashexist = "SELECT filename_hash from file where filename_hash = '$filenamehash'";
		$resulthash = $dbcon->query($hashexist);
		//if the mp3 file not exist, insert it
		if ($resulthash->num_rows == 0) {
			//echo 'File not exist!';
		} else {
			//echo "The filename ".$file." already exist in database!"."\n";
			//echo "\n";
			array_push($filestomoveexists,$file);
		}
	}

	//diff between files that exists and files not exists
	$filestomoveseq2=array_diff($filestomoveseq,$filestomoveexists);

	//create array with the files that not exist on  DB
	$newfilestomoveseq=array();
	foreach ($filestomoveseq2 as $val) {
		array_push($newfilestomoveseq,$val);
	}

	//count the files that realy has to move to repo and include on DB
	$newfilestomoveq=count($newfilestomoveseq)*2;
	echo "\n";
	echo "Total files to move: ".$newfilestomoveq."\n";
	echo "\n";
	//sleep (3);

	$destdirl01=glob($destdir.'*',GLOB_ONLYDIR);
	$subdircont = 0;
	foreach ($destdirl01 as $destsubdirl01) {
		$destsubsdirl02=glob($destsubdirl01.'/*',GLOB_ONLYDIR);
		foreach ($destsubsdirl02 as $destsubdirl02) {
			$subdircont++;
			//check if the subdir has files
			$filesinsubdir02=array_map('basename',glob($destsubdirl02.'/*'));
			$filesninsubdir02=count($filesinsubdir02);
			//echo "There are ".$filesninsubdir02." files in ".$destsubdirl02."\n";
			//echo "\n";
			//sleep(1);

			//check if the subdir is the first dir. If true, the startfilenumber and filenamedecimal is equal to 0;
			if ($subdircont == 1) {
				$startfilenumber = 0;
				$filenamedec = 0;
			}

			//if have more then $filespdir quantity at subdir, exit
			if ($filesninsubdir02 > $filespdir) {
				// exit('The directory '.$destsubdirl02.' has more then '.$filespdir.' files!'."\n");
				echo '**ATENTION!** The directory '.$destsubdirl02.' has more then '.$filespdir.' files ('.$filesninsubdir02.'), skipping... **ATENTION!**'."\n";
				// echo "\n";
			}

			//If has the limit os files per dir, skip
			elseif ($filesninsubdir02 == $filespdir) {
				//$lastfilesinsubdir02=array_map('basename',glob($destsubdirl02.'/*'));
				//echo $destsubdirl02."/ has the limit of ".$filesninsubdir02." files, skiping..."."\n"."\n";

				// $startfilenumber=$filesninsubdir02;
				// $lastfilesinsubdir02=array_map('basename',glob($destsubdirl02.'/*'));
				// $startfolderpath=$destsubdirl02;
				// //echo "The directory ".$destsubdirl02."/ has the last file"."\n";
				// //echo "The filenumber in dir is ".$filesninsubdir02."\n";
				// $filernumber=$filesninsubdir02-1;
				// $filestomovediffdest=($filespdir-$filesninsubdir02);
				// $filenamedec=hexdec($filesinsubdir02[$filernumber]);
				//echo "The filename is ".$filesinsubdir02[$filernumber]."\n";
				//echo "The filename in decimal is ".$filenamedec."\n"."\n";
				//echo "Files to move: ".$newfilestomoveq."\n";
				//echo "Slots available in this folder: ".$filestomovediffdest."\n";
			}

			//If have more then two files and less then files per dir, this is the dir to start the move process
			elseif ($filesninsubdir02 >= 2 and $filesninsubdir02 < $filespdir) {
				$startfilenumber=$filesninsubdir02;
				//get the lastfile in subdir
				$lastfilesinsubdir02=array_map('basename',glob($destsubdirl02.'/*'));
				$startfolderpath=$destsubdirl02;
				//the position (index number) in array (array start with 0)
				$filernumber=$filesninsubdir02-1;
				//diff of files per dir and files in dir (free slots available to move the files)
				$filestomovediffdest=($filespdir-$filesninsubdir02);
				$filenamedec=hexdec($filesinsubdir02[$filernumber]);

				echo "\n";
				echo "The directory ".$destsubdirl02."/ has the last file"."\n";
				echo "The number of files in dir: ".$filesninsubdir02."\n";
				echo "The last filename: ".$filesinsubdir02[$filernumber]."\n";
				echo "The last filename in decimal: ".$filenamedec."\n";
				echo "Total files to move: ".$newfilestomoveq."\n";
				echo "Slots available in this folder: ".$filestomovediffdest."\n";
				echo "\n";
				$filenamedec++;
				// sleep(5);

				if ($newfilestomoveq > $filestomovediffdest) {
					$filestomovediffdest2 = $filestomovediffdest/2;
					for ($freeslots=0; $freeslots < $filestomovediffdest2; $freeslots++) {
						movefiles($sourcedir,$startfolderpath,$newfilestomoveseq[$freeslots],$filenamedec++);
						$filenamedec++;
					}
					$newfilestomoveseq=array_slice($newfilestomoveseq,$freeslots);
				}
				else if ($newfilestomoveq < $filestomovediffdest) {
					$newfilestomoveq2=$newfilestomoveq/2;
					for ($freeslots=0; $freeslots < $newfilestomoveq2; $freeslots++) {
						movefiles($sourcedir,$startfolderpath,$newfilestomoveseq[$freeslots],$filenamedec++);
						$filenamedec++;
					}
					$newfilestomoveseq=array_slice($newfilestomoveseq,$freeslots);
				}
				//if the moved all the files, exit
				$newfilestomoveq=count($newfilestomoveseq);
				if (count($newfilestomoveseq) == 0 ) {
					curl_close($ch);
					mysqli_close($dbcon);
					echo "Moved all files!"."\n";
					exit("Script end"."\n"."\n"."\n");
				}
			}

			//If the folder is empty, start the move process
			elseif ($filesninsubdir02 == 0) {
				// $startfilenumber=0;
				$startfolderpath=$destsubdirl02;
				// $filestomovediffdest=($filespdir-$startfilenumber)/2;
				// echo $destsubdirl02."/ has no files inside!"."\n"."\n";

				//if the quantity of files to move is greater then $filespdir, move only $filespdir quantity
				if ($newfilestomoveq > $filespdir) {
					for ($freeslots=0; $freeslots < $filespdir; $freeslots++) {
						movefiles($sourcedir,$startfolderpath,$newfilestomoveseq[$freeslots],$filenamedec++);
						$filenamedec++;
					}
					$newfilestomoveseq=array_slice($newfilestomoveseq,$freeslots);
				}
				//else if the quantity of files is less then $filespdir, move only the quantity of free slots
				elseif ($newfilestomoveq < $filespdir) {
					$newfilestomoveq2=$newfilestomoveq/2;
					for ($freeslots=0; $freeslots < $newfilestomoveq2; $freeslots++) {
						movefiles($sourcedir,$startfolderpath,$newfilestomoveseq[$freeslots],$filenamedec++);
						$filenamedec++;
					}
					$newfilestomoveseq=array_slice($newfilestomoveseq,$freeslots);
				}
				//if the moved all the files, exit
				$newfilestomoveq=count($newfilestomoveseq);
				if (count($newfilestomoveseq) == 0 ) {
					curl_close($ch);
					mysqli_close($dbcon);
					echo "Moved all files!"."\n";
					exit("Script end"."\n"."\n"."\n");
				}
			}
		}
	}

	curl_close($ch);
	mysqli_close($dbcon);
	
	function movefiles($srcdir,$dstdir,$srcfilenm,$dstfilenm) {
		global $dbcon;
		global $protocol;
		global $port;
		global $host;
		$path='/solr/file';
		$url=$protocol."://".$host.":".$port.$path."/update?wt=json";

		//if the source filename is not null, start the process
		// if (!is_null($srcfilenm)) {
			//filename infos
			$filenameinf=explode("_",$srcfilenm);
			$filenamets=strtotime($filenameinf[0].$filenameinf[1]);
			$filenamerd=$filenameinf[2];
			$filenamest=$filenameinf[3];
			$filenamehex=str_pad(strtoupper(dechex($dstfilenm++)),8,0,STR_PAD_LEFT);
			$filenamehash=hash('sha256',$srcfilenm);
			$nowtimestamp = time();

			//query to verify the filename hash (sha256)
			// $hashexist = "SELECT filename_hash from file where filename_hash = '$filenamehash'";
			// $resulthash = $dbcon->query($hashexist);
			//if the mp3 file not exist, insert it
			// if ($resulthash->num_rows == 0) {

				//query to verify radio
				$radioexist = "SELECT id_radio,name,state from radio WHERE name = '$filenamerd' and state = '$filenamest'";
				$resultradio=$dbcon->query($radioexist);

				//if radio not exists, insert it
				if ($resultradio->num_rows == 0) {
					$insertradio = "INSERT INTO radio (name,state) VALUES ('$filenamerd','$filenamest')";
					$dbcon->query($insertradio);
					$resultradio = $dbcon->query($radioexist);
				}

				//if radio exists, insert the file
				if ($resultradio->num_rows == 1) {

					//get id_radio
					$rowradio = $resultradio->fetch_assoc();

					//start the copy process initiating at number after the last file in the last folder
					//mp3 file
					echo "Moving file ".$srcdir.$srcfilenm.".mp3 to ".$dstdir."/".$dstfilenm."\n";
					// echo "INSERT INTO file (id_radio,path,filename,filename_hash,type,timestamp) VALUES ('".$rowradio['id_radio']."','".$dstdir."','".$filenamehex."','".$filenamehash."','MP3','".$filenamets."')"."\n";
					$insertmp3 = "INSERT INTO file (id_radio,path,filename,filename_hash,type,timestamp,timestamp_add) VALUES (".$rowradio['id_radio'].",'".$dstdir."','".$filenamehex."','".$filenamehash."','MP3',".$filenamets.",".$nowtimestamp.")";
					$dbcon->query($insertmp3);
					$lastidmp3 = $dbcon->insert_id;
					rename($srcdir.$srcfilenm.".mp3",$dstdir."/".$filenamehex);
					// copy($srcdir.$srcfilenm.".mp3",$dstdir."/".$filenamehex);

					//insert into Solr
					$data = array(
						"add" => array(
							"doc" => array(
								"id"						=> $lastidmp3,
								"id_file_i"				=> $lastidmp3,
								"id_radio_i"			=> $rowradio['id_radio'],
								"path_s"				=> $dstdir,
								"filename_s"			=> $filenamehex,
								"filename_hash_s"		=> $filenamehash,
								"type_s"				=> 'MP3',
								"timestamp_i"			=> $filenamets,
								"timestamp_add_i"	=> $nowtimestamp
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

			 		//xml file
			 		$filenamehex=str_pad(strtoupper(dechex($dstfilenm++)),8,0,STR_PAD_LEFT);
					echo "Moving file ".$srcdir.$srcfilenm.".xml to ".$dstdir."/".$dstfilenm."\n";
					echo "\n";
					// echo "INSERT INTO file (id_radio,path,filename,type,timestamp) VALUES ('".$rowradio['id_radio']."','".$dstdir."','".$filenamehex."','XML','".$filenamets."')"."\n";
					$insertxml = "INSERT INTO file (id_radio,path,filename,type,timestamp,timestamp_add) VALUES (".$rowradio['id_radio'].",'".$dstdir."','".$filenamehex."','XML',".$filenamets.",".$nowtimestamp.")";
					$dbcon->query($insertxml);
					$lastidxml = $dbcon->insert_id;
					rename($srcdir.$srcfilenm.".xml",$dstdir."/".$filenamehex);
					// copy($srcdir.$srcfilenm.".xml",$dstdir."/".$filenamehex);

					//insert into Solr
					$data = array(
						"add" => array(
							"doc" => array(
								"id"						=> $lastidxml,
								"id_file_i"				=> $lastidxml,
								"id_radio_i"			=> $rowradio['id_radio'],
								"path_s"				=> $dstdir,
								"filename_s"			=> $filenamehex,
								"filename_hash_s"		=> $filenamehash,
								"type_s"				=> 'MP3',
								"timestamp_i"			=> $filenamets,
								"timestamp_add_i"	=> $nowtimestamp
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

					xmlcontent($lastidxml,$lastidmp3);
				}
			// }
			// else{
			// 	echo "The filename ".$srcfilenm." already exist in database!"."\n";
			// 	// echo "\n";
			// }
		// }
	}

	function xmlcontent ($lastidxml,$lastidmp3) {
		global $dbcon;
		global $protocol;
		global $port;
		global $host;
		$path='/solr/text';
		$url=$protocol."://".$host.":".$port.$path."/update?wt=json";

		$xmlfile = "SELECT * from file where id_file = $lastidxml";
		$resultxmlfile = $dbcon->query($xmlfile);
		$rowxmlfile = $resultxmlfile->fetch_assoc();

		//get the text content from last XML file
		$xmldata = simplexml_load_file($rowxmlfile['path'].'/'.$rowxmlfile['filename']);
		$wid = 0;
		$textcontent = null;
		foreach ($xmldata->StorySegment as $storyseg) {
			foreach ($storyseg->TranscriptSegment as $transcseg) {
				$frstwordstart	= $transcseg->TranscriptWordList->Word[0]['start']-1;
				$guid 			= (string)$transcseg->TranscriptGUID;
				$type 			= $transcseg->AudioType;
				$typestr 		= $type[0];
				$typestartms 	= $type['start'];
				$typestarts 	= $typestartms/100;
				$typeendms 	= $type['end'];
				$typeends 		= $typeendms/100;

				$typeends2	= $frstwordstart/100;
				$typeduration 	= number_format($typeends-$typestarts,3,"."," ");
				$typeduration2 = number_format($typeends2-$typestarts,3,"."," ");

				$speaker		= $transcseg->Speaker;
				$speakerg		= $speaker['name'];

				if (!isset($transcseg->TranscriptWordList->Word)){
				}
				else if (isset($transcseg->TranscriptWordList->Word)) {
					foreach ($transcseg->TranscriptWordList->Word as $transcword) {
						if (empty($transcword['norm'])) {
							$word = $transcword;
						}
						else {
							$word 		= $transcword['norm'];
						}
						$wordstartms	= $transcword['start'];
						$wordstarts	= $wordstartms/100;
						$wordendms	= $transcword['end'];
						$wordends		= $wordendms/100;
						$wordduration	= number_format($wordends-$wordstarts,3,"."," ");
						$wordpunct	= $transcword['punct'];
						$wid++;
						$textcontent .= $word."\x20";
					}
				}
			}
		};

		//insert only the text of the xml file
		// echo "INSERT INTO text (id_file,text_content) VALUES (".$rowxmlfile['id_file'].",'".$textcontent."')"."\n";
		$insertxmltxt = "INSERT INTO text (id_file,id_file_mp3,text_content) VALUES (".$rowxmlfile['id_file'].",".$lastidmp3.",'".utf8_decode(str_replace("'"," ",$textcontent))."')";
		$dbcon->query($insertxmltxt);
		$lastidtext = $dbcon->insert_id;

		//insert into Solr
		$data = array(
			"add" => array(
				"doc" => array(
					"id"						=> $lastidtext,
					"id_file_i"				=> $rowxmlfile["id_file"],
					"id_file_mp3_i" 		=> $lastidmp3,
					"id_text_i"				=> $lastidtext,
					"text_content_txt"		=> $textcontent
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

		//$xmlfilecontent = file_get_contents($rowxmlfile['path'].'/'.$rowxmlfile['filename']);
		//insert all the content of the xml file
		//echo "INSERT INTO text (id_file,text_content) VALUE (".$rowxmlfile['id_file'].",'".$xmlfilecontent."')"."\n";
		//$insertxmlcontent = "INSERT INTO xml (id_file,xml_content) VALUE (".$rowxmlfile['id_file'].",'".utf8_decode($xmlfilecontent)."')"."\n";
		//$dbcon->query($insertxmlcontent);
	}
?>