<?php
session_start();
// Login by Brad Derstine, v1.0
require("_config.php");
require("_functions.php");


if(isset($_REQUEST["do"]) && $_REQUEST["do"]=="logout"){
    $_SESSION=array();
    header("Location: index.php");
    exit;
}
    
if(isset($_POST["username"]) && $_POST["username"]!=""){
    echo "Username: ".$_POST["username"];
    $_SESSION["wt_username"]=$_POST["username"];
    header("Location: Dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>BizzarNet Watchtower</title>
<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
</head>

<body>
<div id="wrapper">
    <div id="header">
        <h1><a href="<?php echo $sitehome; ?>"><?php echo $sitename; ?></a></h1>
        <h2>Open-source cloud server monitoring and management</h2>
    </div>
    

    <form method="post" action="Login.php">
    <table>
        <tr><td>Username</td><td><input type="text" name="username"></td></tr>
        <tr><td>Password</td><td><input type="password" name="password"></td></tr>
        <tr><td></td><td><input type="submit" value="Login"></td></tr>
    </table>
    
    </form>
</div><!-- #wrapper -->
<?php showCopyright(); ?>
</body>
</html>