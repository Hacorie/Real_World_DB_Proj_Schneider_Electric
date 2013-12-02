<?php
	
	require_once('include/db.php');

	session_start();
	gateway(0);
	$title = 'View Tag';
	
	// Verify that a valid tag was specified
	if (isset($_GET['tag']) && isset($_GET['rev'])) {


		// Get the tag
		$db = dbConnect();
		$stmt = $db->prepare("SELECT Num, Revision, CreationDate, Description, Subcategory, Complexity, PriceExpire,
			TagNotes, InstallCost, PriceNotes, Owner, LeadTime, MaterialCost, LaborCost, EngineeringCost
			FROM Tag WHERE Num = ? AND Revision = ?");
		$stmt->bind_param("ii", $_GET['tag'], $_GET['rev']);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {

			$stmt->bind_result($num, $revision, $creationDate, $description, $category, $complexity, $priceExpire,
				$notes, $cost, $priceNotes, $owner, $leadTime, $mat, $labor, $eng);

			$stmt->fetch();
			$stmt->close();

			$tag = array(
					'Num' => $num,
					'Revision' => $revision,
					'CreationDate' => $creationDate,
					'Description' => $description,
					'Subcategory' => $category,
					'Complexity' => $complexity,
					'PriceExpire' => $priceExpire,
					'Notes' => $notes,
					'InstallCost' => $cost,
					'PriceNotes' => $priceNotes,
					'Owner' => $owner,
					'LeadTime' => $leadTime,
					'MaterialCost' => $mat,
					'LaborCost' => $labor,
					'EngineeringCost' => $eng
					);

			
		} else {
			$error = "Invalid Tag Number or Revision";
		}

	}




?>

<?php include "include/header.php"; ?>

<?php if (isset($tag)) { ?>

<div class="page-header">
	<h1>View a Tag</h1>
</div> 
<form name="addtag" action="addTag.php" method="post" accept-charset="utf-8">
	<div id="section_wrapper">
	<div id="section1">
	<table id="addTagDiv">
	<tr>
		<td width=10%>Tag #</td>
		<td width=8%>Rev # </td>
		<td width=13%>Date </td>
		<td>Sub-Category</td>
		<td>Complexity</td>
		<td>Lead Time</td>
	</tr>
	<tr>
		<td><input id="addTag_tagNum" type="text" name="tagNum" value="<?php echo $tag['Num']; ?>" /></td>
		<td><input id="addTag_rev" type="text" name="rev" value="<?php echo $tag['Revision']; ?>" /></td>
		<td><input id="addTag_date" type="text" name="date" value="<?php echo $tag['CreationDate']; ?>" /></td>
		<td><input id="addTag_sCategory" type="text" name="sCategory" value="<?php echo $tag['Subcategory']; ?>" /></td>
		<td><input id="addTag_complexity" type="text" name="complexity" value="<?php echo $tag['Complexity']; ?>" /></td>
		<td><input id="addTag_leadTime"type="text" name="leadTime" value="<?php echo $tag['LeadTime']; ?>" /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Tag Description:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell" type="text" name="desc" value="<?php echo $tag['Description']; ?>" /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Tag Notes:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell"type="text" name="tagNotes" value="<?php echo $tag['Notes']; ?>" /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Price Note:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell" type="text" name="priceNotes" value="<?php echo $tag['PriceNotes']; ?>" /></td>
	</tr>
	</table>
	</div>

	<div id="section3">
		<strong><i>Pricing Information</i></strong>
	<table id="pricingTable">
		<tr>
			<td>Material:</td>
			<td><input type="text" value="<?php echo $tag['MaterialCost']; ?>" /></td>
		</tr>
		<tr>
			<td>Labor:</td>
			<td><input type="text" value="<?php echo $tag['LaborCost']; ?>" /></td>
		</tr>
		<tr style="border-bottom: 1px solid #000;">
			<td>Engineering:</td>
			<td><input type="text" value="<?php echo $tag['EngineeringCost']; ?>" /></td>
		</tr>
		<tr>
			<td>Initial Cost:</td>
			<td><input type="text" value="<?php echo $tag['InstallCost']; ?>" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td>TAG Member:</td>
			<td><input type="text" value="<?php echo $tag['Owner']; ?>" /></td>
		</tr>
		<tr>
			<td>Price Expires:</td>
			<td><input type="text" value="<?php echo $tag['PriceExpire']; ?>" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	<input type="checkbox" name="vehicle" value="Obsolete" />Click Box to Make TAG Permanently Obsolete<br />
	<button class="btn btn-primary" id="viewTag_button">Make Revision</button>
	<button class="btn" id="viewTag_button">Go to Datasheet</button><br />
	<button class="btn" id="viewTag_button">Review Attachments</button><br />
	</div>	
	<div id="section2">
		<strong>Product Lines Tag May be Applied To:</strong>
	<table width=60%>
		<tr>
			<td></td>
			<td>USA$</td>
			<td>Canada$</td>
			<td>Mexico$</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="vehicle" value="HVL" />HVL</td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="vehicle" value="HVL/CC" />HVL/CC</td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="vehicle" value="Metal Clad" />Metal Clad</td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="vehicle" value="MVMCC" />MVMCC</td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
			<td><input type="text" placeholder="$X.XX" /></td>
		</tr>
	</table>
	</div>
	<div id="section4">
	<div id="addTag_checkbox"><input id="addTag_checkbox" type="checkbox" name="quote" value="Quote" />Quote </div>
	<div id="addTag_checkbox"><input id="addTag_checkbox" type="checkbox" name="fOrder" value="Factory Order" />Factory Order</div>
	<br />
	<table class="table-bordered" width=100%>
	<tr>
		<th>Tag Number</th>
		<th>FO Number Applied To</th>
		<th>Notes</th>
	</tr>
	<tr>
		<td><input type="text" /></td>
		<td><input type="text" /></td>
		<td><input type="text" /></td>
	</tr>
	<tr>
		<td><input type="text" /></td>
		<td><input type="text" /></td>
		<td><input type="text" /></td>
	</tr>
	<tr>
		<td><input type="text" /></td>
		<td><input type="text" /></td>
		<td><input type="text" /></td>
	</tr>
	<tr>
		<td><input type="text" /></td>
		<td><input type="text" /></td>
		<td><input type="text" /></td>
	</tr>
	<tr>
		<td><input type="text" /></td>
		<td><input type="text" /></td>
		<td><input type="text" /></td>
	</tr>
	</table>
	</div>
</div>
</form>

<?php } else { ?>
	Try searching for a tag.
<?php } ?>

<?php include "include/footer.php"; ?>