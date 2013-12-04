<?php
	require_once('include/db.php');

	$specialGroups = array("Administrator", "OE", "Tag Members", "User");

	session_start();
	gateway(3);
	$title = 'Manage Groups';
	$admin = true;

	$db = dbConnect();

	$errMsg = Array();

	// If the form was submitted
	if (isset($_POST['add'])) {

		$sql = "INSERT INTO Groups(GName) VALUES (?)";
		$stmt = $db->prepare($sql);
	
		$stmt->bind_param("s", $_POST['GName']);
		$stmt->execute();

		if ($db->affected_rows == 1) {
			$flash = $_POST['GName'] . ' group was added!';
		} else {
			$errMsg[] = 'Error adding Group';
			$errMsg[] = $db->error;
		}
		$stmt->close();


	}

	if (isset($_POST['delete']) && !in_array($_POST['GName'], $specialGroups)) {

		$sql = "DELETE FROM Groups WHERE GName = ?";
		$stmt = $db->prepare($sql);
	
		$stmt->bind_param("s", $_POST['GName']);
		$stmt->execute();

		if ($db->affected_rows == 1) {
			$flash = $_POST['GName'] . ' group was deleted!';
		} else {
			$errMsg[] = 'Error deleting Group';
			$errMsg[] = $db->error;
		}
		$stmt->close();


	}

	$error = join('<br />', $errMsg);

	// Get a list of groups
	$groups = dbQuery($db, 'SELECT Groups.GName, COUNT(DISTINCT Member_Of.UName) AS Count
			FROM Groups LEFT JOIN Member_Of ON Groups.GName = Member_Of.GName
			GROUP BY Groups.GName
			ORDER BY Groups.GName');

?>


<?php include "include/header.php"; ?>

<div style="float: left; overflow: hidden; margin-right: 200px;">
<div class="page-header">
	<h1>Create a Group</h1>
</div> 

<form name="addGroup" action="groups.php" method="post" accept-charset="utf-8">
		<table class="table table-bordered table-striped" style="width: 100%">
			<tr><td><strong>Group Name: </strong></td><td><input type="text" name="GName" placeholder="Group Name" required /></tr>
		</table>
		<button type="submit" name="add" class="btn btn-success">Create User</button>
</form>
</div>

<div style="float: left;">
<div class="page-header">
	<h1>Delete a Group</h1>
</div> 

<table class="table table-bordered table-striped">
<tr>
	<th><strong>Group Name</strong></th>
	<th><strong>Members</strong></th>
	<th><strong>Action</strong></th>
</tr>
	<?php foreach($groups as $group) { ?>
		<tr>
			<td><?php echo $group['GName']; ?> </td><td><?php echo $group['Count']; ?></td>
			<?php if (!in_array($group['GName'], $specialGroups)) { ?>
				<form action="groups.php" method="post">
					<input type="hidden" name="GName" value="<?php echo $group['GName'] ?>" />
					<td><button type="submit" name="delete" class="btn btn-xs btn-danger">Delete</button></td>
				</form>
			<?php } else { ?>
				<td><button class="btn btn-xs btn-danger" disabled="disabled">Delete</button></td>

			<?php } ?>
		</tr>
	<?php } ?>
</table>
</div>
<?php include "include/footer.php"; ?>
