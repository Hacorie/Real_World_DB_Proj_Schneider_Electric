<?php

	require_once('include/db.php');

	session_start();
	gateway(3);
	$title = 'Edit User';

	$db = dbConnect();
	$username = $db->real_escape_string($_GET['user']);
	$groups = dbQuery($db, 'SELECT * FROM Groups');
	

	if (isset($_POST['submit'])) {
		$db->query("DELETE FROM Member_Of WHERE UName = '$username'");

		foreach($groups as $group) {
			if (in_array($group['GName'], $_POST['group'])) {
				$db->query("INSERT INTO Member_Of VALUES ('$username', '{$group['GName']}')");
			}
		}

	}

	// Get the user's groups
	$groupsDB = dbQuery($db, "SELECT * FROM Member_Of WHERE UName = '$username'");

	$groupMember = array();

	foreach($groupsDB as $group) {
		$groupMember[] = $group['GName'];
	}

	

?>

<?php include "include/header.php"; ?>

<div style="float: left; overflow: hidden; margin-right: 200px;">
<div>
	<h1>Edit a User</h1>
	<hr />
</div> 

<form name="editUser" action="editUser.php?user=<?php echo $username; ?>" method="post" accept-charset="utf-8">
<table class="table table-bordered table-striped" style="width: 15%">
		<tr> <td> Username:</td><td> <input type="text" name="username" value="<?php echo $username; ?>" disabled="disabled"/></td></tr>
		<tr> <td>
			Groups:<br></td><td>
			<?php foreach($groups as $group) { ?>
				<input type="checkbox" name="group[]" value="<?php echo $group['GName']; ?>" <?php if(in_array($group['GName'], $groupMember)) { echo 'checked="checked"';}?> /> <?php echo $group['GName']; ?><br />
			<?php } ?>
		</td></tr>
</table>
		<button type="submit" name="submit" class="btn btn-primary">Modify User</button>
</form>
</div>

<?php include "include/footer.php"; ?>
