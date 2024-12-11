<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>Login</title>
		<link rel="stylesheet" href="projectstyle.css"/>
	</head>
	
	<body>
		<?php
			require('db_connect.php');
			session_start();
			
			// Check if user entered their username into the login page
			
			if(isset($_POST['username'])) {
				
				// Get inputted username value
				
				$username = stripslashes($_REQUEST['username']);
				$username = mysqli_real_escape_string($con, $username);
				
				// Get inputted password value
				
				$password = stripslashes($_REQUEST['password']);
				$password = mysqli_real_escape_string($con, $password);
				
				// Query to find user in users table with username and password
				
				$query = "SELECT * FROM `users` WHERE username='$username'
                     AND password='" . md5($password) . "'";
					 
				$result = mysqli_query($con, $query) or die(mysql_error());
				
				$rows = mysqli_num_rows($result);
				
				# Username and password verified; Go to user dashboard
				
				if($rows == 1){
					$_SESSION['username'] = $username;
					header("Location: bdashboard.php");
				}
				
				// Not verified 
				
				else{
				  echo "<div class='form'>
                  <h3>Incorrect Username/password.</h3><br/>
                  <p class='link'>Click here to <a href='login.php'>Login</a> again.</p>
                  </div>";
				}
			}
			
			// Nothing inputted
			
			else {
			?>	
				<!-- Login form -->
				
				<form class="form" method="post" name="login">
					<h1 class="login-title">Login</h1>
					<input type="text" class="login-input" name="username" placeholder="Username" autofocus="true"/>
					<input type="password" class="login-input" name="password" placeholder="Password"/>
					<input type="submit" value="Login" name="submit" class="login-button"/>
					<p class="link"><a href="reg.php">New Registration</a></p>
				</form>
		<?php
			}
		?>
	</body>
</html>