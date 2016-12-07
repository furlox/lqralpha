<?php
	if(!isset($_GET['user']) || !isset($_GET['msg']) || !isset($_GET['pass']) || !isset($_GET['room'])) exit();
	if(strlen($_GET['msg'])==0) exit();
	$room=$_GET['room'];
	if($_GET['room'][0]==='@') {
		$room=$_GET['room']="subrooms/".substr($_GET['room'],1);
	}
	echo $_GET['room'];
	$g=fopen("users.txt", "r") or die("WTF");
	$k=fopen($_GET['room']."____auth.txt", "r") or die("WTF");
	$authlevel=trim(fgets($k));
	$speaker=trim(fgets($k));
	if($_GET['msg']==="/auth0" || $_GET['msg']==="/auth1" || substr($_GET['msg'],0,8)==="/speaker" || $_GET['msg']==="/nospeaker") {
		while ( ($buf=fgets($g)) !=FALSE ) {
			$dat=explode(" ",$buf);
			if($dat[0]===$_GET['user'] && $dat[1]===$_GET['pass'] && $dat[3]==1) {
				if($_GET['msg']==="/auth0") file_put_contents(pathinfo($_GET['room'], PATHINFO_FILENAME)."____auth.txt","0", LOCK_EX);
				else if($_GET['msg']==="/auth1") file_put_contents(pathinfo($_GET['room'], PATHINFO_FILENAME)."____auth.txt","1", LOCK_EX);
				else if(substr($_GET['msg'],0,8)==="/speaker"){
					file_put_contents($room."____auth.txt",$authlevel."\n".substr($_GET['msg'],9), LOCK_EX);
				}
				else file_put_contents($room."____auth.txt",$authlevel, LOCK_EX);
				file_put_contents($room.'.txt', "\n<div class=\"alert\"><b>".gmdate('Y-m-d h:i:s \G\M\T').'</b> [NOTICE] Room has been set in '.$_GET['msg'].' mode by '.$_GET['user'].". </div>", FILE_APPEND | LOCK_EX);
				file_put_contents($room.'_md5.txt', hash_file('md5', $room.'.txt'), LOCK_EX);
				fclose($g);
				fclose($k);
				exit();
			}
		}
		file_put_contents($room.'.txt', "\n<div class=\"alert\">".'<b>'.gmdate('Y-m-d h:i:s \G\M\T').'</b> [WARNING] '.$_GET['user'].' '.' tried to '.$_GET['msg'].", but is not authorized.</div>", FILE_APPEND | LOCK_EX);
		file_put_contents($room.'_md5.txt', hash_file('md5',$room.'.txt'),LOCK_EX);
	}
	else{
		while ( ($buf=fgets($g)) !=FALSE ) {
			$dat=explode(" ",$buf);
			if($dat[0]===$_GET['user'] && $dat[1]===$_GET['pass'] && ( (empty($speaker)) || $speaker===$_GET['user']) ) {
				$msg=htmlspecialchars($_GET['msg']);
				$msg=str_replace("}{",'" target="_blank">', $msg);
				$msg=str_replace("{",'<a href="http://', $msg);
				$msg=str_replace("}","</a>",$msg);
				$msg=str_replace("[[[",'<img src="', $msg);
				$msg=str_replace("]]]",'"></img>', $msg);
				file_put_contents($room.'.txt', "\n<div class=\"chatmsg\">".'<b class="time">'.gmdate('Y-m-d h:i:s \G\M\T').'</b><img class="flag" src="flags/'.$_GET['country'].'" /><label>'.$_GET['user'].'</label><br />'.$msg."</div>", FILE_APPEND | LOCK_EX);
				file_put_contents($room.'_md5.txt', hash_file('md5',$room.'.txt'), LOCK_EX);
				fclose($g);
				fclose($k);
				exit();
			}
		}
	}

	fclose($g);
	fclose($k);
?>
	
