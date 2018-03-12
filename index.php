<?php
header("connection-type: application/json");

$response = $_SERVER['REQUEST_METHOD'];

switch ($response){
	case 'GET':
	getMethod();
	break;
	
	case 'PUT':
	$data = json_decode(file_get_contents('php://input'), true);
	updateMethod($data);
	break;
	
	case 'POST':
	$data = json_decode(file_get_contents('php://input'), true);
	postMethod($data);
	break;
	
	case 'DELETE':
	$data = json_decode(file_get_contents('php://input'), true);
	deleteMethod($data);
	break;
	
	default:
	echo 'Other';
	break;

}

function getMethod(){
	include "db.php";
	$sql = "SELECT * FROM tbl";
	$result = mysqli_query($conn, $sql);
	$rows = array();
	if (mysqli_num_rows($result) > 0) {
		while ($r = mysqli_fetch_assoc($result)) {
			$rows['result'][] = $r;
		}
		echo json_encode($rows);
	}else{
		echo '{"result" : "No data found!"}';
	}
}

function postMethod($data){
	include "db.php";
	$name = $data['name'];
	$email = $data['email'];

	if (!empty($name) && !empty($email) ) {
		$sql = "INSERT INTO tbl(name, email, created_at) VALUES('$name', '$email', NOW())";
		if (mysqli_query($conn, $sql)) {
			echo '{"result" : "Data inserted."}';
		}else{
			echo '{"result" : "Data could not inserted."}';
		}
	}else{
		echo '{"result" : "Blank field could not inserted."}';
	}
}

function updateMethod($data){
	include "db.php";
	$id = $data['id'];
	$name = $data['name'];
	$email = $data['email'];

	if (!empty($name) && !empty($email) ) {
		$sql = "UPDATE tbl SET name='$name', email='$email', created_at=NOW() WHERE id='$id'";
		if (mysqli_query($conn, $sql)) {
			echo '{"result" : "Data updated."}';
		}else{
			echo '{"result" : "Data could not updated."}';
		}
	}else{
		echo '{"result" : "Blank field could not inserted."}';
	}
}

function deleteMethod($data){
	include "db.php";
	$id = $data['id'];

	if (!empty($id)) {
		$sql = "DELETE FROM tbl WHERE id='$id'";
		if (mysqli_query($conn, $sql)) {
			echo '{"result" : "Data deleted."}';
		}else{
			echo '{"result" : "Data could not deleted."}';
		}
	}else{
		echo '{"result" : "Blank field could not make request."}';
	}
}