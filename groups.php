<?php
	require_once('include/db.php');

	session_start();
	$title = 'Manage Groups';

	$db = dbConnect();

	$errMsg = Array();

	// If the form was submitted
	if (isset($_POST['submit'])) {

		$sql = "INSERT INTO Groups(GName) VALUES (?)";
		$stmt = $db->prepare($sql);
	
		$stmt->bind_param("s", $_POST['GName']);
		$stmt->execute();

		if ($db->affected_rows == 1) {

		} else {
			$errMsg[] = 'Error adding Group';
			$errMsg[] = $db->error;
		}
		$stmt->close();


	}

	// Get a list of groups
	$res = $db->query('SELECT GName from Groups');
	$groups = Array();
	while ($row = $res->fetch_assoc()) {
		$groups[] = $row;
	}

?>


<?php include "include/header.php"; ?>
<ul>
	<?php foreach($groups as $group) { ?>
		<li><?php echo $group['GName'] ?></li>
	<?php } ?>
</ul>

<form name="addGroup" action="groups.php" method="post" accept-charset="utf-8">
		<ul>
			<?php if (!empty($errMsg)) { ?>
				<li><?php echo join('<br />', $errMsg); ?></li>
			<?php } ?>
			<li>Group Name: <input type="text" name="GName" placeholder="Group Name" required /></li>
			<li><input type="submit" name="submit" value="Create Group" /></li>
		</ul>
</form>

<?php include "include/footer.php"; ?>
