<?php

	$sourcedir = '/mnt/';
	$destdir = '/app/application/repository/01_files/';

	$leveldir01 = 32;
	$leveldir02 = 64;
	$filespdir = 1000;
	$totalfiles = $leveldir02*$leveldir01*$filespdir;

	echo 'Dir quantity of first level: '.$leveldir01."\n";
	echo 'Dir quantity of second level: '.$leveldir02."\n";
	echo "Total Files: ".$totalfiles."\n";
	echo "\n";

	//sleep(5);

	$fptotal='-1';
	//creates directories and the levels
	for ($ld01=0; $ld01 < $leveldir01; $ld01++) {
		echo "\n";

		//convert decimal to hexadecimal
		$ldhex01=str_pad(strtoupper(dechex($ld01)),2,0,STR_PAD_LEFT);

		//verify if folder exist. If not, create with name in hex
		if (!is_dir($destdir.$ldhex01)) {
			echo '|-Creating Dir '.$destdir.$ldhex01."\n";
			mkdir($destdir.$ldhex01);
		}
		//else if the dir exists, display the messsage
		elseif (is_dir($destdir.$ldhex01)) {
			echo 'Dir '.$ldhex01.' already exits!'."\n";
		}

		for ($ld02=0; $ld02 < $leveldir02; $ld02++) {

			//convert decimal to hexadecimal with two characteres
			$ldhex02=str_pad(strtoupper(dechex($ld02)),2,0,STR_PAD_LEFT);

			//check if the dir exists. If not, create with name in hex
			if (!is_dir($destdir.$ldhex01.'/'.$ldhex02)) {
				echo '  |--Creating Dir '.$destdir.$ldhex01.'/'.$ldhex02."\n";
				mkdir($destdir.$ldhex01.'/'.$ldhex02);
			}
			//else if the dir exists, display the messsage
			elseif (is_dir($destdir.$ldhex01.'/'.$ldhex02)) {
				echo 'Dir '.$ldhex02.' already exists!'."\n";
			}

			/*for ($fpdir=0; $fpdir < $filespdir ; $fpdir++) {
				$fptotal++;
				$fpdhex=str_pad(strtoupper(dechex($fptotal)),8,0,STR_PAD_LEFT);
				echo "    |----Creating file ".$destdir.$ldhex01.'/'.$ldhex02.'/'.$fpdhex."\n";
			}*/

		}

	}

?>