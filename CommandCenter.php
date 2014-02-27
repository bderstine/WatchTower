<?php
// CommandCenter by Brad Derstine, v1.0
// This requires PHP 5 >= 5.2.0, PECL json >= 1.2.0
require("_config.php");
require("_functions.php");

$id = uniqid(); //generate unique id for server creation and audit log association

if(isset($_POST["ccrequest"]) && $_POST["ccrequest"]!=""){
    //This is used when the request is coming from a web service form.
    $json_data = $_POST["ccrequest"];
    $client = 0;
}
else{
    //This is used when a python client is doing a direct POST to the site.
    $json_data = json_encode($_POST);
    $client = 1;
}

if(isset($_POST["debug"]) && $_POST["debug"]=="debugon"){
    $indate = time();
    $ipaddr = $_SERVER["REMOTE_ADDR"];
    echo "<strong>The following information would be used if this wasn't in debug mode.</strong><br/><br/>";
    echo "Date: ".date("F j, Y, g:i a",$indate)."<br>";
    echo "Remote IP: ".$ipaddr."<br>";
    echo "Request: ".$json_data;
    echo "<br/><br/>";
    echo "<hr><br/>";
    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";
    exit;
}

add_audit_log($id,$json_data,$client);

$json_array = jsonDecode($json_data);
$action = $json_array["action"];

switch($action){

case "checkin":
    server_check_in($id,$json_array);
    if($client==1){
        echo "Your request has been processed successfully!";
    } else {
        header("Location: https://bizzarnet.com/watchtower/web-services/server-check-in.php?message=1");        
    }
    break;
case "updateservices":
    update_services($id,$json_array);
    break;
    
case "getserverid":
    get_server_id($json_array);
    break;
case "getserverinfo":
    var_dump($json_array);
    get_server_info($json_array);
    break;
    
default:
    echo "Unknown action: ".$action;
}
exit;
?>