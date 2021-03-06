<?php

	require_once('include/db.php');

	session_start();
	gateway(3);
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
	<ul> <div class="page-header">
			<h1>Country Multipliers</h1>
		</div>  <br>
		<table class="table table-bordered table-striped" style="width: 40%">
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
	
		<button type="submit" name="submitCountry" class="btn btn-primary">Edit Country Multipliers</button>
	</ul>
</form>
<br><br>
<form name="editPartMults" action="editMults.php" method="post" accept-charset="utf-8">
	<ul> <div class="page-header">
			<h1>Part Multipliers</h1>
		</div> <br>
		<table class="table table-bordered table-striped" style="width: 40%">
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
		
		<button type="submit" name="submitParts" class="btn btn-primary">Edit Part Multipliers</button>
	</ul>
</form>
<?php include "include/footer.php"; ?>
