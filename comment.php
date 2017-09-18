<?php

$db_server = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "test";

$limit = 5;

$mysqli = new mysqli($db_server, $db_user, $db_password, $db_name);
$result = new stdClass();

if($_POST['action']=='add'){
	$name    = $mysqli->real_escape_string($_POST['name']);
	$email   = $mysqli->real_escape_string($_POST['email']);
	$comment = $mysqli->real_escape_string($_POST['comment']);
	if(!empty($name) && !empty($comment) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
		$mysqli->query("INSERT INTO comments (name, email, comment) VAlUES ('$name', '$email', '$comment')");
	}else{
		$result->error = "Заполните все поля формы";	
	}
}
if($_POST['action']=='get'){
	$last = intval($_POST['last']);
	$where = '';
	if(!empty($last)){
		$where = "WHERE id<$last";
	}
	$mysql_result = $mysqli->query("SELECT id, name, email, comment, date_format(created, '%d.%m.%Y в %H:%i') as 'created' FROM comments $where ORDER BY id DESC LIMIT $limit");
	$result->comments = mysqli_fetch_all($mysql_result,MYSQLI_ASSOC);
	$mysql_result = $mysqli->query("SELECT count(*) as 'count' FROM comments $where");
	$result->more = (intval(mysqli_fetch_object($mysql_result)->count)-$limit)>0?1:0;
}


header("Content-type: application/json; charset=UTF-8");
header("Cache-Control: must-revalidate");
header("Pragma: no-cache");
header("Expires: -1");		
print json_encode($result);