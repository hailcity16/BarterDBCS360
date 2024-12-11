

<?php
	session_start();
	include "db_connect.php";
	
?>
	
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Page</title>
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
            max-width: 900px;
            width: 100%;
        }

        .container h1 {
            font-size: 24px;
            font-weight: bold;
            color: #4a90e2;
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 16px;
        }

        .table th {
            background-color: #4a90e2;
            color: #fff;
            font-weight: bold;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #e0f3ff;
        }

        .status-complete {
            color: green;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-failed {
            color: red;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px 20px;
            }

            .container h1 {
                font-size: 20px;
            }

            .table th, .table td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<!-- Table Header -->

<div class="container">
    <h1>Trade Requests</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Item Had</th>
                <th>Item Wanted</th>
				<th> Type </th>
				<th> Approve </th>
            </tr>
        </thead>
        <tbody>
</div>

<?php

$user_id = $_SESSION['user_id'];

// Get requests sent to the user logged in

$request_sub = $con->query("SELECT * FROM requests WHERE user_req = $user_id");

if($request_sub -> num_rows > 0){
	 while($row = $request_sub->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . ($row["r_id"] ?? "") . "</td>";
                    echo "<td>" . htmlspecialchars($row["item_had"] ?? "") . "</td>";
                    echo "<td>" . htmlspecialchars($row["item_wanted"] ?? "") . "</td>";
					echo "<td>" . htmlspecialchars($row["type"] ?? "") . "</td>";
					
					// user B Approve Transaction
					
					if($row["type"] == "Other Side"){
						echo "<td><a href = 'get.php?user_idX=".$row["user_sent"]."&have=".$row["item_had"]."&want=".$row["item_wanted"]."' id='btn'> Approve </a><td>";
					}
					
					// A send portion of Hash Code To B
					else if($row["type"] == "Send B"){
						echo "<td><a href = 'sendHashB.php?t_id=".$row["t_id"]."&user_idB=".$row["user_sent"]."&have=".$row["item_had"]."&want=".$row["item_wanted"]."' id='btn'> Approve </a><td>";
					}
					
					// B Send Hash CodePart to DB
					
					else if($row["type"] == "Send HashDB"){
						echo "<td><a href = 'sendHashBDB.php?t_id=".$row["t_id"]."&user_idB=".$row["user_req"]."&have=".$row["item_wanted"]."&want=".$row["item_had"]."' id='btn'> Approve </a><td>";
					}
					
					// Y send Hash Code Part tp DB
					else if($row["type"] == "Send HashY"){
						echo "<td><a href = 'sendHashY.php?t_id=".$row["t_id"]."&user_idB=".$row["user_sent"]."&have=".$row["item_had"]."&want=".$row["item_wanted"]."' id='btn'> Approve </a><td>";
					}
                    echo "</tr>";
                }
}

else{
	echo "<tr><td colspan='4'>No data</td></tr>";
}

?>

<a href = 'bdashboard.php'> Dashboard </a>

</body>
</html>
