<?php
	$clearbuffer = $_GET['clearbuffer'];
	if($clearbuffer=="0")
	{
		$file = "clearbuffer.txt";
		$open = fopen($file, "w");
		fwrite($open, "0");
		fclose($open);
		header('Location: chat.php');
		return;
	}
	else if($clearbuffer=="1")
	{
		$file = "clearbuffer.txt";
		$open = fopen($file, "w");
		fwrite($open, "1"); // Bufferin voi poistaa.
		fclose($open);
		header('Location: chat.php');
		return;
	}
	else if($clearbuffer=="2")
	{
		$file = "chatbuffer.txt";
		$open = fopen($file, "w");
		fwrite($open, ""); // Tyhjennä chatbuffer.txt
		fclose($open);
		header('Location: chat.php');
		return;
	}

	$chat = $_GET['msg'];
	if($chat=='')
	{
		header('Location: chat.php');
		return;
	}
	$file = "chatbuffer.txt";
	$open = fopen($file, "a");
	$read = fread($open, filesize($file));
	fwrite($open, $read . $chat . PHP_EOL);
	fclose($open);
	header('Location: chat.php');

	$file2 = "chat.txt";
	$open2 = fopen($file2, "a");
	$read2 = fread($open2, filesize($file2));
	fwrite($open2, $read2 . $chat . PHP_EOL);
	fclose($open2);
	header('Location: chat.php')
?>
