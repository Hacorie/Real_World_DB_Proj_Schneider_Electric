<?php
	$error = false;

	// Check if the user has submitted the login form
	if (isset($_POST['submit'])) {

		$db = new mysqli("mysql.cs.mtsu.edu", "ncr2g", "donthackmebro", "ncr2g");
		
		if ($db->connect_errno) {
			echo "Failed to connect to MySQL: " . $db->connect_error;
		}

		// Check for the username/password combination
		$stmt = $db->prepare("SELECT UName FROM User WHERE Uname = ? AND Password = ?");
		$stmt->bind_param("ss", $_POST['username'], sha1($_POST['password']));
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			// Logged in successfully

			// Store username in the SESSIONS variable
			$stmt->bind_result($username);
			$stmt->fetch();

			session_start();
			$_SESSION['username'] = $username;

			// Set a cookie with the username and token
			$token = uniqid(mt_rand(), true);
			$expire = time() + 60*60*24*90; // 90 days
			setcookie('username', $username, $expire);
			setcookie('token', $token, $expire);

			$stmt->close();

			// Add an entry to the log
			$sql = "INSERT INTO Log_In(UName, Initial_Date, Expire_Date, IP, MName, Token) 
					VALUES (?, CURRENT_DATE, ADDDATE(CURRENT_DATE, INTERVAL 90 DAY), ?, ?, ?)";
			$stmt = $db->prepare($sql);

			//$host = $_SERVER['REMOTE_HOST']
			$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			
			$stmt->bind_param("ssss", $username, $_SERVER['REMOTE_ADDR'], $host, $token);
			$stmt->execute();
			$stmt->close();

			$db->close();

			// Redirect to homepage
			header('Location: homepage.php');
		} else {
			$error = true;
		}


	}

	// Check if the user has a saved login session
	if (isset($_COOKIE['username']) && isset($_COOKIE['token'])) {

		// Connect to db if not already connected
		if (!isset($db)) {
			$db = new mysqli("mysql.cs.mtsu.edu", "ncr2g", "donthackmebro", "ncr2g");
		
			if ($db->connect_errno) {
				echo "Failed to connect to MySQL: " . $db->connect_error;
			}
		}

		// Check for the username/password combination
		$stmt = $db->prepare("SELECT UName FROM Log_In WHERE Uname = ? AND Token = ? 
			AND Expire_Date >= CURRENT_DATE AND IP = ?");
		$stmt->bind_param("sss", $_COOKIE['username'], $_COOKIE['token'], $_SERVER['REMOTE_ADDR']);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			// Logged in successfully

			// Store username in the SESSIONS variable
			$stmt->bind_result($username);
			$stmt->fetch();

			session_start();
			$_SESSION['username'] = $username;

			$stmt->close();

			$db->close();

			// Redirect to homepage
			header('Location: homepage.php');
		} else {
			$error = true;
		}


	}



	if (isset($db)) {
		$db->close();
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Schneider Electric</title>
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
</head>
<body>
	<section class="loginform cf">

		<center><img src ="images/logo.gif"></center><br/>
		<form name="login" action="index.php" method="post" accept-charset="utf-8">
			<ul>
				<?php if ($error) { ?>
					<li>
						Invalid Username or Password
					</li>
				<?php } ?>
				<li>
					<input type="text" name="username" placeholder="Username" required>
				</li>
				<li>
					<input type="password" name="password" placeholder="Password" required></li>
				<li>
					<input type="submit" name="submit" value="Login">
				</li>
			</ul>
		</form>
	</section>
</body>
</html>