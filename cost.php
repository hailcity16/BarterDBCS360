<?php

	include "db_connect.php";
	include "session_auto.php";

	$user_idY = $_SESSION['user_id'];
	$user_idX = $_GET['x'];
	$user_idA = $_GET['a'];
	$user_idB = $_GET['b'];
	$item_a = $_GET['want'];
	$item_b = $_GET['have'];
	$a_cost = $_GET['wantc'];
	$b_cost = $_GET['havec'];
	$t_id = $_GET['tid'];
	
	// Get user X's item_id
	
	$sql_itemA = "Select item_id FROM items WHERE user_id = '$user_idX' AND name = '$item_b'";
	$stmt_itemA = $con->prepare($sql_itemA);
	$stmt_itemA->execute();
    $stmt_itemA->bind_result($a_id);
    $stmt_itemA->fetch();
	$stmt_itemA->close();
	
	// Get user B's item_id
	
	$sql_itemB = "Select item_id FROM items WHERE user_id = '$user_idB' AND name = '$item_a'";
	$stmt_itemB = $con->prepare($sql_itemB);
	$stmt_itemB->execute();
    $stmt_itemB->bind_result($b_id);
    $stmt_itemB->fetch();
	$stmt_itemB->close();
	
	// Get equivalence Value
	
	$sql_equiv = "Select equivalence_value FROM equivalence_table WHERE itema_name = '$item_a' AND itemb_name = '$item_b'";
	$stmt_equiv = $con->prepare($sql_equiv);
	$stmt_equiv->execute();
	$stmt_equiv->bind_result($e_val);
	$stmt_equiv->fetch();
	$stmt_equiv->close();
	
?>

<!DOCTYPE html>

<html>

<head>

	<title> Equivalence Verification </title>

</head>

<body>

	<?php
	
		$fee = 5;

		// Deduct cost and equivalence Values
		
		$a_cost = $a_cost - $fee - $e_val;
		$b_cost = $b_cost - $fee;
		
		// Costs not the same
		
		if($a_cost != $b_cost){
			
			echo "<p> Error: Values of items don't match up after accounting for fees. Trade Failed. Please change an item's price. </p>";
			echo "<a href='bdashboard.php'> Dashboard </a>";
		
			// Transaction Failed
			
			$status = "failed";
			$sql_failed = "UPDATE transactions SET status = '$status'  WHERE transaction_id = '$t_id'";
			$runF = mysqli_query($con, $sql_failed);
			
			if(!$runF){
				echo "Error: ".mysqli_error($con);   
			}
		}
		
		// Costs are the same
		
		else{
			
			echo "<p> Trade successful! </p>";
			echo "<a href='bdashboard.php'> Dashboard </a>";
			
			if($e_val < 0){
				$e_val = $e_val * -1;
			}
			
			// Total cost of Transaction
			
			$cost = $fee + $fee + $e_val;
			
			// Transaction succeeds
			
			
			$bstatus = "in_progess";
			$istatus = "complete";
			
			$sql_succeed = "UPDATE transactions SET status = '$istatus', costs = '$cost' WHERE transaction_id = '$t_id'";
			$runS = mysqli_query($con, $sql_succeed);
	
			if(!$runS){
				echo "Error: ".mysqli_error($con);   
			}
			
			// Item sent to user A
			
			$bstatus = "in_progess";
			$istatus = "complete";
			$sql_switchA = "UPDATE items SET user_id = '$user_idA', status = '$istatus' WHERE item_id = '$a_id' AND status = '$bstatus'";
			$runA = mysqli_query($con, $sql_switchA);
			
			if(!$runA){
				echo "Error: ".mysqli_error($con);   
			}
			
			
			// Item sent to user Y
			
			$sql_switchB = "UPDATE items SET user_id = '$user_idY', status = '$istatus WHERE item_id = '$b_id' AND status = '$bstatus'";
			$runB = mysqli_query($con, $sql_switchB);
			
			if(!$runB){
				echo "Error: ".mysqli_error($con);   
			}	
		}
		
		
	?>
	
	

</body>

</html>