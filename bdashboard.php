<?php
    session_start();
	include "db_connect.php";
    
    $username = $_SESSION['username'];
	
    
    // Find user_id, role, and if admin from logged in User
	
    $sql = "SELECT user_id, role, admin FROM users WHERE username = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $role, $admin);
	
    
	// $sql succeeds
	
    if ($stmt->fetch()) {
		$_SESSION['user_id'] = $user_id;
		
		// Shows username, user_id, and link to logout page
		
        echo "<div class='form'>
                <h1>Welcome, " . htmlspecialchars($username) . "!</h1>
                <h1>Your User ID is: " . htmlspecialchars($user_id) . "</h1>
                <div class='logout'>
                    <p><a href='logout.php'>Logout</a></p>
                    
                    

                </div>
              </div>";
    }
	
	// $sql fails
	
	else {
        echo "Error: No user found";
    }
    
    

?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Client Area</title>
    <link rel="stylesheet" href="projectstyle.css" />
    <style>
        /* Reset some default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        /* Header positioning */
        .header {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 36px;  /* Larger font size */
            font-weight: bold;
            color: #4a90e2;
        }

        .form {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            font-size: 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .form h1 {
            font-size: 28px;
            color: #4a90e2;
            margin-bottom: 20px;
        }

        .form p {
            margin: 15px 0;
        }

        .form p a {
            display: inline-block;
            padding: 12px 20px;
            margin: 8px 0;
            background-color: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .form p a:hover {
            background-color: #357ab8;
        }

        .form p a:active {
            background-color: #286594;
        }

        .form .logout {
            margin-top: auto;
            padding-top: 20px;
        }

        @media (max-width: 600px) {
            .form {
                padding: 20px;
                width: 90%;
            }

            .form h1 {
                font-size: 24px;
            }

            .header {
                font-size: 28px;  /* Adjust the size for smaller screens */
            }
        }
    </style>
</head>



<body>
    <!-- Header section at the top-left -->
    <div class="header">
        Barter Dashboard
    </div>
	<?php
		
		// User isn't approved
		
		if($role != "approved")
		{
			echo '<p> Wait for acccount to be approved by admin </p>';
		}
		
		// User is approved
		
		else
		{
			// All links to different parts of the website
			
		?> <div class="form">
				<p><a href="bulletin1.php">Bulletin Board</a></p>
				<p><a href="linkpartner.php">Link to Partner</a></p>
				<p><a href="transaction1.php">Conduct Transactions</a></p>
				<p><a href="post-ad.php">Add Item</a></p>
				<p><a href="request.php">Post on Bulletin Board</a></p>
				<p><a href = "tradereq.php"> Accept Trade Requests</a></p>
				<p><a href = "item_status.php"> Check Item Status</a></p>
				
				<?php 
				// User has admin priveleges
				
				if($admin == 1)
				{ 
					echo '<p><a href="admin.php">Admin Dashboard</a></p>';
				}
				?>
				
			</div>
		<?php
		}
		?>
    </div>
</body>

</html>
