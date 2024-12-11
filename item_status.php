<?php
	include "db_connect.php";
	include "session_auto.php";	
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item List</title>
    <style>
        /* style */
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
            max-width: 800px;
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

<div class="container">
    <h1>Item List</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Item ID</th>
                <th>Name</th>
                <th>Cost</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
			<a href = "bdashboard.php"> Dashboard </a>
            <?php

            // Get entire items table
			
            $sql = "SELECT * FROM items";
            $result = $con->query($sql);
			
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
					
					// Only show items in the process of transaction
					
					if(isset($row["status"])){
						
						echo "<tr>";
						echo "<td>" . ($row["item_id"] ?? "") . "</td>";
						echo "<td>" . htmlspecialchars($row["name"] ?? "") . "</td>";
						echo "<td>" . htmlspecialchars($row["value"] ?? "") . "</td>";
						echo "<td>" . htmlspecialchars($row["status"] ?? "") . "</td>";
						echo "</tr>";
					}
                }
                
                
                
            } else {
                echo "<tr><td colspan='4'>No data</td></tr>";
            }
            $con->close();
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
