<?php
    function dbConnect() {
        $db = new mysqli("mysql.cs.mtsu.edu", "ncr2g", "donthackmebro", "ncr2g");
        
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: " . $db->connect_error;
            exit(1);
        }

        return $db;
    }

    session_start();

    $db = dbConnect();

    $errMsg = [];

    // If the form was submitted
    if (isset($_POST['submit'])) {

        $sql = "INSERT INTO Groups(GName) VALUES (?)";
        $stmt = $db->prepare($sql);
    
        $stmt->bind_param("s", $_POST['GName']);
        $stmt->execute();

        if ($db->affected_rows == 1) {

        } else {
            $errMsg[] = 'Error adding Group';
            $errMsg[] = $db->error;
        }
        $stmt->close();


    }

    // Get a list of groups
    $res = $db->query('SELECT GName from Groups');
    $groups = [];
    while ($row = $res->fetch_assoc()) {
        $groups[] = $row;
    }

?>


<!DOCTYPE html>
<html>
<head>
        <title>Manage Groups</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
</head>
<body>
        <section class="addgroup">

                <center><img src ="images/logo.gif"></center><br/>
                <ul>
                    <?php foreach($groups as $group) { ?>
                        <li><?php echo $group['GName'] ?></li>
                    <?php } ?>
                </ul>

                <form name="addGroup" action="groups.php" method="post" accept-charset="utf-8">
                        <ul>
                            <?php if (!empty($errMsg)) { ?>
                                <li><?php echo join('<br />', $errMsg); ?></li>
                            <?php } ?>
                            <li>Group Name: <input type="text" name="GName" placeholder="Group Name" required /></li>
                            <li><input type="submit" name="submit" value="Create Group" /></li>
                        </ul>
                </form>
        </section>
</body>
</html>
