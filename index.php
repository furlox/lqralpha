<!DOCTYPE HTML>
<?php
	session_start();
	if(!isset($_SESSION['user'])) {
		header("Location: login.php");
		exit();
	}
?>
<html>
  <head>
    <meta charset="utf-8" />
	<!-- This site is best viewed on a 15'' Retina MacBook display;
	much like most other sites on the internet. Donate a Mac today,
	so the creators can view their site in all its glory ! -->
    <link rel="stylesheet" type="text/css" href="style.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script type="text/javascript">
			var skip=0,skipsave=0;
			$.ajaxSetup({cache: false});
			var chatbox;
			var beep = new Audio('steambeep.mp3'); // copyright infringement waiting to happen
			var room = "<?php if(isset($_GET['room'])) echo $_GET['room']; else echo "general"; ?>";
			$(document).ready( function() {
								// This website is free to use, and the code
				// on it is so bad that the programmers didn't
				// even bother to copy paste a license so they
				// could look cool. Doesn't even use multiline
				// comments, for Bob's sake.
				
				/* If you're a 1337 hax0r here to fuk our
				// shit up - welp :*/
				
				// Bob sometimes messes up her pronouns.
				var sock = new WebSocket("ws://"+window.location.host+":9003");
				console.log("ws://"+window.location.host+":9003");
				
				$(window).on('beforeunload', function(){
					$.get("timer.php?user=<?php echo $_SESSION['user'] ?>&flag=<?php echo $_SESSION['country'];?>&clear=1");
					sock.close();
				});			
				
				sock.onopen = function (event) {
					console.log("sock opened");
					sock.send("keepalive <?php echo $_SESSION['user']?>");
					setInterval( function() {
						$.get("timer.php?user=<?php echo $_SESSION['user'] ?>&flag=<?php echo $_SESSION['country'];?>");
						$.get("alive.php", function(data) {
							$(".onlinelist").html(data);
						});

					}, 1000); // <--- Change this value to DoS the server.
				};
				sock.onmessage = function(event) {
					str=event.data;
					if(str==="updateroomlist") {
						console.log("new subroom for us");
						location.reload(true);
					}
				};
				$.get("alive.php", function(data) {
					$(".onlinelist").html(data);
				});
				var old="";
				/* NOTE : --- somewhere around here, the programmer (Bob)
				ran out of fucks to give ---- */
				setInterval( function() {
					room2=room;
					if(room[0]==='@') room2="subrooms/"+room.substring(1)+"_md5.txt";
					else room2=room+"_md5.txt";
					$.get(room2, function(data, status) {
						if(status==="success") {
							// Bob finally got something right
							if(old === "" || !(old===data)) {
								room2=room2.replace("_md5","");
								$.get(room2, function(data,status) {
									if(status==="success") {
										skipsave=data.length;
										document.getElementById("chatarea").innerHTML=data.substring(skip);
										beep.play();
										document.title=data.substr(data.lastIndexOf("</label>")+8).replace("</div>","").replace("<br />", "");
										//window.scrollTo(0,document.body.scrollHeight);
									}
								});
							}
							old=data;
						}
					});
				}, 500); // Bob is certain this is a really neat piece of programming
				
				$.get(room+'.txt',function(data){
					document.getElementById("chatarea").innerHTML=data;
					window.scrollTo(0,document.body.scrollHeight);
				});
				
				// Bob knows when you tap dat entah.
				$("#usermsg").keyup(function (e) {
					uri="post.php?msg="+document.getElementById("usermsg").value+"&user=<?php echo $_SESSION['user']?>&pass=<?php echo md5($_SESSION['pass'])?>&room="+room+"&country=<?php echo $_SESSION['country']; ?>";
					
					if (e.which == 13) {
						msg=document.getElementById("usermsg").value;
						if(msg.substring(0,8)==="/subroom") {
							sock.send("subroom <?php echo $_SESSION['user']?> "+msg.substr(9));
							document.getElementById("usermsg").value="";
							window.location="index.php?room=@<?php echo $_SESSION['user']?>/"+msg.substr(9);
							return;
						}
						if(msg.substring(0,7)==="/invite") {
							sock.send("invite <?php echo $_SESSION['user']?> "+msg.substr(8)+" "+room);
							document.getElementById("usermsg").value="";
							return;
						}
						room3=room;
						if(room[0]==='@') room3="subrooms/"+room.substring(1)+".txt";
						else room3=room+".txt";
						window.scrollTo(0, document.body.scrollHeight);
						$.get(uri, function(data, status) {
							if(status==="success") {
								$.get(room3, function(data) {
									document.getElementById("chatarea").innerHTML=data;
									document.getElementById("usermsg").value=""
									window.scrollTo(0,document.body.scrollHeight);
								});
							}
						});
					}
				});
			});
			// BOB WILL BE BACKKkkkk......
		</script>
  </head>
  <body>
		<div class="roomlist">
			<?php
				echo '<a target="index.php?room=general" id="general" href="index.php?room=general">General</a>';
				$f=fopen("subrooms/".$_SESSION['user']."/roomlist.txt","r");
				while( ($buf=fgets($f)) !=FALSE ) {
					$buf=trim($buf);
					echo '<a target="index.php?room='.$buf.'" id="'.$buf.'" href="index.php?room='.$buf.'">'.$buf.'</a>';
				}
			?>
		</div>
		<div class="container">
  			<img src="back.jpg" class="watermark" />
			<div id="chatarea"></div>
			
			<input type="text" id="usermsg" />
			<center class="footer"><a href="logout.php">Leave</a></center>
		</div>
		<div class="onlinelist">ABCDEF</div>
		<div style="clear: both;"></div>
	</body>
</html>
