<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
    <title>Shockpot-Frontend | Fast Visualization for your Shockpot Honeypot Stats</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <link rel="stylesheet" href="styles/layout.css" type="text/css"/>
    <script type="text/javascript" src="scripts/jquery-1.4.1.min.js"></script>
    <!-- FancyBox -->
    <script type="text/javascript" src="scripts/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="scripts/fancybox/jquery.fancybox-1.3.2.js"></script>
    <script type="text/javascript" src="scripts/fancybox/jquery.fancybox-1.3.2.setup.js"></script>
    <link rel="stylesheet" href="scripts/fancybox/jquery.fancybox-1.3.2.css" type="text/css"/>
    <!-- End FancyBox -->
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
            <li><a href="shockpot-ip.php">Shockpot-Ip</a></li>
            <li><a href="shockpot-geo.php">Shockpot-Geo</a></li>
            <li class="active last"><a href="gallery.php">Graph Gallery</a></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>
<!-- ####################################################################################################### -->
<div class="wrapper">
    <div class="container">
        <!-- ############################# -->
        <div id="gallery" class="whitebox">
            <h2>Provided you have visited all the other pages/components (for the graphs to be generated) you can see
                all the images in this single page with the help of fancybox</h2>
            <hr/>
            <br/>
            <ul>
                <li><a rel="gallery_group" href="generated-graphs/top10_commands.png"
                       title="Top 10 commands"><img src="generated-graphs/top10_commands.png" alt=""/></a>
                </li>
                <li><a rel="gallery_group" href="generated-graphs/top10_paths.png"
                       title="Top 10 paths"><img src="generated-graphs/top10_paths.png" alt=""/></a>
                </li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/shellshock_ratio.png"
                                    title="Overall shellshock ratio"><img
                            src="generated-graphs/shellshock_ratio.png" alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/most_shellshock_attacks_per_day.png"
                       title="Most shellshock attacks per day (Top 20)"><img
                            src="generated-graphs/most_shellshock_attacks_per_day.png" alt=""/></a></li>
                <li><a rel="gallery_group" href="generated-graphs/shellshocks_per_day.png" title="Shellshock attacks per day"><img
                            src="generated-graphs/shellshocks_per_day.png" alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/shellshocks_per_week.png"
                                    title="Shellshock attacks per week"><img
                            src="generated-graphs/shellshocks_per_week.png" alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/connections_per_ip.png" title="Number of connections per unique IP (Top 10)"><img
                            src="generated-graphs/connections_per_ip.png" alt=""/></a></li>
                <li><a rel="gallery_group" href="generated-graphs/connections_per_ip_pie.png"
                       title="Number of connections per unique IP (Top 10)"><img src="generated-graphs/connections_per_ip_pie.png" alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/shellshocks_attacks_from_same_ip.png"
                                    title="Shellshock attacks from same IP (Top 20)"><img
                            src="generated-graphs/shellshocks_attacks_from_same_ip.png" alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/most_probes_per_day.png"
                       title="Most probes per day (Top 20)"><img
                            src="generated-graphs/most_probes_per_day.png" alt=""/></a></li>
                <li><a rel="gallery_group" href="generated-graphs/probes_per_day.png"
                       title="Probes per day"><img
                            src="generated-graphs/probes_per_day.png" alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/probes_per_week.png"
                                    title="Probes per week"><img
                            src="generated-graphs/probes_per_week.png" alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/connections_per_country_pie.png"
                       title="Number of connections per country"><img
                            src="generated-graphs/connections_per_country_pie.png" alt=""/></a></li>
                <li><a rel="gallery_group" href="generated-graphs/connections_per_ip_geo.png"
                       title="Number of connections per unique IP (Top 10) + Country Codes"><img
                            src="generated-graphs/connections_per_ip_geo.png" alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/connections_per_ip_geo_pie.png"
                                    title="Number of connections per unique IP (Top 10) + Country Codes"><img
                            src="generated-graphs/connections_per_ip_geo_pie.png" alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/shellshock_activity_busiest_days.png"
                       title="Shellshock activity busiest days (Top 20)"><img
                            src="generated-graphs/shellshock_activity_busiest_days.png" alt=""/></a></li>
                <li><a rel="gallery_group" href="generated-graphs/shellshock_activity_per_day.png"
                       title="Shellshock activity per day"><img src="generated-graphs/shellshock_activity_per_day.png"
                                                           alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/shellshock_activity_per_week.png"
                                    title="Shellshock activity per week"><img
                            src="generated-graphs/shellshock_activity_per_week.png" alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/top10_overall_commands.png"
                       title="Top 10 commands (overall)"><img src="generated-graphs/top10_overall_commands.png" alt=""/></a>
                </li>
                <li><a rel="gallery_group" href="generated-graphs/top10_combinations.png"
                       title="Top 10 command / command_data combinations"><img src="generated-graphs/top10_combinations.png"
                                                            alt=""/></a></li>
                <li class="last"><a rel="gallery_group" href="generated-graphs/top10_headers.png"
                                    title="Top 10 headers"><img src="generated-graphs/top10_headers.png"
                                                                     alt=""/></a></li>

                <li><a rel="gallery_group" href="generated-graphs/top10_urls.png" title="Top 10 urls"><img
                            src="generated-graphs/top10_urls.png" alt=""/></a></li>




            </ul>
            <br class="clear"/>
        </div>
        <!-- ############################# -->
        <div class="clear"></div>
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
