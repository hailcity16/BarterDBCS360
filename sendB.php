<?php
	include "db_connect.php";
	include "session_auto.php";
	
	$user_idX = $_SESSION['user_id'];
	$idB = $_GET['idB'];
	$have = $_GET['have'];
	$need = $_GET['need'];
	
	$type = "Other Side";
	
	// Send request to user B (owner of item user X wants)
	
	$sql_request = "INSERT INTO requests (user_req, item_wanted, item_had, type, user_sent) VALUES ('$idB', '$need', '$have', '$type', '$user_idX')";
	$run = mysqli_query($con, $sql_request);
	
	if($run){
		header("location:bdashboard.php");
	}
	
	else
	{
		echo "Error: ".mysqli_error($con);  
	}
	
?>