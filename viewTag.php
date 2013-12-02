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
			TagNotes, InstallCost, PriceNotes, Owner, LeadTime, MaterialCost, LaborCost, EngineeringCost,
			HVL, HVLCC, MC, MVMCC
			FROM Tag WHERE Num = ? AND Revision = ?");
		$stmt->bind_param("ii", $_GET['tag'], $_GET['rev']);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {

			$stmt->bind_result($num, $revision, $creationDate, $description, $category, $complexity, $priceExpire,
				$notes, $cost, $priceNotes, $owner, $leadTime, $mat, $labor, $eng, $hvl, $hvlcc, $mc, $mvmcc);

			$stmt->fetch();
			$stmt->close();

            $compArr = dbQuery($db, 'SELECT CName FROM Complexity');
            $subCatArr = dbQuery($db, 'SELECT SName FROM Subcategory');

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
					'EngineeringCost' => $eng,
					'HVL' => $hvl,
					'HVLCC' => $hvlcc,
					'MC' => $mc,
					'MVMCC' => $mvmcc,
                    'compArr' => $compArr,
                    'subCatArr' => $subCatArr
				);

			
		} else {
			$error = "Invalid Tag Number or Revision";
		}

		// Get the multipliers
		$countryDB = dbQuery($db, 'SELECT * FROM Country');
		$countries = array();
		foreach($countryDB as $country) {
			$countries[$country['CName']] = $country['Multiplier'];
		}

		$productDB = dbQuery($db, 'SELECT * FROM Product_Type');
		$products = array();
		foreach($productDB as $product) {
			$products[$product['PName']] = $product['Multiplier'];
		}

		$attachments = dbQuery($db, "SELECT * FROM Attachment WHERE Tag = '" . intval($_GET['tag']) . "'");

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
<!--        <td>
            <select id="addTag_sCategory" name="sCategory">
                <?php $arr = $tag['subCatArr']; foreach($arr as $item) { ?>
                    <option value="<?php echo $item['SName'] ?>" <?php if($item['SName'] == $tag['Subcategory']){ echo "selected";}?>> <?php echo $item['SName'] ?>  </option>
                <?php } ?>
            </select>
        </td>

  DO  NOT DELETE THIS!      <td>
            <select id="addTag_leadTime" name="complexity">
                <?php $arr = $tag['compArr']; foreach($arr as $item) { ?>
                    <option value="<?php echo $item['CName'] ?>" <?php if($item['CName'] == $tag['Complexity']){ echo "selected";}?>> <?php echo $item['CName'] ?>  </option>
                <?php } ?>
            </select>

        </td>
-->
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
	<button class="btn btn-danger" id="attachmentButton">Click Box to Make TAG Permanently Obsolete</button><br />
	<button class="btn btn-primary" id="attachmentButton">Make Revision</button><br /><br />
	<hr style="clear: both"/>
	<div id="attachmentList">
	<strong>Attachments:</strong>
	<ul style="list-style-type: none">
		<?php if (!empty($attachments)) { foreach($attachments as $attachment) { ?>
			<li><input type="checkbox" value="<?php echo $attachment['Name']; ?>" /> <?php echo $attachment['Name']; ?></li>
		<?php } } ?>
	</ul>
	</div>
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
			<td><input type="checkbox" <?php if($tag['HVL'] == 1) { echo 'checked="checked"'; }?> />HVL</td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL'] * $countries['USA']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL'] * $countries['Canada']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL'] * $countries['Mexico']; ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['HVLCC'] == 1) { echo 'checked="checked"'; }?> />HVL/CC</td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL/CC'] * $countries['USA']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL/CC'] * $countries['Canada']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL/CC'] * $countries['Mexico']; ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['MC'] == 1) { echo 'checked="checked"'; }?> />Metal Clad</td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['Metal Clad'] * $countries['USA']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['Metal Clad'] * $countries['Canada']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['Metal Clad'] * $countries['Mexico']; ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['MVMCC'] == 1) { echo 'checked="checked"'; }?> />MVMCC</td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['MVMCC'] * $countries['USA']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['MVMCC'] * $countries['Canada']; ?>" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['MVMCC'] * $countries['Mexico']; ?>" /></td>
		</tr>
	</table>
	</div>
	<div id="section4">

<!-- Nav tabs -->
<ul class="nav nav-tabs nav-success">
  <li><a href="#quote" data-toggle="tab">Quote</a></li>
  <li><a href="#factoryorder" data-toggle="tab">Factory Order</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content" style="margin-bottom: 100px" >
  <div class="tab-pane active" id="quote">
	<table class="table-bordered" width=100%>
	<tr>
		<th>Tag Number</th>
		<th>FO Number Applied To</th>
		<th>Notes</th>
	</tr>
	<tr>
		<td>Hi</td>
		<td>Chyna</td>
		<td>BOOM</td>
	</tr>
	</table>
  </div>
  <div class="tab-pane" id="factoryorder">
	<table class="table-bordered" width=100%>
	<tr>
		<th>Tag Number</th>
		<th>FO Number Applied To</th>
		<th>Notes</th>
	</tr>
	<tr>
		<td>Hello</td>
		<td>Shawn Michaels</td>
		<td>ROASTED</td>
	</tr>
	</table>
  </div>

</div>
</div>
</form>

<?php } else { ?>
	Try searching for a tag.
<?php } ?>

<?php include "include/footer.php"; ?>
