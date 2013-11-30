<!DOCTYPE html>
<html>
<head>
        <title>Add User</title>
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
</head>
<body>
        <section class="adduser">

                <center><img src ="images/logo.gif"></center><br/>
                <form name="addUser" action="addUser.php" method="post" accept-charset="utf-8">
                        <ul>
                                <li> Username: <input type="text" name="user" placeholder="Username" required /></li>
                                <li> Password: <input type="password" name="pass" placeholder="Password" required /></li>
                                <li> Confirm Password: <input type="password" name="confirmPass" placeholder="Retype Password" required /></li>
                                <li>
                                    Group:
                                    <select name="select">
                                        <option value="admin"> Administrator </option>
                                        <option value="tmember"> Tag Members </option>
                                        <option value="oe"> OE </option>
                                        <option value="user"> User </option>
                                    </select>
                                </li>
                                <li><input type="submit" name="submit" value="Create User" /></li>
                        </ul>
                </form>
        </section>
</body>
</html>
