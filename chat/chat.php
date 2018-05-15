<!DOCTYPE html>
<html lang="fi_FI">
<head>
<title>Testi</title>
<meta charset="utf-8">
</head>

<body>
<?php
	//<meta http-equiv="refresh" content="4">
	echo '<input name="msg" type="text" id="chatmsg"/>';
	echo '<button name="submit" id="submitbutton">Lähetä</button>';
	echo '<br>';
	echo '<p> Server buffer: <span id="serverbuffer"> </span> </p>';
	echo '<p> Client buffer: <span id="clientbuffer"> </span> </p>';
	echo '<p> Length: <span id="bufferlength"> </span> </p>';
	echo '<p> Clear: <span id="serverbufferclear"> </span> </p>';
	echo '<textarea id="chatbox" rows="40" cols="200" style="overflow:auto;resize:none"></textarea>';

	echo '<script type="text/javascript", src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>';
	echo '<script type="text/javascript">
	$(document).ready(function()
	{
		//$.ajax({
		//	url: "chat.php",
		//	context: document.body,
		//	success: function(){
		//		// a
		//	}
		//});

		var alltext = "";
		var bufferlength = 0;
		var buffercleartime = 0;
		var load = false;
		var send = false;
		var chatbox = document.getElementById("chatbox");

		var sendchatrequest = new XMLHttpRequest();
		var clearbufferrequest = new XMLHttpRequest();
		var chatbufferrequest = new XMLHttpRequest();
		var chatrequest = new XMLHttpRequest();
		
		function clearBuffer()
		{
			var xml = clearbufferrequest;
			xml.open("GET", "clearbuffer.txt"); // URL osoite clearbuffer.txt
			xml.onload = function()
			{
				var text = xml.responseText;
				if(text=="1" || bufferlength>=128) // Bufferin voi poistaa.
				{
					var xml2 = new XMLHttpRequest();
					xml2.open("GET", "sendchat.php?clearbuffer=2"); // Tyjennä chatbuffer.txt
					xml2.send();
				}
			}
			xml.send(null);
		}

		function updateText()
		{
			var xml = chatrequest;
			xml.open("GET", "chat.txt"); // URL osoite chat.txt
			xml.onload = function()
			{
				var text = xml.responseText;
				alltext = alltext + text;
				document.getElementById("chatbox").innerHTML = alltext; // Näkyvä teksti.
				bufferlength = text.length;
				load = true;
			}
			xml.send(null);
		}

		function scrollDownChatBox()
		{
			chatbox.scrollTop = chatbox.scrollHeight;
		}

		function scrollUpdateChatBox()
		{
			if(chatbox.scrollTop>=(chatbox.scrollHeight-768))
				chatbox.scrollTop = chatbox.scrollHeight;
		}

		function updateTextBuffer()
		{
			var xml = chatbufferrequest;
			xml.open("GET", "chatbuffer.txt"); // URL osoite chatbuffer.txt
			xml.onload = function()
			{
				var text = xml.responseText;
				var text2 = text.slice(bufferlength);
				document.getElementById("serverbuffer").innerHTML = text;
				document.getElementById("clientbuffer").innerHTML = text2;
				if(text2!="")
				{
					alltext = alltext + text2;
					document.getElementById("chatbox").innerHTML = alltext; // Näkyvä teksti.
					scrollUpdateChatBox();
				}
				bufferlength = text.length;
				document.getElementById("bufferlength").innerHTML = bufferlength;
			}
			xml.send(null);
		}

		setInterval(function()
		{
			if(load==true)
				updateTextBuffer(); // Lisää bufferoidut viestit.

			document.getElementById("serverbufferclear").innerHTML = buffercleartime;
			if(send==true) // Bufferin tyhjennys.
			{
				buffercleartime = buffercleartime + 1;
				if(buffercleartime==24)
				{
					var xml = new XMLHttpRequest();
					xml.open("GET", "sendchat.php?clearbuffer=1"); // Bufferin voi poistaa.
					xml.send();
				}
				else if(buffercleartime==32)
				{
					clearBuffer(); // Poista buffer.
					buffercleartime = 0;
					send = false;
				}
			}
		}, 500); // Päivitä chat joka 0.5 sekuntti.

		$("#submitbutton").click(function(event) // Lähetä painike klikkaus.
		{
			if(load==false) return;
			var msg = document.getElementById("chatmsg").value; // Ota viesti.
			//buffer = buffer + msg . PHP_EOL;
			//var xml = new XMLHttpRequest();

			sendchatrequest.open("GET", "sendchat.php?msg="+msg); // Lähetä viesti.
			sendchatrequest.send();
			updateTextBuffer(); // Lisää bufferoidut viestit.
			document.getElementById("chatmsg").value = "";

			clearbufferrequest.open("GET", "sendchat.php?clearbuffer=0"); // Bufferia ei voi poistaa.
			clearbufferrequest.send();

			send = true;
			scrollDownChatBox();	
		});
		updateText(); // Näytä chat.txt sisältö.
		scrollDownChatBox();
	});
	</script>';
?>
</body>
</html>