<?php

	// Connect to barterdb
	
	$con = mysqli_connect("localhost", "root", "", "barterdb");
	
	if(mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
?>