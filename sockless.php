<?php
header("Content-Type: text/event-stream");
header('Cache-Control: no-cache');
if(!isset($_GET['channel']) || $_GET['channel'] == "") exit;
$prev = file_get_contents('cache/channels/'.$_GET['channel'].'/buffer');
$prev;
while (1) {
	$now = file_get_contents('cache/channels/'.$_GET['channel'].'/buffer');
	if ($prev != $now) {
		$new = substr($now, strlen($prev));
		$new = explode("\n", $new);
		foreach ($new as $message) {
			if($message != ""){
				echo "event: message\n", 'data: '.$message, "\n\n";
			}
			ob_flush();
			flush();
		}		
	  $prev = $now;
	}
    sleep(1);
}