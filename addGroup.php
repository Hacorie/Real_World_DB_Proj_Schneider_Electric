<!DOCTYPE html>
<html>
<head>
        <title>Add Group</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
</head>
<body>
        <section class="addgroup">

                <center><img src ="images/logo.gif"></center><br/>
                <form name="addGroup" action="addGroup.php" method="post" accept-charset="utf-8">
                        <ul>
                                <li>Group Name: <input type="text" name="groupname" placeholder="GroupName" required /></li>
                                <li>Confirm Group Name:<input type="text" name="regroupname" placeholder="Retype Group Name" required /></li>
                                <li>
                                        Permissions: <br>
                                        Input Tags: <input type="checkbox" name="iTag" value="iTag"><br>
                                        Revist Tags: <input type="checkbox" name="rTag" value="rTag"><br>
                                        Search Tags: <input type="checkbox" name="sTag" value="sTag"><br>
                                        View Tags: <input type="checkbox" name="vTag" value="vTag"><br>
                                        View Prices: <input type="checkbox" name="vPrice" value="vPrice"><br>

                                </li>
                                <li><input type="submit" name="submit" value="Create Group" /></li>
                        </ul>
                </form>
        </section>
</body>
</html>