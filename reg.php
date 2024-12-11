<!DOCTYPE html>

<html>

	<head>
		<meta charset = "utf-8"/>
		<title> BarterDB Register </title>
		<link rel = "stylesheet" href = "projectstyle.css"/>
	</head>
	
	<body>
		<?php
			require('db_connect.php');
			
			// Email input is set
			
			if(isset($_REQUEST['email'])){
				
				// Get username input
				
				$username = stripslashes($_REQUEST['username']);
				$username = mysqli_real_escape_string($con, $username);
				
				// Get email input
				
				$email = stripslashes($_REQUEST['email']);
				$email = mysqli_real_escape_string($con, $email);
				
				// Get phone numebr input
				
				$pno = stripslashes($_REQUEST['pno']);
				$pno = mysqli_real_escape_string($con, $pno);
				
				
				// Get Address input
				
				$address = stripslashes($_REQUEST['address']);
				$address = mysqli_real_escape_string($con, $address);
				
				// Get password input
				
				$password = stripslashes($_REQUEST['password']);
				$password = mysqli_real_escape_string($con, $password);
				
				
				// Insert new entry into users table
				
				$query = "INSERT into `users` (username, email, password, pno, address)
                VALUES ('$username', '$email', '" . md5($password) . "', '$pno', '$address')";
				
				$result = mysqli_query($con, $query);
				
				// $result succeeds
				
				if($result) {
				  echo "<div class='form'>
                  <h3>You are registered!</h3><br/>
                  <p class='link'><a href='login.php'>Login</a></p>
                  </div>";
				}
			}
			
			else{
		?>
				<!-- Registration Form -->
				
				<form class="form" action="" method="post">
					<h1 class="login-title">Register</h1>
					<input type="text" class="login-input" name="username" placeholder="Username"/>
					<input type="text" class="login-input" name="email" placeholder="Email Address" required>
					<input type="text" class="login-input" name="pno" placeholder="Phone Number" required>
					<input type="text" class="login-input" name="address" placeholder="Street Address" required>
					<input type="password" class="login-input" name="password" placeholder="Password" required>
					<input type="submit" name="submit" value="Register" class="login-button">
					<p class="link"><a href="login.php">Login</a></p>
				</form>
		<?php
			}
		?>
	</body>
</html>