<?

	require_once('include/db.php');

	session_start();
	gateway(1);
	$title = 'Search Tags';

	if (isset($_GET['search']) || isset($_GET['advSearch'])) {

		$db = dbConnect();

		$where = array(
			"Num = ?",
			"Revision = ?",
			"CreationDate = ?",
			"Description LIKE ?",
			"Subcategory LIKE ?",
			"TagNotes LIKE ?",
			"PriceNotes LIKE ?",
			"InstallCost = ?",
			"Owner LIKE ?"
			);

		$sql = "SELECT Num, Revision, CreationDate, Description, Subcategory, TagNotes, InstallCost, PriceNotes, Owner FROM Tag AS T WHERE";
		$sql .= ' (' . join(' OR ', $where) . ')';

		$tag = $rev = $date = $desc = $category = $notes = $install = $price = $owner = '';

		if (isset($_GET['search'])) {
			$query = $_GET['query'];

			$tag = $rev = $install = $query;
			$date = $desc = $category = $notes = $price = $owner = '%' . $query . '%';
		} else {
			$tag = ($_GET['tag'] !== '' ? $_GET['tag'] : 'NULL');
			$rev = ($_GET['rev'] !== '' ? $_GET['rev'] : 'NULL');
			$date = ($_GET['date'] !== '' ? $_GET['date'] : 'NULL');
			$desc = ($_GET['desc'] !== '' ? '%' . $_GET['desc'] . '%' : '');
			$category = ($_GET['category'] !== '' ? '%' . $_GET['category'] . '%' : '');
			$notes = ($_GET['notes'] !== '' ? '%' . $_GET['notes'] . '%' : '');
			$price = ($_GET['price'] !== '' ? '%' . $_GET['price'] . '%' : '');
			$install = ($_GET['install'] !== '' ? $_GET['install'] : 'NULL');
			$owner = ($_GET['owner'] !== '' ? '%' . $_GET['owner'] . '%' : '');

		}

		if (!(isset($_GET['old']) && $_GET['old'] == 'yes')) {
			$sql .= " AND Revision = (SELECT MAX(Revision) FROM Tag WHERE Num = T.Num)";
		}

		$stmt = $db->prepare($sql);
		$query = $_GET['query'];
		$like = '%' . $query . '%';
		$stmt->bind_param("iisssssds", $tag, $rev, $date, $desc, $category, $notes, $price, $install, $owner);

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
	<div class="large-3" <?php if (isset($_GET['advSearch'])) { echo 'style="display: none;"'; } ?>id="simpleSearch">
		<form action="searchTag.php" method="GET">
			<table class="table table-bordered table-striped" style="width: 25%;">
				<tr><td><input type="text" name="query" id="userSearch" style="width: 100%;" placeholder="Enter Search Criteria" <?php if (isset($_GET['query'])) { echo 'value="'.$_GET['query'].'"'; } ?> /></td></tr>
			</table>
			<input type="checkbox" name="old" style="margin-top: 10px;" value="yes" <?php if (isset($_GET['old'])) { echo 'checked="checked"'; } ?> /> Include Older Revisions?
			<br />
			<button class="btn btn-s btn-success" type="submit" name="search" style="margin-top: 10px;">Search</button>
		</form>
	</div>

	<div class="large-3" <?php if (!isset($_GET['advSearch'])) { echo 'style="display: none;"'; } ?> id="advancedSearch">
		<form action="searchTag.php" method="GET">
			<table class="table table-bordered table-striped" style="width: 55%;">
				<tr><td><strong>Tag Number:</strong></td><td><input type="text" name="tag" id="userSearch" style="margin-right: 10px;" <?php if (isset($_GET['tag'])) { echo 'value="'.$_GET['tag'].'"'; } ?> /></td><td><strong>Notes:</strong></td><td><input type="text" name="notes" id="userSearch" style="margin-right: 10px;"  <?php if (isset($_GET['notes'])) { echo 'value="'.$_GET['notes'].'"'; } ?> /></td></tr>
				<tr><td><strong>Revision:</strong></td><td><input type="text" name="rev" id="userSearch" style="margin-right: 10px;" <?php if (isset($_GET['rev'])) { echo 'value="'.$_GET['rev'].'"'; } ?> /></td><td><strong>Install Cost:</strong></td><td><input type="text" name="cost" id="userSearch" style="margin-right: 10px;" <?php if (isset($_GET['cost'])) { echo 'value="'.$_GET['cost'].'"'; } ?> /></td></tr>
				<tr><td><strong>Date:</strong></td><td><input type="text" name="date" id="userSearch" style="margin-right: 10px;" <?php if (isset($_GET['date'])) { echo 'value="'.$_GET['date'].'"'; } ?> /></td><td><strong>Price Note:</strong></td><td><input type="text" name="price" id="userSearch" style="margin-right: 10px;" <?php if (isset($_GET['price'])) { echo 'value="'.$_GET['price'].'"'; } ?> /></td></tr>
				<tr><td><strong>Description:</strong></td><td><input type="text" name="desc" id="userSearch" style="margin-right: 10px;" <?php if (isset($_GET['desc'])) { echo 'value="'.$_GET['desc'].'"'; } ?> /></td><td><strong>Created By:</strong></td><td><input type="text" name="owner" id="userSearch" style="margin-right: 10px;" <?php if (isset($_GET['owner'])) { echo 'value="'.$_GET['owner'].'"'; } ?> /></td></tr>
				<tr><td><strong>Sub-Category:</strong></td><td><input type="text" name="category" id="userSearch" style="margin-right: 10px;" <?php if (isset($_GET['category'])) { echo 'value="'.$_GET['category'].'"'; } ?> /></td></tr>
			</table>
			<input type="checkbox" name="old" style="margin-top: 15px;" value="yes" <?php if (isset($_GET['old'])) { echo 'checked="checked"'; } ?> /> Include Older Revisions?
			<br />
			<button class="btn btn-s btn-success" type="submit" name="advSearch" style="margin-top: 10px;">Search</button>
		</form>
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