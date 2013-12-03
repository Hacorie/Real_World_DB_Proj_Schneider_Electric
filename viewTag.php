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
		$fotable = dbQuery($db, "SELECT * FROM Applied_FO_Table WHERE Num = '" . intval($_GET['tag']) . "'");

	}




?>

<?php include "include/header.php"; ?>

<?php if (isset($tag)) { ?>

<div class="page-header" style="overflow: auto;">
	<h1 id="pheader">View a Tag</h1>
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
		<td><input id="addTag_tagNum" type="text" name="tagNum" value="<?php echo $tag['Num']; ?>" disabled="disabled" /></td>
		<td><input id="addTag_rev" type="text" name="rev" value="<?php echo $tag['Revision']; ?>" disabled="disabled"  /></td>
		<td><input id="addTag_date" type="text" name="date" value="<?php echo $tag['CreationDate']; ?>" disabled="disabled"  /></td>
		<td><input id="addTag_sCategory" type="text" name="sCategory" value="<?php echo $tag['Subcategory']; ?>" disabled="disabled"  /></td>
		<td><input id="addTag_complexity" type="text" name="complexity" value="<?php echo $tag['Complexity']; ?>" disabled="disabled"  /></td>
        <td><input id="addTag_leadTime"type="text" name="leadTime" value="<?php echo $tag['LeadTime']; ?>" disabled="disabled"  /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Tag Description:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell" type="text" name="desc" value="<?php echo $tag['Description']; ?>" disabled="disabled"  /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Tag Notes:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell"type="text" name="tagNotes" value="<?php echo $tag['Notes']; ?>" disabled="disabled"  /></td>
	</tr>
	</table>
	<table id="tagTable">
	<tr>
		<td>Price Note:</td>
	</tr>
	<tr>
		<td ><input id="tagDescCell" type="text" name="priceNotes" value="<?php echo $tag['PriceNotes']; ?>" disabled="disabled"  /></td>
	</tr>
	</table>
	</div>

	<div id="section3">
		<?php if ($_SESSION['role'] > 0) { ?>
		<strong><i>Pricing Information</i></strong>
	<table id="pricingTable">
		<tr>
			<td>Material:</td>
			<td><input type="text" value="<?php echo $tag['MaterialCost']; ?>" disabled="disabled"  /></td>
		</tr>
		<tr>
			<td>Labor:</td>
			<td><input type="text" value="<?php echo $tag['LaborCost']; ?>" disabled="disabled"  /></td>
		</tr>
		<tr style="border-bottom: 1px solid #000;">
			<td>Engineering:</td>
			<td><input type="text" value="<?php echo $tag['EngineeringCost']; ?>" disabled="disabled"  /></td>
		</tr>
		<tr>
			<td>Initial Cost:</td>
			<td><input type="text" value="<?php echo $tag['InstallCost']; ?>" disabled="disabled"  /></td>
		</tr>
		<tr id="emptyRow"><td>&nbsp;</td></tr>
		<tr id="emptyRow"><td>&nbsp;</td></tr>
		<tr id="emptyRow"><td>&nbsp;</td></tr>
		<tr>
			<td>TAG Member:</td>
			<td><input type="text" value="<?php echo $tag['Owner']; ?>" disabled="disabled"  /></td>
		</tr>
		<tr>
			<td>Price Expires:</td>
			<td><input type="text" value="<?php echo $tag['PriceExpire']; ?>" disabled="disabled"  /></td>
		</tr>
		<tr id="emptyRow"><td>&nbsp;</td></tr>
		<tr id="emptyRow"><td>&nbsp;</td></tr>
		<tr id="emptyRow"><td>&nbsp;</td></tr>
	</table>
	<button class="btn btn-danger" id="attachmentButton">Click Box to Make TAG Permanently Obsolete</button><br />
	<a href="editTag.php?tag=<?php echo $tag['Num']; ?>" class="btn btn-primary" id="attachmentButton">Make Revision</a><br /><br />
	<hr style="clear: both"/>
	<div id="attachmentList">
	<strong>Attachments:</strong>
	<ul style="list-style-type: none">
		<?php if (!empty($attachments)) { foreach($attachments as $attachment) { ?>
			<li><input type="checkbox" value="<?php echo $attachment['Name']; ?>"  disabled="disabled" /> <?php echo $attachment['Name']; ?></li>
		<?php } } ?>
	</ul>
	</div>
	<?php } ?>
	</div>	
	<div id="section2">
		<strong>Product Lines Tag May be Applied To:</strong>
	<table width=60% id="plTable">
		<tr>
			<td></td>
			<td>USA$</td>
			<td>Canada$</td>
			<td>Mexico$</td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['HVL'] == 1) { echo 'checked="checked"'; }?>  disabled="disabled" />HVL</td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL'] * $countries['USA']; ?>"  disabled="disabled" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL'] * $countries['Canada']; ?>" disabled="disabled"  /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL'] * $countries['Mexico']; ?>" disabled="disabled"  /></td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['HVLCC'] == 1) { echo 'checked="checked"'; }?>  disabled="disabled" />HVL/CC</td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL/CC'] * $countries['USA']; ?>" disabled="disabled"  /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL/CC'] * $countries['Canada']; ?>"  disabled="disabled" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['HVL/CC'] * $countries['Mexico']; ?>"  disabled="disabled" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['MC'] == 1) { echo 'checked="checked"'; }?>  disabled="disabled" />Metal Clad</td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['Metal Clad'] * $countries['USA']; ?>"  disabled="disabled" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['Metal Clad'] * $countries['Canada']; ?>"  disabled="disabled" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['Metal Clad'] * $countries['Mexico']; ?>"  disabled="disabled" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" <?php if($tag['MVMCC'] == 1) { echo 'checked="checked"'; }?>  disabled="disabled" />MVMCC</td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['MVMCC'] * $countries['USA']; ?>"  disabled="disabled" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['MVMCC'] * $countries['Canada']; ?>"  disabled="disabled" /></td>
			<td><input type="text" value="<?php echo $tag['InstallCost'] * $products['MVMCC'] * $countries['Mexico']; ?>"  disabled="disabled" /></td>
		</tr>
	</table>
	</div>
	<div id="section4">

<!-- Nav tabs -->
<ul class="nav nav-tabs nav-success">
  <li><a href="#all" data-toggle="tab">All</a></li>
  <li><a href="#quote" data-toggle="tab">Quote</a></li>
  <li><a href="#factoryorder" data-toggle="tab">Factory Order</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content" style="margin-bottom: 100px" >
  <div class="tab-pane active" id="all">
	<table class="table-bordered" width=100%>
	<tr>
		<th>Tag Number</th>
		<th>FO Number Applied To</th>
		<th>Notes</th>
	</tr>
	<?php if (!empty($fotable)) { foreach($fotable as $fo) { ?>
		<tr>
			<td><?php echo $tag['Num']; ?></td>
			<td><?php echo $fo['FONumber']; ?></td>
			<td><?php echo $tag['Notes']; ?></td>
		</tr>
	<?php } } ?>
	</table>
  </div>
  <div class="tab-pane" id="quote">
	<table class="table-bordered" width=100%>
	<tr>
		<th>Tag Number</th>
		<th>FO Number Applied To</th>
		<th>Notes</th>
	</tr>
	<?php if (!empty($fotable)) { foreach($fotable as $fo) { ?>
		<?php if ($fo['Typeof'] == 'Q') { ?>
			<tr>
				<td><?php echo $tag['Num']; ?></td>
				<td><?php echo $fo['FONumber']; ?></td>
				<td><?php echo $tag['Notes']; ?></td>
			</tr>
		<?php } ?>	
	<?php } } ?>
	</table>
  </div>
  <div class="tab-pane" id="factoryorder">
	<table class="table-bordered" width=100%>
	<tr>
		<th>Tag Number</th>
		<th>FO Number Applied To</th>
		<th>Notes</th>
	</tr>
	<?php if (!empty($fotable)) { foreach($fotable as $fo) { ?>
		<?php if ($fo['Typeof'] == 'F') { ?>
			<tr>
				<td><?php echo $tag['Num']; ?></td>
				<td><?php echo $fo['FONumber']; ?></td>
				<td><?php echo $tag['Notes']; ?></td>
			</tr>
		<?php } ?>	
	<?php } } ?>
	</table>
  </div>

</div>
</div>
</form>

<?php } else { ?>
<div class="page-header">
	<h1>View a Tag</h1>
</div>

<div>
			<table class="table table-bordered table-striped" id="tags_table">
				<thead>
					<tr>
						<th id="th_tag" style="text-align: center;">Tag</th>
						<th id="th_rev" style="text-align: center;">Rev</th>
						<th id="th_date" style="text-align: center;">Date</th>
						<th id="th_desc" style="text-align: center;">Description</th>
						<th id="th_subcat" style="text-align: center;">Sub Cat.</th>
						<th id="th_nodes" style="text-align: center;">Notes</th>
						<th id="th_install" style="text-align: center;">Install Cost</th>
						<th id="th_pricenote" style="text-align: center;">Price Note</th>
						<th id="th_creator" style="text-align: center;">Created By</th>
					</tr>
				</thead>
				<tbody>
				<!--- Need to retrieve via PHP --->
				</tbody>
			</table>
		</div>
 
<?php } ?>

<?php include "include/footer.php"; ?>
