<?php

	include "db_connect.php";
	include "session_auto.php";
	
	$user_idY = $_SESSION['user_id'];
	$have = $_GET['have'];
	$want = $_GET['want'];
	$t_id = $_GET['t_id'];
	$user_idB = $_GET['user_idB'];

	// Get user Y's HashCodePart
	
	$sql_hashY = "Select HashCodePart FROM users WHERE user_id = '$user_idY'";
	$stmt_hashY = $con->prepare($sql_hashY);
	$stmt_hashY->execute();
    $stmt_hashY->bind_result($hashY);
    $stmt_hashY->fetch();
	$stmt_hashY->close();
	
	// Set Y's HashCodePart to NULL
	
	$sql_updateHashY = "UPDATE users SET HashCodePart = NULL WHERE user_id = '$user_idY'";
	$runY = mysqli_query($con, $sql_updateHashY);
	
	if(!$runY){
		echo "Error: ".mysqli_error($con);   
	}
	
	// Set trail_hash to Y's HashCodePart
	
	$sql_updateTrans = "UPDATE transactions SET trail_hash = '$hashY' WHERE transaction_id = '$t_id'";
	$runT = mysqli_query($con, $sql_updateTrans);
	
	if(!$runT){
		echo "Error: ".mysqli_error($con);   
	}
	
	// Get most values from transactions
	
	$sql_selectT = "SELECT hash_code, itema_cost, itemb_cost, userX, userA, lead_hash, trail_hash
	FROM transactions WHERE transaction_id = '$t_id'";
	$stmt_selectT = $con->prepare($sql_selectT);
	$stmt_selectT->execute();
    $stmt_selectT->bind_result($hash, $aCost, $bCost, $user_idX, $user_idA, $lead, $trail);
    $stmt_selectT->fetch();
	$stmt_selectT->close();
	
	// Delete previous request
	
	$sql_deleteRY = "DELETE FROM requests WHERE user_req = '$user_idY' AND t_id = '$t_id'";
	$runRY = mysqli_query($con, $sql_deleteRY);
	
	if(!$runRY){
		echo "Error: ".mysqli_error($con); 
	}
?>

<!DOCTYPE html>

<html>

<head>

	<title> Trading End </title>

</head>

<body>

<?php
	
	$hashFull = $lead . $trail;
	
	// Hash is invalid
	
	if($hash != $hashFull){
		
		echo "<p> Error: Parts of hash code don't line up. Trade Failed. </p>";
		echo "<a href='bdashboard.php'> Dashboard </a>";
		
		// Transaction Failed
		
		$status = "failed";
		$sql_failed = "UPDATE transactions SET status = '$status'  WHERE transaction_id = '$t_id'";
		$runF = mysqli_query($con, $sql_failed);
	
		if(!$runF){
			echo "Error: ".mysqli_error($con);   
		}
	}
	
	// Hash Code Matches
	
	else{
		
		echo "<p> Hash Code Matches! </p>";
		echo "<a href='cost.php?want=".$want."&wantc=".$aCost."&have=".$have."&havec=".$bCost."&x=".$user_idX."&a=".$user_idA."
		&b=".$user_idB."&tid=".$t_id."'> Continue </a>";
		
		// Status set to hash verified
		
		$status = "hash verified";
		$sql_succeed = "UPDATE transactions SET status = '$status'  WHERE transaction_id = '$t_id'";
		$runS = mysqli_query($con, $sql_succeed);
	
		if(!$runS){
			echo "Error: ".mysqli_error($con);   
		}
		
	}
		
	
?>


</body>

</html>

