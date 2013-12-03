<?

	require_once('include/db.php');

	session_start();
	gateway(1);
	$title = 'Search Tags';

	if (isset($_GET['query'])) {

		$db = dbConnect();

		$sql = "SELECT Num, Revision, CreationDate, Description, Subcategory, TagNotes, InstallCost, PriceNotes, Owner 
			FROM Tag AS T WHERE (Num = ? OR Description LIKE ?)";

		if (!(isset($_GET['old']) && $_GET['old'] == 'yes')) {
			$sql .= " AND Revision = (SELECT MAX(Revision) FROM Tag WHERE Num = T.Num)";
		}

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
	<div class="btn-group">
  		<button type="button" class="btn btn-default" onClick="toggleSimpleSearch();" >Simple</button>
  		<button type="button" class="btn btn-default" onClick="toggleAdvSearch();">Advanced</button>
	</div>
</div>
	<div class="large-3" id="simpleSearch">
		<form action="searchTag.php" method="GET">
			<table class="table table-bordered table-striped" style="width: 25%;">
				<tr><td><input type="text" name="query" id="userSearch" style="width: 100%;" placeholder="Enter Search Criteria" <?php if (isset($_GET['query'])) { echo 'value="'.$_GET['query'].'"'; } ?> /></td></tr>
			</table>
			<input type="checkbox" name="old" style="margin-top: 10px;" value="yes" <?php if (isset($_GET['old'])) { echo 'checked="checked"'; } ?> /> Include Older Revisions?
			<br />
			<button class="btn btn-s btn-success" type="submit" name="search" style="margin-top: 10px;">Search</button>
	</div>

	<div class="large-3" style="display: none;" id="advancedSearch">
		<form action="searchTag.php" method="GET">
		<table class="table table-bordered table-striped" style="width: 55%;">
			<tr><td><strong>Tag Number:</strong></td><td><input type="text" name="query" id="userSearch" style="margin-right: 10px;" placeholder="Enter a Tag No." <?php if (isset($_GET['query'])) { echo 'value="'.$_GET['query'].'"'; } ?> /></td><td><strong>Notes:</strong></td><td><input type="text" name="query" id="userSearch" style="margin-right: 10px;" placeholder="Enter Notes" <?php if (isset($_GET['query'])) { echo 'value="'.$_GET['query'].'"'; } ?> /></td></tr>
			<tr><td><strong>Revision:</strong></td><td><input type="text" name="query" id="userSearch" style="margin-right: 10px;" placeholder="Enter a Revision No." <?php if (isset($_GET['query'])) { echo 'value="'.$_GET['query'].'"'; } ?> /></td><td><strong>Install Cost:</strong></td><td><input type="text" name="query" id="userSearch" style="margin-right: 10px;" placeholder="Enter Install Cost" <?php if (isset($_GET['query'])) { echo 'value="'.$_GET['query'].'"'; } ?> /></td></tr>
			<tr><td><strong>Date:</strong></td><td><input type="text" name="query" id="userSearch" style="margin-right: 10px;" placeholder="Enter a Date" <?php if (isset($_GET['query'])) { echo 'value="'.$_GET['query'].'"'; } ?> /></td><td><strong>Price Note:</strong></td><td><input type="text" name="query" id="userSearch" style="margin-right: 10px;" placeholder="Enter a Price Note" <?php if (isset($_GET['query'])) { echo 'value="'.$_GET['query'].'"'; } ?> /></td></tr>
			<tr><td><strong>Description:</strong></td><td><input type="text" name="query" id="userSearch" style="margin-right: 10px;" placeholder="Enter a Description" <?php if (isset($_GET['query'])) { echo 'value="'.$_GET['query'].'"'; } ?> /></td><td><strong>Created By:</strong></td><td><input type="text" name="query" id="userSearch" style="margin-right: 10px;" placeholder="Enter Creator" <?php if (isset($_GET['query'])) { echo 'value="'.$_GET['query'].'"'; } ?> /></td></tr>
			<tr><td><strong>Sub-Category:</strong></td><td><input type="text" name="query" id="userSearch" style="margin-right: 10px;" placeholder="Enter a Sub-Category" <?php if (isset($_GET['query'])) { echo 'value="'.$_GET['query'].'"'; } ?> /></td></tr>
			</table>
			<input type="checkbox" name="old" style="margin-top: 15px;" value="yes" <?php if (isset($_GET['old'])) { echo 'checked="checked"'; } ?> /> Include Older Revisions?
			<br />
			<button class="btn btn-s btn-success" type="submit" name="search" style="margin-top: 10px;">Search</button>
	</div>

	<div class="lead">
		<div class="alert-box success radius" id="userResult" style="display:none;"></div>
		<div class="alert-box alert radius" id="userError" style="display:none;"></div>
	</div>
	<?php if (!empty($searchResults)) { ?>
		<hr />
		<div>
			<p> <h3>Search Results:</h3> </p>
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
				<?php foreach($searchResults as $tag) { ?>
					<tr>
						<th>
							<a href="viewTag.php?tag=<?php echo $tag['Num']; ?>&rev=<?php echo $tag['Revision'];?>">
								<?php echo $tag['Num']; ?>
							</a>
						</th>
						<th><?php echo $tag['Revision']; ?></th>
						<th><?php echo $tag['CreationDate']; ?></th>
						<th><?php echo $tag['Description']; ?></th>
						<th><?php echo $tag['Subcategory']; ?></th>
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

<script type="text/javascript"> 
  

          function toggleAdvSearch() { 

             var simple = document.getElementById('simpleSearch'); 
             var advanced = document.getElementById('advancedSearch');

		simple.style.display = "none";
		advanced.style.display = "inline"; 
          }

          function toggleSimpleSearch() { 

             var simple = document.getElementById('simpleSearch'); 
             var advanced = document.getElementById('advancedSearch');

		simple.style.display = "inline";
		advanced.style.display = "none"; 
          }  

</script> 
<?php include "include/footer.php"; ?>