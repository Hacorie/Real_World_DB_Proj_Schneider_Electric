<?php

	require_once('include/db.php');

	session_start();
	gateway(3);
	$title = 'Edit Complexities';
	$admin = true;

	$db = dbConnect();

	$errMsg = Array();

	// If the add form was submitted
	if (isset($_POST['submit'])) {

		// Add an entry to the log_in table
		$sql = "INSERT INTO Complexity VALUES (?)";
		$stmt = $db->prepare($sql);
		
		$stmt->bind_param("s", $_POST['CName']);
		$stmt->execute();

		if ($db->affected_rows == 1) {
			$flash = "Complexity added!";
		} else {
			$errMsg[] = 'Error adding Complexity';
			$errMsg[] = $db->error;
		}
		$stmt->close();

	}

	if (isset($_POST['delete'])) {

		$sql = "DELETE FROM Complexity WHERE CName = ?";
		$stmt = $db->prepare($sql);
	
		$stmt->bind_param("s", $_POST['CName']);
		$stmt->execute();

		if ($db->affected_rows == 1) {
			$flash = $_POST['CName'] . ' was removed!';
		} else {
			$errMsg[] = 'Error deleting Complexity';
			$errMsg[] = $db->error;
		}
		$stmt->close();


	}

	$error = join('<br />', $errMsg);

	// Get a list of groups
	$complexities = dbQuery($db, 'SELECT * FROM Complexity');

?>

<?php include "include/header.php"; ?>

<div style="float: left; overflow: hidden; margin-right: 200px;">
<div>
	<h1>Add a Complexity</h1>
	<hr />
</div> 

<form name="addUser" action="editComp.php" method="post" accept-charset="utf-8">
<table class="table table-bordered table-striped" style="width: 100%">
		<tr> <td> <strong>Complexity Name:</strong></td><td> <input type="text" name="CName" placeholder="Name" required /></td></tr>
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
	<?php foreach($complexities as $com) { ?>
		<tr><td>
			<?php echo $com['CName']; ?>
			<form action="editComp.php" method="post">
				<input type="hidden" name="CName" value="<?php echo $com['CName'] ?>" /></td><td>
				<button type="submit" name="delete" class="btn btn-xs btn-danger">Delete</button>
			</form>
		</td></tr>
	<?php } ?>
</table>
</div>
<?php include "include/footer.php"; ?>
