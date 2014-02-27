<?php
// ServiceCatalog by Brad Derstine, v1.0
// This file reads meta data from web-services folder contents and displays
require("_config.php");
require("_functions.php");

$path = "web-services/";
if(isset($_REQUEST["show"])){
    $show = $_REQUEST["show"];
} else {
    $show = "";
}

$services = array();
$d = dir($path);
while (false !== ($entry = $d->read())) {
   if($entry!="." && $entry!=".." && !is_dir($path.$entry) && ($show=="all" || substr($entry,0,4)=="get-")){
    // !is_dir($path.$entry) was added to avoid/ignore directories in $path
	// may want to convert this to read dirs as plugin folders, instead of ignoring, TODO
	// ($show=="all" || substr($entry,0,4)=="get-") was added to filter get requests but still allow all to show
	$servicefile = $path.$entry;
	$servicefilename = $entry;
    $servicemeta = file($servicefile);
	$servicename = str_replace("Service Name: ","",$servicemeta[2]);
	$servicename = str_replace("\n","",$servicename );
	$description = str_replace("Description: ","",$servicemeta[3]);
	$description = str_replace("\n","",$description );
	$author = str_replace("Author: ","",$servicemeta[4]);
	$author = str_replace("\n","",$author );
	$version = str_replace("Version: ","",$servicemeta[5]);
	$version = str_replace("\n","",$version );
	$lastupdate = str_replace("Last Update: ","",$servicemeta[6]);
	$lastupdate = str_replace("\n","",$lastupdate );
	if($servicename!=""){ $services[] = array(
	    "servicename"=>$servicename, 
	    "description"=>$description, 
	    "author"=>$author, 
	    "version"=>$version, 
	    "servicefile"=>$servicefile, 
	    "servicefilename"=>$servicefilename, 
	    "lastupdate"=>$lastupdate
	); }
   }
}
$d->close();
rsort($services);
?>
<!DOCTYPE html>
<html>
<head>
<title>Service Catalog</title>
<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
</head>

<body>
<div id="wrapper">
    <div id="header">
        <h1><a href="<?php echo $sitehome; ?>"><?php echo $sitename; ?></a></h1>
        <h2>Service Catalog</h2>
    </div>

TODO: Make these app icons!!<br/><br/>
<?php
sort($services);
foreach($services as $s){
    ?>
    <div class='serviceinfo'>
        <strong><a href="<?php echo $s["servicefile"]; ?>"><?php echo $s["servicename"]; ?></a> - <?php echo $s["version"]; ?></strong> - <?php echo $s["author"]; ?> - <?php echo $s["lastupdate"]; ?><br/>
        <?php echo $s["description"]; ?>
    </div>
<?php
}

if($show==""){?>
    <div class='serviceinfo'>
        <strong><a href="ServiceCatalog.php?show=all">Show All Services</a></strong><br/>
    </div>
<?php
} else { ?>
    <div class='serviceinfo'>
        <strong><a href="ServiceCatalog.php">Filter Get Services</a></strong><br/>
        This will filter services to only show "get" services.
    </div>
<?php
}
?>
</div><!-- #wrapper -->
<?php showCopyright(); ?>
</body>
</html>