<?

	require_once('include/db.php');

	session_start();
	$title = 'Search a Tag';

	echo "<br /><Br /><Br />";

	if (isset($_GET['query'])) {

		$db = dbConnect();

		$sql = "SELECT Num, Revision, CreationDate, Description, Subcategory, TagNotes, InstallCost, PriceNotes, Owner 
			FROM Tag WHERE Num = ? OR Description LIKE ?";
		$stmt = $db->prepare($sql);
		$query = $_GET['query'];
		$like = '%' . $query . '%';
		$stmt->bind_param("is", $query, $like);

		$stmt->execute();
		$stmt->bind_result($num, $revision, $creationDate, $description, $category, $notes, $cost, $priceNotes, $owner);

		$searchResults = array();
		while ($stmt->fetch()) {
			$searchResults[] = array(
				'Num' => $num,
				'Revision' => $revision,
				'CreationDate' => $creationDate,
				'Description' => $description,
				'Subcategory' => $category,
				'Notes' => $notes,
				'InstallCost' => $cost,
				'PriceNotes' => $priceNotes,
				'Owner' => $owner
				);
		}

	}



?>

<?php include "include/header.php"; ?>

<div class="page-header">
	<h1>Search a Tag</h1>

</div>
	<div class="large-3">
		<form action="searchTag.php" method="GET">
			<input type="text" name="query" id="userSearch" placeholder="Enter a Tag" <?php if (isset($_GET['query'])) { echo 'value="'.$_GET['query'].'"'; } ?> />
			<input type="submit" name="search" value="Search" />
	</div>
	<div class="lead">
		<div class="alert-box success radius" id="userResult" style="display:none;"></div>
		<div class="alert-box alert radius" id="userError" style="display:none;"></div>
	</div>
	<?php if (!empty($searchResults)) { ?>
		<div>
			<table class="table table-bordered" id="tags_table">
				<thead>
					<tr>
						<th id="th_tag">Tag</th>
						<th id="th_rev">Rev</th>
						<th id="th_date">Date</th>
						<th id="th_desc">Description</th>
						<th id="th_subcat">Sub Cat.</th>
						<th id="th_hvl">HVL</th>
						<th id="th_cc4">HVL CC4</th>
						<th id="th_mclad">Metal Clad</th>
						<th id="th_mvcc"> MV MCC</th>
						<th id="th_spec">Special Items</th>
						<th id="th_nodes">Notes</th>
						<th id="th_install">Install Cost</th>
						<th id="th_pricenote">Price Note</th>
						<th id="th_creator">Created By</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($searchResults as $tag) { ?>
					<tr>
						<th><?php echo $tag['Num']; ?></th>
						<th><?php echo $tag['Revision']; ?></th>
						<th><?php echo $tag['CreationDate']; ?></th>
						<th><?php echo $tag['Description']; ?></th>
						<th><?php echo $tag['Subcategory']; ?></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th><?php echo $tag['Notes']; ?></th>
						<th><?php echo $tag['InstallCost']; ?></th>
						<th><?php echo $tag['PriceNotes']; ?></th>
						<th><?php echo $tag['Owner']; ?></th>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } ?>
</div>

<?php include "include/footer.php"; ?>