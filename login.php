<?php
	session_start();
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
    <div class="container">
	<img src="back.jpg" class="watermark" />
    <form name="login_form" action="login.php" method="POST">
      <h2>Login</h2>
	<?php
	$form = <<<EOFORM
      <label>Username:</label><br />
      <input type="text" name="user" value="username" /><br />
      <label>Password:</label><br />
      <input type="password" name="pass" value="password" /><br />
      <input type="submit" name="submit" value="Sign In" />
EOFORM;
	if(isset($_SESSION['pass']) && isset($_SESSION['user'])) {
		echo "<label>Re-directing to general chat ... click <a href=\"index.php?room=general\">here</a> if that doesn't work.</label>";
		header("Location: index.php?room=general");
		exit();
	}
	else echo $form;
	if( isset($_POST['user']) && isset($_POST['pass']) ) {
		echo "<br /><br />";
		$f=fopen("users.txt","r") or die("</label>Sorry, can't login now.</label>");
		while ( ($buf=fgets($f,1024)) != FALSE ) {
			$data = explode(" ",$buf);
			if( strtolower($data[0])===strtolower($_POST['user']) && $data[1]===md5($_POST['pass']) ) {
				echo "</label>Re-directing, please wait... <br /></label>";
				$_SESSION['user']=$_POST['user'];
				$_SESSION['pass']=$_POST['pass'];
				$_SESSION['country']=$data[2];
				$_SESSION['auth']=1;
				header("Location: login_check.php");
				exit();
			}
		}
		echo "<label>Invalid username/password! <br /></label>";
		fclose($f);
	}
	?>
    </form>
    <center class="footer"><a href="register.php">Register</a></center>
    </div>
  </body>
</html>
