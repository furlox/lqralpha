<?php
	if( !isset($_COOKIE['user']) || !isset($_COOKIE['pass']) ) {
		header("Location: login.php");
		exit();
	}
	$f=fopen("users.txt","r") or die("Internal Server Error");
	while( ($buf=fgets($f)) !=FALSE) {
		$dat=explode(" ",$buf);
		if($dat[0]===$_COOKIE['user'] && $dat[1]===$_COOKIE['pass']) {
			header("Location: index.php");
			exit();
		}
	}
	die("Error: Invalid credentials");
?>
