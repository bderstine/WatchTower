<?php
// Watchtower - _functions.php
// By: Brad Derstine, 12/10/2011

// These are the global settings used throughout the WatchTower application
//1 = on, 0 = off
$unittestsubmit = 1; 

// Below are the functions used throughout the WatchTower application
function dbconnect(){
    //include("_config.php");//Don't understnad why this doesn't work... :(
    
    //############## This should be moved to _config.php... ###############
    $dbserver = "localhost";
    $dbuser   = "root";
    $dbpass   = "z3bra1980";
    $dbname   = "bizzar_watchtower";
    //############## This should be moved to _config.php... ###############
    
    $dbh = mysql_connect($dbserver, $dbuser, $dbpass) or
        die ('I cannot connect to the database because: ' . mysql_error());
    mysql_select_db($dbname);
}

function showCopyright(){
    echo "<div class='copyright'>&copy; 2011-2013 <a href='mailto:bderstine@gmail.com'>Brad Derstine</a>, ";
    echo "<a href='https://sourceforge.net/projects/watchtower/'>WatchTower</a>, ";
    echo "<a href='http://www.bizzartech.com/watchtower/'>BizzarTech.com</a></div><br/><br/><br/>";
}

function jsonDecode($json){
    //http://us2.php.net/manual/en/function.json-decode.php#105259
    $json = str_replace(array("\\\\", "\\\""), array("&#92;", "&#34;"), $json);
    $parts = preg_split("@(\"[^\"]*\")|([\[\]\{\},:])|\s@is", $json, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    foreach ($parts as $index => $part){ 
        if (strlen($part) == 1){ 
            switch ($part) { 
                case "[": 
                case "{": 
                    $parts[$index] = "array("; 
                    break; 
                case "]": 
                case "}": 
                    $parts[$index] = ")"; 
                    break; 
                case ":": 
                    $parts[$index] = "=>"; 
                    break;    
                case ",": 
                    break; 
                default: 
                    return null; 
            } 
        } else { 
            if ((substr($part, 0, 1) != "\"") || (substr($part, -1, 1) != "\"")){ 
                return null;
            } 
        } 
    } 
    $json = str_replace(array("&#92;", "&#34;", "$"), array("\\\\", "\\\"", "\\$"), implode("", $parts));
    return eval("return $json;"); 
}

function do_post_request($url, $fields, $optional_headers = null){
    // Create URL parameter string
    $fields_string="";
    foreach($fields as $key => $value){
        $fields_string .= $key.'='.$value.'&';
        $fields_string = rtrim( $fields_string, '&' );
    }
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url);
    curl_setopt( $ch, CURLOPT_POST, count( $fields ) );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );
    $result = curl_exec( $ch );
 
    curl_close( $ch );
    return $result;
}


//###############################################################################################################
//
//  The functions below this point are used within the CommandCenter. Only edit if you know what you're doing!
//
//###############################################################################################################

function add_audit_log($id,$json_data,$client){
    dbconnect();
    $remoteip = $_SERVER["REMOTE_ADDR"];
    $safejson = addslashes($json_data);
    $add = "INSERT INTO audit_log SET json_data='".$safejson."', remoteip='".$remoteip."', indate='".time()."', client='".$client."', screcord='".$id."';";
    $addres = mysql_query($add);
}

function post_to_json($postdata,$action){
    $servicedata = array();
    foreach($postdata as $k => $v){ $data["$k"] = $v; }
    $data["action"]=$action;
    $json_request = json_encode($data);
    return $json_request;
}

function server_check_in($id,$data){
    dbconnect();
    $host = $data["hostname"];
    $ip = $data["ipaddress"];
    $mac = $data["macaddress"];
    $check = "SELECT * FROM servers WHERE ACTIVE=1 AND hostname='".$host."' AND ipaddress='".$ip."' AND macaddress='".$mac."';";
    $checkres = mysql_query($check);
    $checknum = mysql_num_rows($checkres);
    if($checknum > 0){
        $query = "UPDATE servers SET lastupdate='".time()."' WHERE ACTIVE=1 AND hostname='".$host."' AND ipaddress='".$ip."' AND macaddress='".$mac."';";
    } else {
        $query = "INSERT INTO servers SET serverid='".$id."', hostname='".$host."', ipaddress='".$ip."', macaddress='".$mac."', indate='".time()."', lastupdate='".time()."';";        
    }
    $queryres = mysql_query($query);
}

//###############################################################################################################

function get_server_id($data){
    dbconnect();
    
    $host = $data["hostname"];
    $ip = $data["ipaddress"];
    $mac = $data["macaddress"];
    
    $get = "SELECT serverid FROM servers WHERE ACTIVE=1 AND hostname='".$host."' AND ipaddress='".$ip."' AND macaddress='".$mac."';";
    $getres = mysql_query($get);
    $getnum = mysql_num_rows($getres);

    if($getnum>0){
        $g = mysql_fetch_assoc($getres);
        echo json_encode($g);
        return 1;
    } else {
        echo "Problem";
        return 0;
    }
}
//noticed the pattern building between different services...
//is it possible to create dynamic services based on $data?? TODO!
function get_server_info($data){
    dbconnect();

    $serverid = $data["serverid"];

    $get = "SELECT * FROM servers WHERE serverid='".$serverid."';";
    $getres = mysql_query($get);
    $getnum = mysql_num_rows($getres);
    
    if($getnum>0){
        $g = mysql_fetch_assoc($getres);
        echo json_encode($g);
        return 1;
    } else {
        echo "Problem";
        return 0;
    }
}
?>
