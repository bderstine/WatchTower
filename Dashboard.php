<?php
session_start();
if(!isset($_SESSION["wt_username"])){ header("Location: Login.php"); }
// Dashboard by Brad Derstine, v1.0
require("_config.php");
require("_functions.php");
require("_functions_dashboard.php");

if(isset($_REQUEST["serverid"])){
    $serverid = $_REQUEST["serverid"];
    $serverinfo = dash_get_server_info($serverid);
    //var_dump($serverinfo);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>WatchTower Dashboard</title>
<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
<link rel="stylesheet" type="text/css" media="all" href="css/dashboard.css" /> 
</head>

<body>
<div id="wrapper">
    <div id="header">
        <h1><a href="<?php echo $sitehome; ?>"><?php echo $sitename; ?></a></h1>
        <h2>Dashboard</h2>
    </div>

    <div id="dash-sidebar-left">
        <div class="header-bar">Main Menu</div>
        <?php dashboard_list_servers(); ?><br/>
        <div class="sidebar-text"><a href="Login.php?do=logout">Logout</a> (<?php echo $_SESSION["wt_username"]; ?>)</div>
        <div class="sidebar-text"><br/><?php echo date("F j, Y g:i a",time()); ?></div>
    </div>

    <div id="dash-main-wrapper">
        <div class="header-bar"><a href="Dashboard.php">Dashboard</a> 
        <?php if(isset($_REQUEST["serverid"])){ ?>
            \ <a href="Dashboard.php?show=server&serverid=<?php echo $serverid; ?>"><?php echo $serverinfo["hostname"]; ?></a>
        <?php } ?>
        <?php if(isset($_REQUEST["show"]) && $_REQUEST["show"]!="server"){ ?>
            \ <?php echo $_REQUEST["show"]; ?>
        <?php } ?>
        </div>
        
        <?php if(!isset($_REQUEST["show"])){
            echo "Select a server from the menu on the left.";
        } 
        else {
            $show = $_REQUEST["show"];
            if($show=="request-log-files"){ ?>
                <h3>Log File Request Form</h3><br/>
                <div style="font-size:11px;">
                    Wildcard (*) includes all subdirectories. If not /*, then includes all files that match.<br/>
                    For example, "yum*" includes all files that start with "yum".
                </div>
                <br/><br/>
                <form method="post" action="actions.php?do=get-log-files">
                <input type="hidden" name="serverid" value="<?php echo $serverinfo["serverid"]; ?>">
                <input type="checkbox" name="logids" value="LOG1" /> /var/log/*
                <br/><br/>
                <input type="checkbox" name="logids" value="LOG2" /> /var/log/httpd/*
                <br/><br/>
                <input type="checkbox" name="logids" value="LOG3" /> /var/log/yum*
                <br/><br/>
                Email: <input type="text" name="email" size="35">
                <br/><br/>
                <input type="submit" value="Submit">
                </form>
            <?php
            }
            else{
            ?>
            <div id="dash-main-left">
                <h3>Admin Summary</h3>
                Server ID: <?php echo $serverinfo["serverid"]; ?><br/>
                Last Update: <?php echo date("m/d/y g:i a",$serverinfo["lastupdate"]); ?><br/>
                <a href="actions.php?do=delserver&serverid=<?php echo $serverid; ?>">Remove</a>
                <br/><br/>
                
                <h3>General</h3>
                Hostname: <?php echo $serverinfo["hostname"]; ?><br/>
                IP Address: <?php echo $serverinfo["ipaddress"]; ?><br/>
                Mac Address: <?php echo $serverinfo["macaddress"]; ?><br/>
                <!-- Manufacturer: <br/> -->
                <!-- Model: <br/> -->
                <!-- CPU Cores: <br/> -->
                <!-- Processor Type: <br/> -->
                <!-- Processor Sockets: <br/> -->
                <!-- Cores per Socket: <br/> -->
                <!-- Logical Processors: <br/> -->
                <!-- Hyperthreading: <br/> -->
                <!-- Number of NICs: <br/> -->
                <br/>
            </div>
            
            <div id="dash-main-right">
                <h3>Commands</h3>
                <a href="Dashboard.php?serverid=<?php echo $serverinfo["serverid"]; ?>&show=request-log-files">Request Log Files</a><br/>
                <a href="#">Enter Maintenance Mode</a><br/>
                <a href="#">Reboot</a><br/>
                <a href="#">Shutdown</a><br/>
            </div>
    
            <div style="clear:both;"></div>
            
            <div id="dash-main-resources">
                <h3>Resources</h3>
                
                CPU Usage<br/>
                <img src="/watchtower/images/generate_graph_cpu.png" width="600px"><br/><br/>
                Network Usage<br/>
                <img src="/watchtower/images/generate_graph_traffic.png" width="600px"><br/><br/>
                IO Usage<br/>
                <img src="/watchtower/images/generate_graph_io.png" width="600px"><br/><br/>
                
                TODO<br/>
                Data/Storage Usage<br/>
                Swap Usage<br/>
                I'm sure there's others to include... maybe make it customizable...
            </div>
            <?php } ?>
        <div style="clear:both;"></div>
        <?php } ?>
        
    </div>
    <div style="clear:both;"></div>
</div><!-- #wrapper -->
<?php showCopyright(); ?>
</body>
</html>