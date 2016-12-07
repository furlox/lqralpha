<?php
  session_start();
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script type="text/javascript">
		function test(){
			document.register.flag.src="flags/"+document.register.country.value;
		}
	</script>
    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
    <div class="container">
	<img src="back.jpg" class="watermark" />
    <form name="register" action="register.php" method="POST">
      <h2>Register</h2>
	<?php
	$_POST['user']=preg_replace("/[^A-Za-z0-9 ]/", '', $_POST['user']);
	$form = <<<EOFORM
      <label>Username:</label><br />
      <input type="text" name="user" value="username" /><br />
      <label>Password:</label><br />
      <input type="password" name="pass" value="password" /><br />
	  <label>Re-type:</label><br />
	  <input type="password" name="pass2" value="password" /><br />
	  <label>Pick your country. Must be here somewhere.</label><br />
	  <select name="country" onchange="javascript:test();">
EOFORM;


	$html2 = <<<HERE2
    </form>
	<center class="footer"><a href="login.php">Login</a></center>
    </div>
  </body>
</html>
HERE2;
	if(isset($_SESSION['pass']) && isset($_SESSION['user']) ) {
		echo "<label>Re-directing to general chat ... click <a href=\"index.php?room=general\">here</a> if that doesn't work.</label>";
		header("Location: index.php?room=general");
		exit();
	}
	else {
		echo $form;
		$flag=scandir("flags/");
		foreach($flag as $icon) {
			if($icon==="." || $icon==="..") continue;
			echo '<option value="'.$icon.'">'.$icon.'</option>';
		}
		echo '</select><img src="" name="flag" id="formflag"/><br /><label>And.. you\'re done?</label><input type="submit" name="submit" value="Create User" />';
	}
	if( isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['pass2']) && isset($_POST['country'])) {
		echo "<br /><br />";
		$f=fopen("users.txt","r") or die("Sorry, can't register now.");
		while ( ($buf=fgets($f,1024)) != FALSE ) {
			$data = explode(" ",$buf);
			$_POST['user']=preg_replace('/\s+/', '', $_POST['user']);
			if( empty($_POST['user']) || empty($_POST['pass']) || empty($_POST['pass2']) || empty($_POST['country'])) {
				echo "<label>Missing fields :(</label><br />";
				fclose($f);
				echo $html2;
				die();
			}
			if( $data[0]===$_POST['user'] ) {
				echo "<label>Username is taken, sorry.</label><br />";
				fclose($f);
				echo $html2;
				die();
			}
			if( ! ($_POST['pass']===$_POST['pass2']) ) {
				echo "<label>Passwords don't match</label><br />";
				fclose($f);
				echo $html2;
				die();
			}
		}
		fclose($f);
		$username=$_POST['user'];
		$password=$_POST['pass'];
		$country=$_POST['country'];
		file_put_contents("users.txt","\n".$username." ".md5($password)." ".$country." 0", FILE_APPEND | LOCK_EX);
		mkdir(getcwd()."/subrooms/".$username."/",0777, $recursive=true) or die("KAPPA");
		touch("subrooms/".$username."/roomlist.txt");
		echo '<label>Success! You\'ve been registered. <a href="login.php">Login</a></label>';
		$_SESSION['user']=$username;
		$_SESSION['pass']=$password;
		$_SESSION['country']=$country;
		exit();
	}
	echo $html2;
?>
