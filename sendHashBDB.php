<?php

	include "db_connect.php";
	include "session_auto.php";
	
	$user_idB = $_SESSION['user_id'];
	$have = $_GET['have'];
	$want = $_GET['want'];
	$t_id = $_GET['t_id'];

	// Get User B's HashCodePart
	
	$sql_hashB = "Select HashCodePart FROM users WHERE user_id = '$user_idB'";
	$stmt_hashB = $con->prepare($sql_hashB);
	$stmt_hashB->execute();
    $stmt_hashB->bind_result($hashDB);
    $stmt_hashB->fetch();
	$stmt_hashB->close();
	
	# Set user B's HashCodePart to NULL
	
	$sql_updateHashB = "UPDATE users SET HashCodePart = NULL WHERE user_id = '$user_idB'";
	$runB = mysqli_query($con, $sql_updateHashB);
	
	if(!$runB){
		echo "Error: ".mysqli_error($con);   
	}
	
	// Set lead_hash to B's HashCodePart
	
	$sql_updateTrans = "UPDATE transactions SET lead_hash = '$hashDB' WHERE transaction_id = '$t_id'";
	$runT = mysqli_query($con, $sql_updateTrans);
	
	if(!$runT){
		echo "Error: ".mysqli_error($con);   
	}
	
	// Get user Y
	
	$sql_selectY = "Select userY FROM transactions WHERE transaction_id = '$t_id'";
	$stmt_selectY = $con->prepare($sql_selectY);
	$stmt_selectY->execute();
    $stmt_selectY->bind_result($user_idY);
    $stmt_selectY->fetch();
	$stmt_selectY->close();
	
	// Delete previous request
	
	$sql_deleteRB = "DELETE FROM requests WHERE user_req = '$user_idB' AND t_id = '$t_id'";
	$runRB = mysqli_query($con, $sql_deleteRB);
	
	if(!$runRB){
		echo "Error: ".mysqli_error($con); 
	}
	
	// Request Y to send their HashCodePart
	
	$type = "Send HashY";
	$sql_insertRY = "INSERT INTO requests (user_req, item_wanted, item_had, type, user_sent, t_id) VALUES ('$user_idY', '$want', '$have', '$type','$user_idB', '$t_id')";
	$runRY = mysqli_query($con, $sql_insertRY);
	
	if($runRY){
		header("location:tradereq.php");
	}
	
	else{
		echo "Error: ".mysqli_error($con); 
	}
?>