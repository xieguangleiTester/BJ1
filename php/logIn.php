<?php
require 'rsiddb.php';

$userName = $_GET["userName"];
$passWords = $_GET["passWords"];

if(validateUser($userName, $passWords)){
	$userS = getUsers($userName, $passWords);
	$userRow = $userS->fetch_assoc();

	$obj = array(
		'userNickName'=>$userRow['NAME']
	);
	echo json_encode($obj);
}
else{
	echo "RSIDERROR";
}


?>