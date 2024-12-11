<?php   
 include 'db_connect.php';
 
 if (isset($_GET['id'])) {  
      $id = $_GET['id'];
	  
	  // Delete entries connected to specified user_id (foreign keys first)
	  
	  $item_query = "Delete FROM items where user_id = '$id'";
	  $runI = mysqli_query($con,$item_query);
	
	  $bulletin_query = "Delete FROM bulletinboard where user_id = '$id'";
	  $runB = mysqli_query($con,$bulletin_query); 
	  
	  
      $user_query = "DELETE FROM users WHERE user_id = '$id'"; 
      $runU = mysqli_query($con,$user_query); 
	  
      if ($runU) 
	  {  
		header('location:admin.php');  
      }
	  
	  else
	  {  
		echo "Error: ".mysqli_error($con);  
      }  
 }  
 ?>  