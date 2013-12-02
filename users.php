<?php

	require_once('include/db.php');

	session_start();
	gateway(3);
	$title = 'Manage Users';
	$admin = true;

	$db = dbConnect();

	// Get a list of groups
	$groups = dbQuery($db, 'SELECT GName from Groups');

	$errMsg = Array();

	// If the add form was submitted
	if (isset($_POST['submit'])) {
		if ($_POST['password'] != $_POST['confirmPassword']) {
			$errMsg[] = 'Passwords do not match.';
		} else {


			// Add an entry to the log_in table
			$sql = "INSERT INTO User(UName, Password) 
					VALUES (?, ?)";
			$stmt = $db->prepare($sql);
			
			$stmt->bind_param("ss", $_POST['username'], sha1($_POST['password']));
			$stmt->execute();

			if ($db->affected_rows == 1) {
				// Add groups
				$sql = "INSERT INTO Member_Of(UName, GName) VALUES (?, ?)";
				$stmt = $db->prepare($sql);
				
				$stmt->bind_param("ss", $_POST['username'], $g);

				$grp = $_POST['group'];
				foreach ($grp as $g) {
					$stmt->execute();
				}

				$flash = "User added!";
			} else {
				$errMsg[] = 'Error adding User';
				$errMsg[] = $db->error;
			}
			$stmt->close();


		}

	}

	if (isset($_POST['delete'])) {

		$sql = "DELETE FROM Member_Of WHERE UName = ?";
		$stmt = $db->prepare($sql);
	
		$stmt->bind_param("s", $_POST['UName']);
		$stmt->execute();

		$sql = "DELETE FROM User WHERE UName = ?";
		$stmt = $db->prepare($sql);
	
		$stmt->bind_param("s", $_POST['UName']);
		$stmt->execute();

		if ($db->affected_rows == 1) {
			$flash = $_POST['UName'] . ' was removed!';
		} else {
			$errMsg[] = 'Error deleting User';
			$errMsg[] = $db->error;
		}
		$stmt->close();


	}

	$error = join('<br />', $errMsg);

	// Get a list of groups
	$users = dbQuery($db, 'SELECT UName FROM User');

?>

<?php include "include/header.php"; ?>

<div class="page-header">
	<h1>Delete a User</h1>
</div> 

<table class="table table-bordered table-striped" style="width: 15%">
<tr>
	<th><strong>Users</strong></th>
	<th><strong>Action</strong></th>
</tr>
	<?php foreach($users as $user) { ?>
		<tr><td>
			<?php echo $user['UName']; ?>
			<form action="users.php" method="post">
				<input type="hidden" name="UName" value="<?php echo $user['UName'] ?>" /></td><td>
				<button type="submit" name="delete" class="btn btn-xs btn-danger">Delete</button>
			</form>
		</td></tr>
	<?php } ?>
</table>

<div class="page-header">
	<h1>Add a User</h1>
</div> 

<form name="addUser" action="users.php" method="post" accept-charset="utf-8">
<table class="table table-bordered table-striped" style="width: 15%">
		<tr> <td> Username:</td><td> <input type="text" name="username" placeholder="Username" required /></td></tr>
		<tr> <td> Password:</td><td> <input type="password" name="password" placeholder="Password" required /></td></tr>
		<tr> <td> Confirm Password:</td><td> <input type="password" name="confirmPassword" placeholder="Retype Password" required /></td></tr>
		<tr> <td>
			Groups:<br></td><td>
			<?php foreach($groups as $group) { ?>
				<input type="checkbox" name="group[]" value="<?php echo $group['GName'] ?>" /> <?php echo $group['GName'] ?><br />
			<?php } ?>
		</td></tr>
</table>
		<button type="submit" name="submit" class="btn btn-success">Create User</button>
</form>
<?php include "include/footer.php"; ?>
