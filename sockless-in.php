<?php
$buffer = file_get_contents('php://input'); 
$data = json_decode(base64_decode($buffer), true);

if((!isset($data['channel'])) || $data['channel'] == "") exit;
if(!is_dir('cache/channels/'.$data['channel'])){
	mkdir('cache/channels/'.$data['channel']);
}

file_put_contents('cache/channels/'.$data['channel'].'/buffer', $buffer."\n", FILE_APPEND);
