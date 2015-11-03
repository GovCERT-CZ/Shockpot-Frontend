<?php
require_once(DIR_ROOT . '/include/rb.php');
require_once(DIR_ROOT . '/include/maxmind/geoip2.phar');
require_once(DIR_ROOT . '/include/tor/tor.class.php');

class ShockpotIP
{
    private $maxmind;
    private $tor;

    function __construct()
    {
        $this->maxmind = new \GeoIp2\Database\Reader(DIR_ROOT . '/include/maxmind/GeoLite2-City.mmdb');
        $this->tor = new Tor();

        //Let's connect to the database
        R::setup('pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    }

    function __destruct()
    {
        R::close();
    }

    public function printOverallIpActivity()
    {
        $db_query = "SELECT A.*, B.is_shellshock FROM (
          SELECT source_ip, MAX(date_trunc('second', timestamp)) as lastseen
          FROM connections GROUP BY source_ip) A
          LEFT JOIN (
            SELECT source_ip, is_shellshock, timestamp
            FROM connections) B on A.source_ip = B.source_ip AND A.lastseen = date_trunc('second', B.timestamp)
          ORDER BY A.source_ip";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            echo '<p>Click column heads to sort data, rows to display attack details.</p>';

            echo '<table id="Total-IPs"><thead><tr class="dark"><th>
                  Total identified IP addresses: ' . count($rows) . '</th></tr></thead></table>';

            //We create a skeleton for the table
            echo '<table id="Overall-IP-Activity" class="tablesorter"><thead>';
            echo '<tr class="dark">';
            echo '<th>IP address</th>';
            if (GEO_METHOD == 'LOCAL')
                echo '<th>Geolocation</th>';
            if (TOR_CHECK == 'YES')
                echo '<th>Tor exit node</th>';
            echo '<th>Is shellshock</th>';
            echo '<th>Last seen</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach ($rows as $row) {
                $success = is_null($row['is_shellshock']) ? 'N/A' : $row['is_shellshock'];
                $timestamp = is_null($row['lastseen']) ? 'N/A' : $row['lastseen'];

                echo '<tr class="light word-break" onclick=\'getIPinfo("' . $row['source_ip'] . '")\'>';
                echo '<td>' . $row['source_ip'] . '</td>';

                if (GEO_METHOD == 'LOCAL') {
                    try {
                        $geodata = $this->maxmind->city($row['source_ip']);
                        $geolocation = $geodata->city->name ? $geodata->city->name . ', ' . $geodata->country->name : $geodata->country->name;

                    } catch (\GeoIp2\Exception\GeoIp2Exception $e) {
                        $geolocation = 'N/A';
                    }
                    echo '<td>' . $geolocation . '</td>';
                }

                if (TOR_CHECK == 'YES') {
                    $exitnode = $this->tor->isTorExitNode($row['source_ip']) ? 'Yes' : 'No';
                    echo '<td>' . $exitnode . '</td>';
                }

                echo '<td>' . $success . '</td>';
                echo '<td>' . $timestamp . '</td>';
                echo '</tr>';
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
        }

        echo '<div id="pager1" class="pager">';
        echo '  <form>';
        echo '     <img src="images/first.png" class="first"/>';
        echo '     <img src="images/prev.png" class="prev"/>';
        echo '     <span class="pagedisplay"></span>';
        echo '     <img src="images/next.png" class="next"/>';
        echo '     <img src="images/last.png" class="last"/>';
        echo '     <select class="pagesize">';
        echo '        <option selected="selected" value="10">10</option>';
        echo '        <option value="20">20</option>';
        echo '        <option value="30">30</option>';
        echo '        <option value="40">40</option>';
        echo '     </select>';
        echo '  </form>';
        echo '  <a id="allActivityLink" href="include/export.php?type=allActivity">CSV of all recent IP activity</a>';
        echo '</div>';

        echo '<br /><hr />';
        if (GEO_METHOD == 'LOCAL') {
            echo '<small><a href="http://www.maxmind.com">http://www.maxmind.com</a></small><br />';
        }
        else {
            //TODO
        }
    }
}

?>
