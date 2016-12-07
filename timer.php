<?php
	if( !isset($_GET['user']) || !isset($_GET['flag'])) exit();
	if( $_GET['clear']==="1" ) {
		$contents = file_get_contents("alive.txt");
		$contents = str_replace($_GET['user']." ".$_GET['flag']."\r\n", '', $contents);
		$contents = str_replace($_GET['user']." ".$_GET['flag']."\n", '', $contents);
		file_put_contents("alive.txt", $contents);
		exit();
	}
	$g = fopen("alive.txt", "r") or die("cannot open file");
	$found = 0;
	while( ($buf=fgets($g)) != FALSE ) {
		echo $buf." ".$_GET['user']."<br />";
		if ($buf===$_GET['user']." ".$_GET['flag']."\n") {
			$found=1;
			break;
		}
	}
	fclose($g);
	if(!$found) {
		$f=fopen("alive.txt","a") or die("cant open file");
		fputs($f, $_GET['user']." ".$_GET['flag']."\n");
	}
?>
	