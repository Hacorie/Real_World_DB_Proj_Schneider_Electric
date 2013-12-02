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
<ul>
	<?php foreach($groups as $group) { ?>
		<li>
			<?php echo $group['GName']; ?> (<?php echo $group['Count']; ?>)
			<?php if (!in_array($group['GName'], $specialGroups)) { ?>
				<form action="groups.php" method="post">
					<input type="hidden" name="GName" value="<?php echo $group['GName'] ?>" />
					<input type="submit" name="delete" value="Delete" />
				</form>
			<?php } ?>
		</li>
	<?php } ?>
</ul>

<form name="addGroup" action="groups.php" method="post" accept-charset="utf-8">
		<ul>
			<li>Group Name: <input type="text" name="GName" placeholder="Group Name" required /></li>
			<li><input type="submit" name="add" value="Create Group" /></li>
		</ul>
</form>

<?php include "include/footer.php"; ?>
