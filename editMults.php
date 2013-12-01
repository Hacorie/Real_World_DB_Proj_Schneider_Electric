<?php

	require_once('include/db.php');

	session_start();
	$title = 'Edit Multipliers';

	$db = dbConnect();

	$country = dbQuery($db, 'SELECT * from Country');

	$res = $db->query('SELECT * from Product_Type');
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
				<td> <input type="text" name="country" placeholder="Country Name" value="<?php echo $item['CName'] ?>" required /> </td>
				<td> <input type="text" name="cmultiplier" placeholder="Multiplier" value="<?php echo $item['Multiplier'] ?>" required /> </td>
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
				<td> <input type="text" name="part" placeholder="Part Name" value="<?php echo $item['PName'] ?>" required /> </td>
				<td> <input type="text" name="pmultiplier" placeholder="Multiplier" value="<?php echo $item['Multiplier'] ?>" required /> </td>
			</tr>
			<?php } ?>
		</table>
		
		<li><input type="submit" name="submitCountry" value="Edit Country Multipliers" /></li>
	</ul>
</form>
<?php include "include/footer.php"; ?>
