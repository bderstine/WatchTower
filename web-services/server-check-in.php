<?php
/*
Service Name: Server Check-In
Description: Update this description before going live with the site!
Author: Brad Derstine
Version: 1.0
Last Update: 12/19/2011
*/

require("../_config.php");
require("../_functions.php");
$servicename = "Server Check-In";
$action = "checkin";

// TODO: List all class and extra values for jquery validation since that's included with this script
// array("Display Name", "Form Name", "Class", "Extra");
// Class: required, email, 
// Extra: minlength='2', 

$req = array(); 
$req[] = array("Hostname", "hostname", "required", "");
$req[] = array("IP Address", "ipaddress", "required", "");
$req[] = array("Mac Address", "macaddress", "required", "");

// You should not need to edit below this line unless you know what your doing...
?>
<!DOCTYPE html>
<html>
<head>
<title>Server Check-In</title>
<link rel="stylesheet" type="text/css" media="all" href="../css/style.css" />

<script src="../js/jquery-latest.js"></script>
<script type="text/javascript" src="../js/jquery-validate.js"></script>
<script>
  $(document).ready(function(){
    $("#serviceForm").validate();
  });
</script>
</head>

<body>
<div id="wrapper">
<h2><a href="../ServiceCatalog.php"><?php echo $sitename; ?></a> - <a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>"><?php echo $servicename; ?></a></h2>

<form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"]; ?>" id="serviceForm">

<?php foreach($req as $r){ ?>
<div class="formrow">
    <div class="formlabel"><?php echo $r[0]; ?></div>
    <div class="forminput">
	<input type="text" name="<?php echo $r[1]; ?>" value="<?php if(isset($_POST[$r[1]])){ echo $_POST[$r[1]]; } ?>" class="<?php echo $r[2]; ?>" <?php echo $r[3]; ?> />
    </div>
</div><!-- .formrow -->
<?php } ?>

<div class="formrow">
  <div class="formlabel"></div>
  <div class="forminput"><input type="submit" value="Create Request"></div>
</div><!-- .formrow -->

</form>

<?php
if(isset($_REQUEST["message"]) && $_REQUEST["message"]=="1"){
    echo "<div class='message-success'>Your request was successfully processed!</div>";
}
?>

<?php
if(isset($_POST) && count($_POST)>0){
    $json_request = post_to_json($_POST,$action);
    echo $json_request;
    ?>
    <br><br><hr><br />
    Click "submit" below to send the request to the CommandCenter.
    <br /><br />
    <form method="post" action="<?php echo $ccurl; ?>">
    <textarea name="ccrequest" rows="10" cols="60"><?php echo $json_request; ?></textarea>
    <br /><br />
    <input type="radio" name="debug" value="debugoff" checked /> Debug Off<br />
    <input type="radio" name="debug" value="debugon" /> Debug On
    <br /><br />
    <input type="submit" value="submit">
    </form>
<?php
} // endif isset
?>

<a href="../ServiceCatalog.php">Back to Service Catalog</a><br/><br/>
</div><!-- #wrapper -->
<?php showCopyright(); ?>
</body>
</html>