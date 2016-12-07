<?php
	$f=fopen("alive.txt","r") or die("cant");
	echo '<li class="roomheader"><b>Online</b></li>';
	while ( ($buf=fgets($f)) != FALSE ) {
		$dat=explode(" ", $buf);
		echo '<li><img class="flag" src="flags/'.$dat[1].'" />'.$dat[0].'</li>';
	}
?>
