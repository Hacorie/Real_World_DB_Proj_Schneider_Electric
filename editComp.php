<?php

	require_once('include/db.php');

	session_start();
	gateway(3);
	$title = 'Edit Complexities';
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

<div style="float: left; overflow: hidden; margin-right: 200px;">
<div>
	<h1>Add a Complexity</h1>
	<hr />
</div> 

<form name="addUser" action="users.php" method="post" accept-charset="utf-8">
<table class="table table-bordered table-striped" style="width: 100%">
		<tr> <td> <strong>Complexity Name:</strong></td><td> <input type="text" name="cname" placeholder="Name" required /></td></tr>
</table>
		<button type="submit" name="submit" class="btn btn-success">Create Complexity</button>
</form>
</div>

<div style="float: left;">
<div>
	<h1>Delete a Complexity</h1>
	<hr />
</div> 

<table class="table table-bordered table-striped" style="width: 80%">
<tr>
	<th><strong>Complexity</strong></th>
	<th><strong>Action</strong></th>
</tr>
	<?php foreach($users as $user) { ?>
		<tr><td>
			<?php echo $user['UName']; ?>
			<form action="editComp.php" method="post">
				<input type="hidden" name="UName" value="<?php echo $user['UName'] ?>" /></td><td>
				<button type="submit" name="delete" class="btn btn-xs btn-danger">Delete</button>
			</form>
		</td></tr>
	<?php } ?>
</table>
</div>
<?php include "include/footer.php"; ?>
