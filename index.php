<?php
// Index by Brad Derstine, v1.0
require("_config.php");
require("_functions.php");
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

TODO: Make these app icons!!<br/><br/>
<a href="Dashboard.php">Dashboard</a> (login required, use demo/demo)<br/><br/>
<a href="ServiceCatalog.php">Service Catalog</a><br/><br/>
</div><!-- #wrapper -->
<?php showCopyright(); ?>
</body>
</html>