<?php
	// Generate a unique token for authentication
	function generateToken() {
		return uniqid(mt_rand(), true);
	}

	function dbConnect() {
		$db = new mysqli("mysql.cs.mtsu.edu", "ncr2g", "donthackmebro", "ncr2g");
		
		if ($db->connect_errno) {
			echo "Failed to connect to MySQL: " . $db->connect_error;
			exit(1);
		}

		return $db;
	}
	
	function dbQuery($db, $query) {
		$res = $db->query($query);
		$rows = Array();
		while ($row = $res->fetch_assoc()) {
			$rows[] = $row;
		}

		return $rows;
	}

	function gateway($role) {
		if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] < $role) {
			header('Location: index.php');
		}
	}
?>
