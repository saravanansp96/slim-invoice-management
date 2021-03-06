<?php 
use Slim\Http\Request;
use Slim\Http\Response;

function readAllProducts($connection) {
	//$connection = $this->getcontainer()->get('dbUtilObject');
	$resultSet = $connection->select("select * from product_table" , []);
	if(isset($resultSet)){
		$jsonData['status'] = "success";
		$jsonData['product'] = $resultSet;
	} else {
		$jsonData['status'] = "failure";
	}
	//$this->getcontainer()->get('dbUtilObject')->closeConnection();
	return json_encode($jsonData);
}

function searchProduct($connection, $args) {
	$bindParams = array();
	$bindParams['product_id'] = intval($args['product-id']);
	$resultSet = $connection->select("select * from product_table where product_id = :product_id",$bindParams);
	if (!isset($resultSet)) {
		$jsonData['status'] = "failure";
		$jsonData['reason'] = "no such product exists";
	} else if(isset($resultSet) && $resultSet != "fail" ) {
		$jsonData['status'] = "success";
		$jsonData['product'] = $resultSet;
	} else {
		$jsonData['status'] = "failure";
		$jsonData['reason'] = "unable to fetch product details";
	}
	//$this->getcontainer()->get('dbUtilObject')->closeConnection();
	return json_encode($jsonData);
}

function deleteProduct($connection,$args) {
	$bindParams = array();
	$bindParams['product_id'] = intval($args['product-id']);
	$resultSet = $connection->tableUpdate("delete from product_table where product_id = :product_id",$bindParams);
	if($resultSet > 0) {
		$jsonData['status'] = "success";
	} else if ($resultSet == 0) {
		$jsonData['status'] = "failure";
		$jsonData['reason'] = "no such product exists";
	} else {
		$jsonData['status'] = "failure";
		$jsonData['reason'] = "unable to delete the product";
	}
	return json_encode($jsonData);
}

function addProduct($connection,$args) {
	$bindParams = array();
	$bindParams['product_name'] = $args['product-name'];
	$bindParams['product_price'] = $args['product-price'];
	$bindParams['product_quantity'] = $args['product-quantity'];
	$bindParams['quantity_type'] = $args['quantity-type'];
	$resultSet = $connection->tableUpdate("insert into product_table (product_name, product_price, product_quantity , quantity_type ) values (:product_name,:product_price,:product_quantity,:quantity_type);",$bindParams);
	if($resultSet > 0) {
		$jsonData['status'] = "success";
	} else if ($resultSet == 0) {
		$jsonData['status'] = "failure";
		$jsonData['reason'] = "error in adding product";
	} else {
		$jsonData['status'] = "failure";
		$jsonData['reason'] = "unable to delete the product";
	}
	return json_encode($jsonData);
}
?>