<?php

	require_once('include/db.php');

	session_start();
	gateway(3);
	$title = 'Edit Sub-Categories';
	$admin = true;

	$db = dbConnect();

	$errMsg = Array();

	// If the add form was submitted
	if (isset($_POST['submit'])) {



		// Add an entry to the log_in table
		$sql = "INSERT INTO Subcategory VALUES (?)";
		$stmt = $db->prepare($sql);
		
		$stmt->bind_param("s", $_POST['SName']);
		$stmt->execute();

		if ($db->affected_rows == 1) {
			// Add groups
			$flash = "Subcategory added!";
		} else {
			$errMsg[] = 'Error adding Subcategory';
			$errMsg[] = $db->error;
		}
		$stmt->close();


	}

	if (isset($_POST['delete'])) {

		$sql = "DELETE FROM Subcategory WHERE SName = ?";
		$stmt = $db->prepare($sql);
	
		$stmt->bind_param("s", $_POST['SName']);
		$stmt->execute();


		if ($db->affected_rows == 1) {
			$flash = $_POST['SName'] . ' was removed!';
		} else {
			$errMsg[] = 'Error deleting Subcategory';
			$errMsg[] = $db->error;
		}
		$stmt->close();


	}

	$error = join('<br />', $errMsg);

	// Get a list of groups
	$categories = dbQuery($db, 'SELECT * FROM Subcategory');

?>

<?php include "include/header.php"; ?>

<div style="float: left; overflow: hidden; margin-right: 200px;">
<div>
	<h1>Add a Sub-Category</h1>
	<hr />
</div> 

<form name="addCategory" action="editSC.php" method="post" accept-charset="utf-8">
<table class="table table-bordered table-striped" style="width: 100%">
		<tr> <td> <strong>Sub-Category Name:</strong></td><td> <input type="text" name="SName" placeholder="Name" required /></td></tr>
</table>
		<button type="submit" name="submit" class="btn btn-success">Create Sub-Category</button>
</form>
</div>

<div style="float: left;">
<div>
	<h1>Delete a Sub-Category</h1>
	<hr />
</div> 

<table class="table table-bordered table-striped" style="width: 80%">
<tr>
	<th><strong>Sub-Category</strong></th>
	<th><strong>Action</strong></th>
</tr>
	<?php foreach($categories as $category) { ?>
		<tr><td>
			<?php echo $category['SName']; ?>
			<form action="editSC.php" method="post">
				<input type="hidden" name="SName" value="<?php echo $category['SName'] ?>" /></td><td>
				<button type="submit" name="delete" class="btn btn-xs btn-danger">Delete</button>
			</form>
		</td></tr>
	<?php } ?>
</table>
</div>
<?php include "include/footer.php"; ?>
