<?php
	
	require_once('include/db.php');

	session_start();
	$title = 'View Tag';

	// Verify that a valid tag was specified
	if (isset($_GET['tag']) && isset($_GET['rev'])) {


		// Get the tag
		$db = dbConnect();
		$stmt = $db->prepare("SELECT Num, Revision, CreationDate, Description, Subcategory, TagNotes, InstallCost, PriceNotes, Owner
		 FROM Tag WHERE Num = ? AND Revision = ?");
		$stmt->bind_param("ii", $_GET['tag'], $_GET['rev']);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {

			$stmt->bind_result($num, $revision, $creationDate, $description, $category, $notes, $cost, $priceNotes, $owner);

			$stmt->fetch();
			$stmt->close();

			$tag = array(
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

			
		} else {
			$error = "Invalid Tag Number or Revision";
		}

	}




?>

<?php include "include/header.php"; ?>

<?php if (isset($tag)) { ?>
	<?php print_r($tag); ?>
<?php } else { ?>
	Try searching for a tag.
<?php } ?>

<?php include "include/footer.php"; ?>