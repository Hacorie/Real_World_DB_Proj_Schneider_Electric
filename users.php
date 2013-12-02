<?php

	require_once('include/db.php');

	session_start();
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
<ul>
	<?php foreach($users as $user) { ?>
		<li>
			<?php echo $user['UName']; ?>
			<form action="users.php" method="post">
				<input type="hidden" name="UName" value="<?php echo $user['UName'] ?>" />
				<input type="submit" name="delete" value="Delete" />
			</form>
		</li>
	<?php } ?>
</ul>

<form name="addUser" action="users.php" method="post" accept-charset="utf-8">
	<ul>
		<li> Username: <input type="text" name="username" placeholder="Username" required /></li>
		<li> Password: <input type="password" name="password" placeholder="Password" required /></li>
		<li> Confirm Password: <input type="password" name="confirmPassword" placeholder="Retype Password" required /></li>
		<li>
			Groups:<br>
			<?php foreach($groups as $group) { ?>
				<input type="checkbox" name="group[]" value="<?php echo $group['GName'] ?>" /> <?php echo $group['GName'] ?><br />
			<?php } ?>
		</li>
		<li><input type="submit" name="submit" value="Create User" /></li>
	</ul>
</form>
<?php include "include/footer.php"; ?>
