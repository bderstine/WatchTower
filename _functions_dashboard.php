<?php

function dashboard_list_servers(){
    dbconnect();
    $get = "SELECT * FROM servers WHERE active=1;";
    $getres = mysql_query($get);
    $getnum = mysql_num_rows($getres);
    if($getnum>0){
        while($g = mysql_fetch_assoc($getres)){
            echo "<a href='Dashboard.php?show=server&serverid=".$g["serverid"]."'>".$g["hostname"]."</a><br>";
        }    
    } else {
        echo "0 servers!";
    }
}

/*
function dash_get_server_info($serverid){
    require("_config.php");
    $fields=array("action"=>"getserverinfo","serverid"=>"4eff7a19724de");
    $json_data = do_post_request($ccurl, $fields);
    $serverinfo = json_decode($json_data);
    return $serverinfo;
}

*/

function dash_get_server_info($serverid){
    dbconnect();
    $get = "SELECT * FROM servers WHERE serverid='".$serverid."' LIMIT 1;";
    $getres = mysql_query($get);
    $getrow = mysql_fetch_assoc($getres);
    //$getdata = json_encode($getrow);
    //return $getdata;
    return $getrow;
}
?>