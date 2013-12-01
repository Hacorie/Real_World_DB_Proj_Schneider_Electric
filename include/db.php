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
	
?>
