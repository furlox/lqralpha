var ws_arr=new Object();
var ws = require("nodejs-websocket");
//
var server = ws.createServer( function(conn)  {
	conn.on("connection", function(conn) {
		console.log("New connection established");
	});
	
	conn.on("text", function(str) {
		console.log("Received: "+str);
		var arr = str.split(" ");
		if(arr[0]==="keepalive") {
			console.log("User "+arr[1]+" trying to stay online...");
			ws_arr[arr[1]]=conn;
		}
		if(arr[0]==="subroom") {
			console.log("subroom request");
			var fs=require('fs');
			fs.open("subrooms/"+arr[1]+"/"+arr[2]+".txt", "wx", function(err){console.log(err);});
			fs.chmod("subrooms/"+arr[1]+"/"+arr[2]+".txt",0777);
			fs.open("subrooms/"+arr[1]+"/"+arr[2]+"_md5.txt","wx",function(err){console.log(err);});
			fs.appendFile("subrooms/"+arr[1]+"/"+arr[2]+"_md5.txt","d41d8cd98f00b204e9800998ecf8427e");
			fs.chmod("subrooms/"+arr[1]+"/"+arr[2]+"_md5.txt",0777);
			fs.open("subrooms/"+arr[1]+"/"+arr[2]+"____auth.txt", "wx", function(err){console.log(err);});
			fs.chmod("subrooms/"+arr[1]+"/"+arr[2]+"____auth.txt",0777);
			fs.appendFile("subrooms/"+arr[1]+"/"+arr[2]+"____auth.txt","0");
			fs.appendFile("subrooms/"+arr[1]+"/roomlist.txt","@"+arr[1]+"/"+arr[2]+"\n");
			conn.sendText("updateroomlist");
		}
		if(arr[0]==="invite") {
			try{
				console.log("subroom invite");
				var fs=require('fs');
				fs.appendFile("subrooms/"+arr[2]+"/roomlist.txt",arr[3]+"\n",function(err){console.log(err);});
				if(ws_arr[arr[2]] == null) 
					console.log("that person is offline");
				else ws_arr[arr[2]].sendText("updateroomlist");
			}
			catch(err) { console.log(err.message);}
		}
	});
	conn.on("close", function(code, reason) {
		console.log("Connection of "+conn.userid+" was closed");
		ws_arr[conn.userid]=null;
	});
}).listen(process.argv[2]);
