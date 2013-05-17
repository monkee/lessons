<?php

//include '../shadow/Shadow.php';
//文件是UTF-8编码的
$db = new mysqli('127.0.0.1', 'root', '', 'lesson', 3306);
$db->set_charset('UTF8');
$ret = getResult('show variables like "character%"', $db);
print_r($ret);

$ret = getResult('select * from charset', $db);
print_r($ret);



function getResult($sql, $db){
	$query = $db->query($sql);
	if(empty($query)){
		echo $db->error;
		return false;
	}
	$ret = array();
	while($row = $query->fetch_assoc()){
		$ret[] = $row;
	}
	return $ret;
}