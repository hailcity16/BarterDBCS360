<?php
include "session_auto.php";
include "db_connect.php";


if (!isset($_SESSION["username"])) {
    die("Error: No user set");
}

$username = $_SESSION["username"];
$success = false;


// Get partner_id value from user input

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['userID'])) {
        $partner_userid = $_POST['userID'];
    } else {
        die();
    }

	// Find user's user_id
	
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $current_user_id = $result->fetch_assoc()['user_id'];
    } else {
        die();
    }
    $stmt->close();

    // Set partner_id in users table
	
    $sql = "UPDATE users SET partner_id = ? WHERE user_id = ?";
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        die($con->error);
    }

    // Set partner_id on other side
	
    $stmt->bind_param("ii", $partner_userid, $current_user_id);
    if ($stmt->execute()) {
        $stmt->bind_param("ii", $current_user_id, $partner_userid);
        if ($stmt->execute()) {
            $success = true;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // 关闭连接
    $stmt->close();
    $con->close();
}
?>


<?php if ($success): ?>
    
<?php endif; ?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Partner</title>
    <style>
    /* CSS */
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
    max-width: 600px;
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
input[type="email"] {
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
}

.button:hover {
    background-color: #357ab8;
}

/* 成功消息样式 */
.success-container {
    background: #e6f7ff; /* 浅蓝色背景 */
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    max-width: 600px;
    width: 100%;
    text-align: center;
    margin-top: 20px;
}

.success-message {
    font-size: 18px;
    color: #4a90e2; /* 蓝色文字 */
    margin-bottom: 15px;
}

@media (max-width: 600px) {
    .container, .success-container {
        padding: 15px 20px;
    }

    .container h1, .success-message {
        font-size: 20px;
    }
}
</style>

<script>
        function showSuccessMessage() {
            document.querySelector('.container').classList.add('hidden');
            document.querySelector('.success-container').classList.remove('hidden');
        }
    </script>
</head>
<body>

<?php if ($success): ?>

	<!-- Partner succesfully linked -->
	
    <div class="container success-container">
        <div class="success-message">Link partner successfully</div>
        <button class="button" onclick="window.location.href='bdashboard.php'">GO TO Dashboard</button>
    </div>
    <script>
        document.querySelector('.form-container').classList.add('hidden');
    </script>
<?php else: ?>
    <!-- Partner_id Form -->
	
    <div class="container form-container">
        <h1>Link Partner</h1>
        <form action="linkpartner.php" method="POST">
            <label for="userID">Partner User ID</label>
            <input type="text" id="userID" name="userID" placeholder="Enter Partner's User ID" required>

            <label for="email">Partner Email</label>
            <input type="email" id="email" name="email" placeholder="Enter Partner's Email" required>

            <button type="submit" class="button">Link Partner</button>
        </form>
    </div>
<?php endif; ?>


</body>
</html>
