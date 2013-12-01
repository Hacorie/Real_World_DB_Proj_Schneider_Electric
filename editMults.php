<?php

	require_once('include/db.php');

	session_start();
	$title = 'Edit Multipliers';

	$db = dbConnect();

	if (isset($_POST['submitCountry'])) {
		$countries = dbQuery($db, 'SELECT * from Country');
		$sql = "UPDATE Country SET Multiplier = ? WHERE CName = ?";
		$stmt = $db->prepare($sql);
		
		$stmt->bind_param("ds", $value, $country);
		foreach ($countries as $entry) {
			$country = $entry['CName'];
			if (array_key_exists($country, $_POST)) {
				$value = $_POST[$country];
				$stmt->execute();
			}
		}
	}

	if (isset($_POST['submitParts'])) {
		$parts = dbQuery($db, 'SELECT * from Product_Type');
		$sql = "UPDATE Product_Type SET Multiplier = ? WHERE PName = ?";
		$stmt = $db->prepare($sql);
		
		$stmt->bind_param("ds", $value, $part);
		foreach ($parts as $entry) {
			$part = $entry['PName'];
			if (array_key_exists($part, $_POST)) {
				$value = $_POST[$part];
				$stmt->execute();
			}
		}
	}


	$country = dbQuery($db, 'SELECT * from Country');
	$parts = dbQuery($db, 'SELECT * from Product_Type')

?>

<?php include "include/header.php"; ?>  
<form name="editCountryMults" action="editMults.php" method="post" accept-charset="utf-8">
	<ul> Country Multipliers <br>
		<table border="1">
			<tr>
				<th> Country </th>
				<th> Multiplier</th>
			</tr>

			<?php foreach($country as $item) { ?>
			<tr>
				<td><?php echo $item['CName'] ?></td>
				<td><input type="text" name="<?php echo $item['CName'] ?>" value="<?php echo $item['Multiplier'] ?>" /></td>
			</tr>
			<?php } ?>
		</table>
		
		<li><input type="submit" name="submitCountry" value="Edit Country Multipliers" /></li>
	</ul>
</form>
<br><br>
<form name="editPartMults" action="editMults.php" method="post" accept-charset="utf-8">
	<ul> Part Multipliers <br>
		<table border = "1">
			<tr>
				<th> Part </th>
				<th> Multiplier</th>
			</tr>
			<?php foreach($parts as $item) { ?>
			<tr>
				<td><?php echo $item['PName'] ?></td>
				<td><input type="text" name="<?php echo $item['PName'] ?>" value="<?php echo $item['Multiplier'] ?>" /></td>
			</tr>
			<?php } ?>
		</table>
		
		<li><input type="submit" name="submitParts" value="Edit Part Multipliers" /></li>
	</ul>
</form>
<?php include "include/footer.php"; ?>
