<?php
	session_start();

	include "db_connect.php";

	$user_idB = $_SESSION['user_id'];
	$have = $_GET['have'];
	$want = $_GET['want'];
	$user_idX = $_GET['user_idX'];

	
	// Generate 16 digit hash code
	
	$characters = '0123456789abcdefghijklmnopqrs092u3tuvwxyzaskdhfhf9882323ABCDEFGHIJKLMNksadf9044OPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomHash = '';
    for ($i = 0; $i < 16; $i++) {
        $randomHash .= $characters[rand(0, $charactersLength - 1)];
    }
    
	
	$hashLead = substr($randomHash, 0, 8);
	$hashTrail = substr($randomHash, 8, 8);
	
	// Find user Y
	
	$sql_idY = "SELECT partner_id FROM users WHERE user_id = '$user_idX'";
	$stmt_idY = $con->prepare($sql_idY);
    $stmt_idY->execute();
    $stmt_idY->bind_result($user_idY);
    $stmt_idY->fetch();
	$stmt_idY->close();
	
	// Set Y's hash part to last 8 digits of hash
	
	$sql_updateY = "UPDATE users SET HashCodePart = '$hashTrail' WHERE user_id = '$user_idY'";
	$runY = mysqli_query($con, $sql_updateY);
	
	if(!$runY){
		echo "Error: ".mysqli_error($con);   
	}
	
	
	// Find user A
	
	$sql_idA = "SELECT partner_id FROM users WHERE user_id = '$user_idB'";
	$stmt_idA = $con->prepare($sql_idA);
    $stmt_idA->execute();
    $stmt_idA->bind_result($user_idA);
    $stmt_idA->fetch(); 
	$stmt_idA->close();
	
	// Set A's hash part 
	
	$sql_updateA = "UPDATE users SET HashCodePart = '$hashLead' WHERE user_id = '$user_idA'";
	$runA = mysqli_query($con, $sql_updateA);
	
	if(!$runA){
		echo "Error: ".mysqli_error($con);   
	}
	
	// Get itema_name and itema_cost
	
	$sql_itemA = "SELECT name, value FROM items WHERE user_id = '$user_idB' AND name = '$have'";
	$stmt_itemA = $con->prepare($sql_itemA);
    $stmt_itemA->execute();
    $stmt_itemA->bind_result($nameA, $valueA);
    $stmt_itemA->fetch();
	$stmt_itemA->close();
	
	// Get itemb_name and itemb_cost
	
	$sql_itemB = "SELECT name, value FROM items WHERE user_id = '$user_idX' AND name = '$want'";
	$stmt_itemB = $con->prepare($sql_itemB);
    $stmt_itemB->execute();
    $stmt_itemB->bind_result($nameB, $valueB);
    $stmt_itemB->fetch();
	$stmt_itemB->close();
	
	$status = "in-progress";
	
	// Add transaction entry
	
	$sql_insertT = "INSERT INTO transactions (
	hash_code, status, itema_name, itemb_name, itema_cost, itemb_cost, userX, userY, userA, userB)
	VALUES ('$randomHash', '$status', '$nameA', '$nameB', '$valueA', '$valueB', '$user_idX', '$user_idY', '$user_idA', '$user_idB')";
	$runT = mysqli_query($con, $sql_insertT);
	
	if(!$runT){
		echo "Error: ".mysqli_error($con); 
	}
	
	
	// Update B's item status
	
	$sql_statusB = "UPDATE items SET status = '$status' WHERE user_id = '$user_idB' AND status IS NULL AND name = '$nameA'";
	$runsB = mysqli_query($con, $sql_statusB);
			
	if(!$runsB){
		echo "Error: ".mysqli_error($con);   
	}
	
	// Update X's item status
	
	$sql_statusX = "UPDATE items SET status = '$status' WHERE user_id = '$user_idX' AND status IS NULL AND name = '$nameB'";
	$runsX = mysqli_query($con, $sql_statusX);
			
	if(!$runsX){
		echo "Error: ".mysqli_error($con);   
	}
	
	// Get transaction_id
	
	$sql_tid = "SELECT transaction_id FROM transactions WHERE 
	userX = '$user_idX' AND userY = '$user_idY' AND userA = '$user_idA' AND userB = '$user_idB'
	AND itema_name = '$nameA' AND itemb_name = '$nameB' AND itema_cost = '$valueA' AND itemb_cost = '$valueB'";
	$stmt_tid = $con->prepare($sql_tid);
    $stmt_tid->execute();
    $stmt_tid->bind_result($tid);
    $stmt_tid->fetch();
	$stmt_tid->close();
	
	
	// Get rid of first request of transaction
	
	$sql_deleteR = "DELETE FROM requests WHERE user_req = '$user_idB'";
	$runD = mysqli_query($con, $sql_deleteR);
	
	if(!$runD){
		echo "Error: ".mysqli_error($con); 
	}

	
	// Send request to A to send hash code part to B
	
	$type = "Send B";
	$sql_insertR = "INSERT INTO requests (user_req, item_wanted, item_had, type, user_sent, t_id) VALUES ('$user_idA', '$want', '$have', '$type','$user_idB', '$tid')";
	$runIR = mysqli_query($con, $sql_insertR);
	
	if($runIR){
		header("location:tradereq.php");
	}
	
	else{
		echo "Error: ".mysqli_error($con); 
	}

	
?>
