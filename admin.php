
<?php
// Database connection
include "db_connect.php";
include "session_auto.php";

$users = $con->query("SELECT * FROM users");

// Handle user actions (approve, suspend, delete)
if(isset($_POST['action']) &&isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    if ($_POST['action'] === 'approve') {
        $con->query("UPDATE users SET role='approved' WHERE user_id=$user_id");
    } elseif ($_POST['action'] === 'suspend') {
       $con->query("UPDATE users SET role='suspended' WHERE user_id=$user_id");
    }
}

// Fetch users and transactions

$type = "";
$order = 0;

$query = "SELECT * FROM transactions";
$transactions = $con->query($query);



// Calculate total transaction cost

$sql_cost = "SELECT SUM(costs) FROM Transactions";
$stmt_cost = $con->prepare($sql_cost);
$stmt_cost->execute();
$stmt_cost->bind_result($total);
$stmt_cost->fetch();
$stmt_cost->close();

// Order by specific column in transactions

if(isset($_GET['type']) && isset($_GET['order'])){
	$type = $_GET['type'];
	$order = $_GET['order'];
	
	if($order)
	{
		$order = 0;
		$query = "SELECT * FROM Transactions ORDER BY ".$type." ASC";
			
	}
		
	else
	{
		$order = 1;
		$query = "SELECT * FROM Transactions ORDER BY ".$type." DESC";
	}
}

$transactions = $con->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_style.css"> <!-- Link to CSS file with the styles from previous response -->
</head>
<body>
    <div class="header">Admin Dashboard</div>
    <div class="admin-container">
        <h1>User Management</h1>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
					<th> Delete </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <form method="post" style="display: inline-block;">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <button type="submit" name="action" value="approve" class="delete-button">Approve</button>
                            <button type="submit" name="action" value="suspend" class="delete-button">Suspend</button>
                        </form>
                    </td>
					<td>
					<?php echo "<a href='delete.php?id=".$user['user_id']."' id = 'btn'> Delete </a>" ?>
					</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h1>Transaction Management</h1>
        <table>
            <thead>
                <tr>
                    <?php echo "<th><a href='admin.php?type=transaction_id&order=".$order."'>Transaction ID</a></th>
                    <th><a href='admin.php?type=itema_name&order=".$order."'>Item A</a></th>
                    <th><a href='admin.php?type=itemb_name&order=".$order."'>Item B</a></th>
                    <th><a href='admin.php?type=itema_cost&order=".$order."'>Item A Cost</a></th>
                    <th><a href='admin.php?type=itemb_cost&order=".$order."'>Item B Cost</a></th>
                    <th><a href='admin.php?type=hash_code&order=".$order."'>Hash Key</a></th>
                    <th><a href='admin.php?type=status&order=".$order."'>Status</a></th>
					<th><a href='admin.php?type=userX&order=".$order."'>User X</a></th>
					<th><a href='admin.php?type=userY&order=".$order."'>User Y</a></th>
					<th><a href='admin.php?type=userA&order=".$order."'>User A</a></th>
					<th><a href='admin.php?type=userB&order=".$order."'>User B</a></th>
					<th><a href='admin.php?type=costs&order=".$order."'>Costs</a></th>";
					?>
                </tr>
            </thead>
            <tbody>
                <?php while ($transaction = $transactions->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $transaction['transaction_id']; ?></td>
                    <td><?php echo $transaction['itema_name']; ?></td>
                    <td><?php echo $transaction['itemb_name']; ?></td>
                    <td><?php echo $transaction['itema_cost']; ?></td>
                    <td><?php echo $transaction['itemb_cost']; ?></td>
                    <td><?php echo $transaction['hash_code']; ?></td>
                    <td><?php echo $transaction['status']; ?></td>
					<td><?php echo $transaction['userX']; ?></td>
					<td><?php echo $transaction['userY']; ?></td>
					<td><?php echo $transaction['userA']; ?></td>
					<td><?php echo $transaction['userB']; ?></td>
					<td><?php echo $transaction['costs']; ?></td>
					
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="admin-footer">
            <p><strong>Total Transaction Costs:</strong> $<?php echo $total; ?></p>
            <a href="logout.php" class="logout-button">Logout</a>
			<a href="bdashboard.php">Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php $con->close(); ?>
