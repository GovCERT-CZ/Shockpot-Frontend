<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
    <title>Shockpot-Frontend | Fast Visualization for your Shockpot Honeypot Stats</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <link rel="stylesheet" href="styles/layout.css" type="text/css"/>
    <script type="text/javascript" src="scripts/jquery-1.4.1.min.js"></script>
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
            <li class="active"><a href="shockpot-frontend.php">Shockpot-Frontend</a></li>
            <li><a href="shockpot-input.php">Shockpot-Input</a></li>
            <li><a href="shockpot-ip.php">Shockpot-Ip</a></li>
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
<!-- ############################# -->
<h2>Overall honeypot activity</h2>
<hr/>
<?php
#Author: ikoniaris
#Website: bruteforce.gr/kippo-graph
#Modifications: standa4

require_once('config.php');
require_once(DIR_ROOT . '/class/ShockpotFrontend.class.php');

$ShockpotFrontend = new ShockpotFrontend();

//Let's create all the charts! (generated-graphs folder)
$ShockpotFrontend->createTop10commands();
$ShockpotFrontend->createTop10paths();
$ShockpotFrontend->createShellshockRation();
$ShockpotFrontend->createMostShellshockAttacksPerDay();
$ShockpotFrontend->createShellshocksPerDay();
$ShockpotFrontend->createShellshocksPerWeek();
$ShockpotFrontend->createNumberOfConnectionsPerIP();
$ShockpotFrontend->createShellshockAttacksFromSameIP();
$ShockpotFrontend->createMostProbesPerDay();
$ShockpotFrontend->createProbesPerDay();
$ShockpotFrontend->createProbesPerWeek();

//-----------------------------------------------------------------------------------------------------------------
//OVERALL HONEYPOT ACTIVITY
//-----------------------------------------------------------------------------------------------------------------
$ShockpotFrontend->printOverallHoneypotActivity();

echo '<br /><br />';
?>
<h2>Graphical statistics generated from your Shockpot honeypot database<br/><!--For more, visit the other pages/components of this package-->
</h2>

<div class="portfolio">
    <div class="fl_left">
        <h2>Top 10 commands</h2>

        <p>This vertical bar chart displays the top 10 commands that attackers try when attacking the
            system.</p>

        <p><a href="include/export.php?type=Commands">CSV of all distinct commands</a>

        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/top10_commands.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Top 10 query paths</h2>

        <p>This vertical bar chart displays the top 10 path used in query.</p>

        <p><a href="include/export.php?type=Paths">CSV of all distinct paths</a>

        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/top10_paths.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Shellshock ratio</h2>

        <p>This vertical bar chart displays the overall shellshock attacks ratio for the particular honeypot
            system.</p>

        <p><a href="include/export.php?type=Shellshock">CSV of all shellshock ratio</a>

        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/shellshock_ratio.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Shellshock attacks per day/week</h2>

        <p>This vertical bar chart displays the most shellshock attacks per day (Top 20) for the
            particular honeypot system. The numbers indicate how many times shellshock command were used
            by attackers.</p>

        <p><a href="include/export.php?type=ShellshockMost">CSV of all shellshock attacks</a>

        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/most_shellshock_attacks_per_day.png" alt=""/></div>
    <div class="clear"></div>
    <div class="fl_left">
        <p>This line chart displays the daily shellshock attacks on the honeypot system. Spikes indicate attacks
            over a weekly period.<br/><br/><strong>Warning:</strong> Dates with zero attacks are
            not displayed.</p>

        <p><a href="include/export.php?type=ShellshockDay">CSV of daily shellshock attacks</a>

        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/shellshocks_per_day.png" alt=""/></div>
    <div class="clear"></div>
    <div class="fl_left">
        <p>This line chart displays the weekly shellshock attacks on the honeypot system. Curves indicate attacks
             over a weekly period.</p>

        <p><a href="include/export.php?type=ShellshockWeek">CSV of weekly shellshock attacks</a>

        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/shellshocks_per_week.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Connections per IP</h2>

        <p>This vertical bar chart displays the top 10 unique IPs ordered by the number of overall
            connections to the system.</p>

        <p><a href="include/export.php?type=ConnIP">CSV of all connections per IP</a>

        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/connections_per_ip.png" alt=""/></div>
    <div class="fl_left">
        <p>This pie chart displays the top 10 unique IPs ordered by the number of overall connections to the
            system.</p>
    </div>
    <div class="fl_right"><img src="generated-graphs/connections_per_ip_pie.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Shellshock attacks from the same IP</h2>

        <p>This vertical bar chart displays the number of shellshock attacks from the same IP address (Top
            20). The numbers indicate how many times the particular source used shellshock attack.</p>

        <p><a href="include/export.php?type=ShellshockIP">CSV of all shellshock attacks IPs</a>

        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/shellshocks_attacks_from_same_ip.png" alt=""/></div>
    <div class="clear"></div>
</div>
<!-- ############################# -->
<div class="portfolio">
    <div class="fl_left">
        <h2>Probes per day/week</h2>

        <p>This horizontal bar chart displays the most probes per day (Top 20) against the honeypot
            system.</p>
    </div>
    <div class="fl_right"><img src="generated-graphs/most_probes_per_day.png" alt=""/></div>
    <div class="fl_left">
        <p>This line chart displays the daily activity on the honeypot system. Spikes indicate hacking
            attempts.<br/><br/><strong>Warning:</strong> Dates with zero probes are not displayed.</p>

        <p><a href="include/export.php?type=ProbesDay">CSV of all probes per day</a>

        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/probes_per_day.png" alt=""/></div>
    <div class="fl_left">
        <p>This line chart displays the weekly activity on the honeypot system. Curves indicate hacking
            attempts over a weekly period.</p>

        <p><a href="include/export.php?type=ProbesWeek">CSV of all probes per week</a>

        <p>
    </div>
    <div class="fl_right"><img src="generated-graphs/probes_per_week.png" alt=""/></div>
    <div class="clear"></div>
</div>
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
</body>
</html>
