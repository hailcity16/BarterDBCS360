<?php
include "session_auto.php";
include "db_connect.php"

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Item</title>
    <style>
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
        }

        .container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            max-width: 500px;
            width: 100%;
        }

        .container h1 {
            font-size: 24px;
            font-weight: bold;
            color: #4a90e2;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background: #f9f9f9;
            color: #333;
        }

        .button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #4a90e2;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-align: center;
            width: 100%;
            margin-top: 15px;
            text-decoration: none;
        }

        .button:hover {
            background-color: #357ab8;
        }

        .success-message {
            background-color: #e0f7e0;
            border: 1px solid #4caf50;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            color: #4caf50;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px 20px;
            }

            .container h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $item_requested = $_POST['itemrName'];
		$item_posted = $_POST['itempName'];

        // Get user's user_id
		
        $username = $_SESSION['username'];
        $sql = "SELECT user_id FROM users WHERE username = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close(); 

        // New bulletin board entry for user
		
        $sql_bulletin = "INSERT INTO BulletinBoard (ItemPosted, ItemRequested, user_id) VALUES (?, ?, ?)";
        $stmt_bulletin = $con->prepare($sql_bulletin);
        $stmt_bulletin->bind_param("ssi", $item_posted, $item_requested, $user_id);

        if ($stmt_bulletin->execute()) {
            echo "<div class='success-message'>
                    <h2>Your request has been successfully posted!</h2>
                    <p>Your request is now live on the bulletin board. You can continue browsing other pages.</p>
                   <a href='bdashboard.php'> Dashboard</a>
                  </div>";
        } else {
            echo "Error: " . $stmt_bulletin->error;
        }

        $stmt_bulletin->close();
        $con->close();
    } else {
        // BulletinBoard Post Form
		
        echo "
        <h1>Post Item You Want</h1>
        <form action='' method='POST'>
			
			
			<label for = 'itempName'> Item Posted </label>
			<input type = 'text' id = 'itempName' name = 'itempName' required>
			
            <label for='itemrName'> Item Wanted </label>
            <input type='text' id='itemrName' name='itemrName' required>

            <button type='submit' class='button'>Post Item</button>
        </form>";
    }
    ?>
</div>

</body>
</html>