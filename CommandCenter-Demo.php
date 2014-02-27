<?php
// CommandCenter by Brad Derstine, v1.0
// This requires PHP 5 >= 5.2.0, PECL json >= 1.2.0
require("_functions.php");

if(isset($_POST["ccrequest"]) && $_POST["ccrequest"]!=""){
    $indate = time();
    $ipaddr = $_SERVER["REMOTE_ADDR"];
    $json_data = $_POST["ccrequest"];
    $json_array = jsonDecode($json_data);
}

if(isset($_POST["debug"]) && $_POST["debug"]=="debugoff"){
    echo "<h1>It works!</h1>";  
    echo "<strong>This is where the request would be processed if this wasn't a demonstration.</strong><br/>";
    echo "I intentionally moved this outside of the presentation layer in case I want to do header redirects after processing.<br/><br/>";
    echo "Use \$json_array in your new functions. Below is what it looks like.<br/><br/>";
    echo "<pre>";
    var_dump($json_array);
    echo "</pre>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>WatchTower - CommandCenter</title>
<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
</head>

<body>
<div id="wrapper">
<h1>CommandCenter</h1>
<?php
if(isset($_POST["ccrequest"]) && $_POST["ccrequest"]!=""){
    echo "<strong>The following information would be used if this wasn't in debug mode.</strong><br/><br/>";
    echo "Date: ".date("F j, Y, g:i a",$indate)."<br>";
    echo "Remote IP: ".$ipaddr."<br>";
    echo "Request: ".$_POST["ccrequest"];
    echo "<br/><br/>";
    echo "<hr><br/>";
    echo "<pre>";
    var_dump($json_array);
    echo "</pre>";
} else {
  echo "This page should not be called directly or your POST data was invalid/corrupt.";
}
echo "<br/><hr><br/>";
echo "<a href='javascript: history.go(-1)'>Back to Service</a> | <a href='ServiceCatalog.php'>Return to Service Catalog</a>";
echo "<br/><br/>";
?>
<?php showCopyright(); ?>
</div><!-- #wrapper -->
</body>
</html>