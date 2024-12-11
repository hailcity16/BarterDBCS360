<?php
include "session_auto.php";
include "db_connect.php";
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
	
	// Form filled out
	
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $item_name = $_POST['itemName'];
        $item_value = $_POST['itemPrice'];

        // Get user_id for user
		
        $username = $_SESSION['username'];
        $sql = "SELECT user_id FROM users WHERE username = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close(); 
        
        // Insert new item into items for user
		
        $sql_insert = "INSERT INTO items (value, name, user_id) VALUES (?, ?, ?)";
        $stmt_insert = $con->prepare($sql_insert);
        $stmt_insert->bind_param("ssi", $item_value, $item_name, $user_id);

        if ($stmt_insert->execute()) {
            echo "<div class='success-message'>
                    <h2>Your item has been successfully posted!</h2>
                    <p>Your item is now live on the bulletin board. You can continue browsing other pages.</p>
					<a href = 'bdashboard.php'> Dashboard </a>
                  </div>";
        } else {
            echo "发布失败: " . $stmt_insert->error;
        }

        $stmt_insert->close();
        $con->close();
    } else {
		
		// Item Form
        echo "
        <h1>Post An Item</h1>
        <form action='' method='POST'>
            <label for='itemName'>Input Item</label>
            <input type='text' id='itemName' name='itemName' required>
			

            <label for='itemPrice'>Input Item Value</label>
            <input type='number' id='itemPrice' name='itemPrice' required>
			

            <button type='submit' class='button'>Input Item</button>
        </form>
		<a href = 'bdashboard.php'> Dashboard </a>";
    }
    ?>
</div>

</body>
</html>

