

<?php

	include "db_connect.php";
	include "session_auto.php";
	
?>
	
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Other User's Post Page</title>
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
    <h1>Matches</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Post ID</th>
                <th>Item Posted</th>
                <th>Item Requested</th>
				<th> Select </th>
            </tr>
        </thead>
        <tbody>
</div>

<?php

$user_idX = $_SESSION['user_id'];
$have = $_GET['have'];
$need = $_GET['need'];



// Get matching bulletin board posts to the post user selected and show them

$bulletin_board_sub = $con->query("SELECT * FROM BulletinBoard WHERE user_id != $user_idX AND ItemPosted = '$need' AND ItemRequested = '$have'");

if($bulletin_board_sub -> num_rows > 0){
	 while($row = $bulletin_board_sub->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . ($row["post_id"] ?? "") . "</td>";
                    echo "<td>" . htmlspecialchars($row["ItemPosted"] ?? "") . "</td>";
                    echo "<td>" . htmlspecialchars($row["ItemRequested"] ?? "") . "</td>";
					echo "<td><a href = 'sendB.php?idB=".$row["user_id"]."&have=".$row["ItemPosted"]."&need=".$row["ItemRequested"]."' id='btn'> Select </a><td>";
                    echo "</tr>";
                }
}

else{
	echo "<tr><td colspan='4'>No Data</td></tr>";
}

?>

</body>
</html>
