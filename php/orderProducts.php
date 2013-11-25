<?php
require 'rsiddb.php';

$userName = $_GET['userName'];
$passWords = $_GET['passWords'];

function getProduct($orders, $userId){
	$db = getdb();

	
	for($i=0; $i<count($orders); $i++){
		$sql = "select * from products where type=".$orders[$i]->type." and idsource=".$orders[$i]->sourceId;
		$res = $db->query($sql);
		
		if(!($row=$res->fetch_assoc())){
			// new order
			$sql = "select * from sources where id=".$orders[$i]->sourceId;
			$res = $db->query($sql);
			$row = $res->fetch_assoc();
			
			$sourcepath = "../".$row['source'];
			$productpath = "../data/product/".substr($row['source'], 12, strlen($row['source'])-12-4)."-".$orders[$i]->type.".tif";
			$thumbpath = "../data/thumb/".substr($row['source'], 12, strlen($row['source'])-12-4)."-".$orders[$i]->type.".jpg";
			
			$sqlp = array(
				'insert into products (type, product, thumb, idsource, done) values (',
				$orders[$i]->type.',',
				asq(substr($productpath, 3)).',',
				asq(substr($thumbpath, 3)).',',
				$row['id'].',',
				0,
				');',
			);
			$db->query(join(' ', $sqlp));
		}
		else{
			// existing order : Nothing to do
		}
	}


	
	// for all orders
	$sqlp_1 = array(
		'create temporary table tpOrder',
		'(sourceId varchar(11), type int)',
		';'
	);
	
	$sqlp_2 = array(
		'insert into tpOrder (sourceId, type) values'
	);
	for($i=0; $i<count($orders); $i++){
		if($i!=0){
			array_push($sqlp_2, ',');
		}
		array_push($sqlp_2, '('.($orders[$i]->sourceId).', '.($orders[$i]->type).')');
	}
	array_push($sqlp_2, ';');
	
	$sqlp_3 = array(
		'select products.id as IDPRODUCT, products.type as TYPE, sources.id as IDSOURCE, sources.date as DATE,',
		'sources.left_lon as LTLN, sources.right_lon as RTLN, sources.low_lat as LWLT, sources.high_lat as HGLT,',
		'products.product as PRODUCT, products.thumb as THUMB',
		'from tpOrder, sources, products',
		'where tpOrder.sourceId = sources.id',
		'and tpOrder.type = products.type',
		'and products.idsource = sources.id',
		';'
	);
	
	$db->query(join(' ', $sqlp_1));
	$db->query(join(' ', $sqlp_2));
	$historyOrders = $db->query(join(' ', $sqlp_3));
	//return join(' ', $sqlp_1).join(' ', $sqlp_2).join(' ', $sqlp_3);
	
	$h = array();
	while(($row = $historyOrders->fetch_assoc())){
		array_push(
			$h,
			array(
				'IDPRODUCT'=>$row['IDPRODUCT'],
				'IDSOURCE'=>$row['IDSOURCE'],
				'TYPE'=>$row['TYPE'],
				'DATE'=>$row['DATE'],
				'LTLN'=>$row['LTLN'],
				'RTLN'=>$row['RTLN'],
				'LWLT'=>$row['LWLT'],
				'HGLT'=>$row['HGLT'],
				'PRODUCT'=>$row['PRODUCT'],
				'THUMB'=>$row['THUMB']
			)
		);
	}
	
	$db->query('drop table tpOrder;');
	return $h;
}

function insertExistingOrders($userId, $sourceId, $type, $productId){
	$sqlp = array(
		'insert into orders(idusers, idsources, type, done, idproduct)',
		'values ('.$userId.','.$sourceId.','.$type.',1,'.$productId.')'
	);
	$db = getdb();
	$db->query(join(' ', $sqlp));
}

function insertNewOrders($userId, $sourceId, $type){
	$sqlp = array(
		'insert into orders(idusers, idsources, type, done, idproduct)',
		'values ('.$userId.','.$sourceId.','.$type.',0,NULL)'
	);
	$db = getdb();
	$db->query(join(' ', $sqlp));
}

if(validateUser($userName, $passWords)){
	
	$users = getUsers($userName, $passWords);
	$user = $users->fetch_assoc();
	$userId = $user['ID'];
	
	$orders = json_decode($_GET['orders']);
	$product = getProduct($orders, $userId);
	echo json_encode($product);
	
}

?>