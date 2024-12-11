<?php
	include "db_connect.php";
	include "session_auto.php";
	
	$user_idA = $_SESSION['user_id'];
	$user_idB = $_GET['user_idB'];
	$have = $_GET['have'];
	$want = $_GET['want'];
	$t_id = $_GET['t_id'];
	
	// Get HashCodePart from A
	
	$sql_hashA = "Select HashCodePart FROM users WHERE user_id = '$user_idA'";
	$stmt_hashA = $con->prepare($sql_hashA);
    $stmt_hashA->execute();
    $stmt_hashA->bind_result($hashB);
    $stmt_hashA->fetch();
	$stmt_hashA->close();
	
	// Set A's HashCodePart to NULL
	
	$sql_updateHashA = "UPDATE users SET HashCodePart = NULL WHERE user_id = '$user_idA'";
	$runA = mysqli_query($con, $sql_updateHashA);
	
	if(!$runA){
		echo "Error: ".mysqli_error($con);   
	}
	
	// Set B's HashCodePart to A's previous one
	
	$sql_updateHashB = "UPDATE users SET HashCodePart = '$hashB' WHERE user_id = '$user_idB'";
	$runB = mysqli_query($con, $sql_updateHashB);
	
	if(!$runB){
		echo "Error: ".mysqli_error($con);   
	}
	
	// Delete last request
	
	$sql_deleteRA = "DELETE FROM requests WHERE user_req = '$user_idA' AND t_id = '$t_id'";
	$runRA = mysqli_query($con, $sql_deleteRA);
	
	if(!$runRA){
		echo "Error: ".mysqli_error($con); 
	}

	// Request B to send their HashCodePart to DB
	
	$type = "Send HashDB";
	$sql_insertRB = "INSERT INTO requests (user_req, item_wanted, item_had, type, user_sent, t_id) VALUES ('$user_idB', '$want', '$have', '$type','$user_idA', '$t_id')";
	$runRB = mysqli_query($con, $sql_insertRB);
	
	if($runRB){
		header("location:tradereq.php");
	}
	
	else{
		echo "Error: ".mysqli_error($con); 
	}
	
	
?>