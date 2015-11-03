<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
    <title>Shockpot-Frontend | Fast Visualization for your Shockpot Honeypot Stats</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <link rel="stylesheet" href="styles/layout.css" type="text/css"/>
    <link rel="stylesheet" href="styles/tablesorter.css" type="text/css"/>
    <script type="text/javascript" src="scripts/jquery-1.4.1.min.js"></script>
    <script type="text/javascript" src="scripts/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="scripts/jquery.tablesorter.pager.js"></script>
</head>
<body id="top">
<div class="wrapper">
    <div id="header">
        <h1><a href="index.php">Shockpot-Frontend</a></h1>
        <br/>

        <p>Fast Visualization for your Shockpot Honeypot Stats</p>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="topbar">
        <div class="fl_left">Version: 1.0 | Website:  <a href="https://github.com/GovCERT-CZ/Shockpot-Frontend">github.com/GovCERT-CZ/Shockpot-Frontend</a>
        </div>
        <br class="clear"/>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="topnav">
        <ul class="nav">
            <li><a href="index.php">Homepage</a></li>
            <li><a href="shockpot-frontend.php">Shockpot-Frontend</a></li>
            <li><a href="shockpot-input.php">Shockpot-Input</a></li>
            <li class="active"><a href="shockpot-ip.php">Shockpot-Ip</a></li>
            <li><a href="shockpot-geo.php">Shockpot-Geo</a></li>
            <li class="last"><a href="gallery.php">Graph Gallery</a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div class="container">
        <div class="whitebox">
            <!-- ####################################################################################################### -->
            <h2>IP activity gathered from the honeypot system</h2>
            <hr/>
            <?php
            #Author: ikoniaris, s0rtega
            #Website: bruteforce.gr/kippo-graph
            #Modifications: standa4

            require_once('config.php');
            require_once(DIR_ROOT . '/class/ShockpotIP.class.php');

            $ShockpotIP = new ShockpotIP();

            //-----------------------------------------------------------------------------------------------------------------
            //APT-GET COMMANDS
            //-----------------------------------------------------------------------------------------------------------------
            $ShockpotIP->printOverallIpActivity();
            //-----------------------------------------------------------------------------------------------------------------
            //END
            //-----------------------------------------------------------------------------------------------------------------

            ?>
            <!-- ####################################################################################################### -->
            <div id="extended-ip-info"></div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div id="copyright">
        <p class="fl_left">Copyright &copy; 2015 - All Rights Reserved - <a
                href="https://github.com/GovCERT-CZ/Shockpot-Frontend">Shockpot-Frontend</a></p>

        <p class="fl_right">Thanks to <a href="http://bruteforce.gr/kippo-graph" title="Kippo-Graph">Kippo-Graphs</a> and <a href="http://www.os-templates.com/" title="Free Website Templates">OS Templates</a></p>
        <br class="clear"/>
    </div>
</div>
<script type="text/javascript" src="scripts/superfish.js"></script>
<script type="text/javascript">
    jQuery(function () {
        jQuery('ul.nav').superfish();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#Overall-IP-Activity")
            .tablesorter({sortList: [
                [3, 1]
            ], widthFixed: true, widgets: ['zebra']})
            .tablesorterPager({container: $("#pager1")});
    });
</script>
<script type="text/javascript">
    function getIPinfo(ip) {
        $.ajax({
            type: "POST",
            url: 'include/shockpot-ip.ajax.php',
            data: 'ip=' + ip,
            complete: function (response) {
                $('#extended-ip-info').html(response.responseText);

                $("#IP-attemps")
                    .tablesorter({widthFixed: true, widgets: ['zebra']})
                    .tablesorterPager({container: $("#pager2")});

                $("#IP-commands")
                    .tablesorter({widthFixed: true, widgets: ['zebra']})
                    .tablesorterPager({container: $("#pager3")});

            },
            error: function () {
                $('#output').html('Bummer: there was an error!');
            }
        });
        return false;
    }
</script>
</body>
</html>
