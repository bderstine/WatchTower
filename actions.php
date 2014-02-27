<?php
// Actions by Brad Derstine, v1.0
require("_config.php");
require("_functions.php");

// This page should never be called directly

if(isset($_REQUEST["do"])){
    if(isset($_REQUEST["serverid"])){ $serverid = $_REQUEST["serverid"]; } else { $serverid = ""; }
    dbconnect();
    $action = $_REQUEST["do"];
    if($action=="get-log-files"){
        echo "Getting log files!";
    }
    if($action=="delserver"){
        if(isset($_REQUEST["confirm"])){
            $u = "UPDATE servers SET active=0 WHERE serverid='".$serverid."';";
            $ures = mysql_query($u);
            header("Location: Dashboard.php");
        } else {
            echo "Are you sure?<br/>";
            echo "<a href='actions.php?do=delserver&serverid=".$serverid."&confirm=1'>Yes, disable this server record.</a><br/><br/>";
            echo "This will only disable the server record to allow the creation of a new one.<br/><br/>";
            echo "<a href='Dashboard.php?show=server&serverid=".$serverid."'>No, do not disable this server record.</a>";
        }

    }
    else{
        echo "Unknown action: ".$action;
    }
    exit;
}
else{
    echo "This page should not be called directly. <a href='index.php'>Click here to continue!</a>";
}

?>