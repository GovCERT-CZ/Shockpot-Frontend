<?php
require_once(DIR_ROOT . '/include/rb.php');
require_once(DIR_ROOT . '/include/libchart/classes/libchart.php');

class ShockpotFrontend
{

    function __construct()
    {
        //Let's connect to the database
        R::setup('pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    }

    function __destruct()
    {
        R::close();
    }

    public function printOverallHoneypotActivity()
    {
        //TOTAL LOGIN ATTEMPTS
        $db_query = "SELECT COUNT(*) AS logins FROM connections";
        $row = R::getRow($db_query);

        //echo '<strong>Total login attempts: </strong><h3>'.$row['logins'].'</h3>';
        echo '<table><thead>';
        echo '<tr>';
        echo '<th>Total attack attempts</th>';
        echo '<th>' . $row['logins'] . '</th>';
        echo '</tr></thead><tbody>';
        echo '</tbody></table>';

        //TOTAL DISTINCT IPs
        $db_query = "SELECT COUNT(DISTINCT source_ip) AS ips FROM connections";
        $row = R::getRow($db_query);

        //echo '<strong>Distinct source IPs: </strong><h3>'.$row['IPs'].'</h3>';
        echo '<table><thead>';
        echo '<tr>';
        echo '<th>Distinct source IP addresses</th>';
        echo '<th>' . $row['ips'] . '</th>';
        echo '</tr></thead><tbody>';
        echo '</tbody></table>';

        //OPERATIONAL TIME PERIOD
        $db_query = "SELECT MIN(timestamp) AS start, MAX(timestamp) AS end FROM connections";
        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a skeleton for the table
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th colspan="2">Active time period</th>';
            echo '</tr>';
            echo '<tr class="dark">';
            echo '<th>Start date (first attack)</th>';
            echo '<th>End date (last attack)</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach ($rows as $row) {
                echo '<tr class="light">';
                echo '<td>' . date('l, d-M-Y, H:i A', strtotime($row['start'])) . '</td>';
                echo '<td>' . date('l, d-M-Y, H:i A', strtotime($row['end'])) . '</td>';
                echo '</tr>';
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
        }
    }

    public function createTop10commands()
    {
        $db_query = "SELECT command, COUNT(command) AS count
          FROM connections
          WHERE command <> ''
          GROUP BY command
          ORDER BY COUNT(command) DESC
          LIMIT 10 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['command'], $row['count']));
            }

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_COMMANDS);
            $chart->render("generated-graphs/top10_commands.png");
        }
    }

    public function createTop10paths()
    {
        $db_query = "SELECT path, COUNT(path) AS count
          FROM connections
          GROUP BY path
          ORDER BY COUNT(path) DESC
          LIMIT 10 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['path'], $row['count']));
            }

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_PATHS);
            $chart->render("generated-graphs/top10_paths.png");
        }
    }

    public function createShellshockRation()
    {
        $db_query = "SELECT is_shellshock, COUNT(is_shellshock) AS count
          FROM connections
          GROUP BY is_shellshock
          ORDER BY is_shellshock";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //Database should return two rows, so we need two bars
            //If success = 0 or = 1 add point accordingly, else a new bar (in case of NULL/whatever)
            foreach ($rows as $row) {
                if ($row['is_shellshock'] == "false")
                    $dataSet->addPoint(new Point("Not shellshock", $row['count']));
                else if ($row['is_shellshock'] == "true")
                    $dataSet->addPoint(new Point("Shellshock", $row['count']));
                else
                    $dataSet->addPoint(new Point($row['is_shellshock'], $row['count']));
            }

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(OVERALL_SHELLSHOCK_RATIO);
            $chart->render("generated-graphs/shellshock_ratio.png");
        }
    }

    public function createMostShellshockAttacksPerDay()
    {
        $db_query = "SELECT COUNT(connection) AS count, date_trunc('day', timestamp) AS date
          FROM connections
          WHERE is_shellshock = 'true'
          GROUP BY date
          ORDER BY COUNT(connection) DESC
          LIMIT 20 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new horizontal bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['date'])), $row['count']));
            }

            //We set the horizontal chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(MOST_SHELLSHOCK_PER_DAY);
            $chart->getPlot()->setGraphPadding(new Padding(5, 30, 50, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render("generated-graphs/most_shellshock_attacks_per_day.png");
        }
    }

    public function createShellshocksPerDay()
    {
        $db_query = "SELECT COUNT(connection) AS count, date_trunc('day', timestamp) AS date
          FROM connections
          WHERE is_shellshock = 'true'
          GROUP BY date
          ORDER BY date ASC ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new horizontal bar chart and initialize the dataset
            $chart = new LineChart(600, 300);
            $dataSet = new XYDataSet();

            //This graph gets messed up for large DBs, so here is a simple way to limit some of the input
            $counter = 1;
            //Display date legend only every $mod rows, 25 distinct values being the optimal for a graph
            $mod = round(count($rows) / 25);
            if ($mod == 0) $mod = 1; //otherwise a division by zero might happen below
            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                if ($counter % $mod == 0) {
                    $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['date'])), $row['count']));
                } else {
                    $dataSet->addPoint(new Point('', $row['count']));
                }
                $counter++;
            }

            //We set the horizontal chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(SHELLSHOCK_PER_DAY);
            $chart->getPlot()->setGraphPadding(new Padding(5, 30, 50, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render("generated-graphs/shellshocks_per_day.png");
        }
    }

    public function createShellshocksPerWeek()
    {
        $db_query = "SELECT COUNT(connection) AS count, EXTRACT(week from timestamp) AS week, EXTRACT(year from timestamp) AS year,
          to_date('' || EXTRACT(week from timestamp) || ' ' || EXTRACT(year from timestamp), 'IW IYYY') AS date
          FROM connections
          WHERE is_shellshock = 'true'
          GROUP BY week, year
          ORDER BY week ASC";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new line chart and initialize the dataset
            $chart = new LineChart(600, 300);
            $dataSet = new XYDataSet();

            //This graph gets messed up for large DBs, so here is a simple way to limit some of the input
            $counter = 1;
            //Display date legend only every $mod rows, 25 distinct values being the optimal for a graph
            $mod = round(count($rows) / 25);
            if ($mod == 0) $mod = 1; //otherwise a division by zero might happen below
            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                if ($counter % $mod == 0) {
                    $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['date'])), $row['count']));
                } else {
                    $dataSet->addPoint(new Point('', $row['count']));
                }
                $counter++;

                //We add 6 "empty" points to make a horizontal line representing a week
                for ($i = 0; $i < 6; $i++) {
                    $dataSet->addPoint(new Point('', $row['count']));
                }
            }

            //We set the line chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(SHELLSHOCK_PER_WEEK);
            $chart->render("generated-graphs/shellshocks_per_week.png");
        }
    }

    public function createNumberOfConnectionsPerIP()
    {
        $db_query = "SELECT source_ip, COUNT(source_ip) AS count
          FROM connections
          GROUP BY source_ip
          ORDER BY COUNT(source_ip) DESC
          LIMIT 10 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart,a new pie chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $pie_chart = new PieChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['source_ip'], $row['count']));
            }

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(NUMBER_OF_CONNECTIONS_PER_UNIQUE_IP);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 40, 75, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render("generated-graphs/connections_per_ip.png");

            //We set the pie chart's dataset and render the graph
            $pie_chart->setDataSet($dataSet);
            $pie_chart->setTitle(NUMBER_OF_CONNECTIONS_PER_UNIQUE_IP);
            $pie_chart->render("generated-graphs/connections_per_ip_pie.png");
        }
    }

    public function createShellshockAttacksFromSameIP()
    {
        $db_query = "SELECT source_ip, COUNT(source_ip) AS count
          FROM connections
          WHERE is_shellshock = 'true'
          GROUP BY source_ip
          ORDER BY COUNT(source_ip) DESC
          LIMIT 20 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['source_ip'], $row['count']));
            }

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(SHELLSHOCK_ATTACKS_FROM_SAME_IP);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 45, 80, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render("generated-graphs/shellshocks_attacks_from_same_ip.png");
        }
    }

    public function createMostProbesPerDay()
    {
        $db_query = "SELECT COUNT(connection), date_trunc('day', timestamp) AS date
          FROM connections
          GROUP BY date
          ORDER BY COUNT(connection) DESC
          LIMIT 20 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new horizontal bar chart and initialize the dataset
            $chart = new HorizontalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['date'])), $row['count']));
                //$dataSet->addPoint(new Point(date('l, d-m-Y', strtotime($row['timestamp'])), $row['COUNT(session)']));
            }

            //We set the horizontal chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(MOST_PROBES_PER_DAY);
            $chart->getPlot()->setGraphPadding(new Padding(5, 30, 50, 75 /*140*/)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render("generated-graphs/most_probes_per_day.png");
        }
    }

    public function createProbesPerDay()
    {
        $db_query = "SELECT COUNT(connection), date_trunc('day', timestamp) AS date
          FROM connections
          GROUP BY date
          ORDER BY date ASC";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new line chart and initialize the dataset
            $chart = new LineChart(600, 300);
            $dataSet = new XYDataSet();

            //This graph gets messed up for large DBs, so here is a simple way to limit some of the input
            $counter = 1;
            //Display date legend only every $mod rows, 25 distinct values being the optimal for a graph
            $mod = round(count($rows) / 25);
            if ($mod == 0) $mod = 1; //otherwise a division by zero might happen below
            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                if ($counter % $mod == 0) {
                    $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['date'])), $row['count']));
                } else {
                    $dataSet->addPoint(new Point('', $row['count']));
                }
                $counter++;
            }

            //We set the line chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(PROBES_PER_DAY);
            $chart->render("generated-graphs/probes_per_day.png");
        }
    }

    public function createProbesPerWeek()
    {
        $db_query = "SELECT COUNT(connection) AS count, EXTRACT(week from timestamp) AS week, EXTRACT(year from timestamp) AS year,
          to_date('' || EXTRACT(week from timestamp) || ' ' || EXTRACT(year from timestamp), 'IW IYYY') AS date
          FROM connections
          GROUP BY week, year
          ORDER BY week ASC";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new line chart and initialize the dataset
            $chart = new LineChart(600, 300);
            $dataSet = new XYDataSet();

            //This graph gets messed up for large DBs, so here is a simple way to limit some of the input
            $counter = 1;
            //Display date legend only every $mod rows, 25 distinct values being the optimal for a graph
            $mod = round(count($rows) / 25);
            if ($mod == 0) $mod = 1; //otherwise a division by zero might happen below
            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                if ($counter % $mod == 0) {
                    $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['date'])), $row['count']));
                } else {
                    $dataSet->addPoint(new Point('', $row['count']));
                }
                $counter++;

                //We add 6 "empty" points to make a horizontal line representing a week
                for ($i = 0; $i < 6; $i++) {
                    $dataSet->addPoint(new Point('', $row['count']));
                }
            }

            //We set the line chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(PROBES_PER_WEEK);
            $chart->render("generated-graphs/probes_per_week.png");
        }
    }

}

?>
