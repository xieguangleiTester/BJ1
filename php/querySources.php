<?php
require 'rsiddb.php';

function queryResouces($dateFrom, $dateTo, $lon0, $lon1, $lat0, $lat1){
	$dateFrom = adq($dateFrom);
	$dateTo = adq($dateTo);
	
	$sqlp = array(
		'select * from sources',
		'where date between '.$dateFrom.' and '.$dateTo,
		'and (left_lon+right_lon)/2 between '.$lon0.' and '.$lon1,
		'and (high_lat+low_lat)/2 between '.$lat0.' and '.$lat1
	);
	
	$sql = join(' ', $sqlp);
	
	$db = getdb();
	$rslt = $db->query($sql);
	return $rslt;
}

if(validateUser($_GET['userName'], $_GET['passWords'])){
	$rslt = queryResouces($_GET['date0'], $_GET['date1'], $_GET['lon0'], $_GET['lon1'], $_GET['lat0'], $_GET['lat1']);
	
	$images = array();
	while(($row = $rslt->fetch_assoc())!=NULL)
	{
		$image = array(
	 		'id'=>$row['id'],
	 		'date'=>$row['date'],
			'name'=>$row['name'],
	 		'midLon'=>($row['left_lon']+$row['right_lon'])/2,
	 		'midLat'=>($row['high_lat']+$row['low_lat'])/2,
			'leftLon'=>$row['left_lon'],
			'rightLon'=>$row['right_lon'],
			'upLat'=>$row['high_lat'],
			'lowLat'=>$row['low_lat'],
			'thumbPath'=>$row['thumb'],
			'dataPath'=>$row['source']
	 	);
	 	array_push($images, $image);
	}
	echo json_encode($images);
}
?>