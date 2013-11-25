<?php
function getdb(){	
	$db = new mysqli("127.0.0.1", "root", "");
	$db->select_db('rsid');
	return $db;
}

// add double quote
function adq($q){
	return '"'.$q.'"';
}
// add single quote
function asq($q){
	return "'".$q."'";
}

// get users
function getUsers($userName, $passWords){
	$db = getdb();
	$userNameStr = adq($userName);
	$passWordsStr = adq(sha1($passWords));
	
	$sqlp = array(
		'select users.id as ID, users.nickname as NAME from users',
		'where users.name='.$userNameStr,
		'and users.password='.$passWordsStr
	);
	$sql = join(' ', $sqlp);
	
	$users = $db->query($sql);
	return $users;
}

// validate user
function validateUser($userName, $passWords){
	$rst = getUsers($userName, $passWords);
	if($rst->num_rows>0){
		return true;
	}
	else{
		return false;
	}
}
?>