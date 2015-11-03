<?php
require_once(DIR_ROOT . '/include/rb.php');
require_once(DIR_ROOT . '/include/libchart/classes/libchart.php');
require_once(DIR_ROOT . '/include/misc/xss_clean.php');

class ShockpotInput
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
        echo '<h3>Overall commands usage</h3>';

        //TOTAL NUMBER OF COMMANDS
        $db_query = "SELECT COUNT(*) as total, COUNT(DISTINCT command) as uniq FROM connections WHERE command <> ''";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a skeleton for the table
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th colspan="2">Commands used from shellshock</th>';
            echo '</tr>';
            echo '<tr class="dark">';
            echo '<th>Total number of commands</th>';
            echo '<th>Distinct number of commands</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach ($rows as $row) {
                echo '<tr class="light word-break">';
                echo '<td>' . $row['total'] . '</td>';
                echo '<td>' . $row['uniq'] . '</td>';
                echo '</tr>';
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
        }

        //TOTAL DOWNLOADED FILES
        $db_query = "SELECT COUNT(command) as commands, COUNT(DISTINCT command) as uniq_commands
          FROM connections WHERE (command LIKE '%wget%' AND command NOT LIKE 'wget') OR (command LIKE '%curl%' AND command NOT LIKE 'curl')";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a skeleton for the table
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th colspan="2">Data of used commands</th>';
            echo '</tr>';
            echo '<tr class="dark">';
            echo '<th>Total number of command data</th>';
            echo '<th>Distinct number of command data</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach ($rows as $row) {
                echo '<tr class="light word-break">';
                echo '<td>' . $row['commands'] . '</td>';
                echo '<td>' . $row['uniq_commands'] . '</td>';
                echo '</tr>';
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';
        }

        echo '<hr /><br />';
    }

    public function printActivityBusiestDays()
    {
        $db_query = "SELECT COUNT(command) AS count, date_trunc('day', timestamp) AS date
          FROM connections
          WHERE command <> ''
          GROUP BY date
          ORDER BY COUNT(command) DESC
          LIMIT 20 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart_vertical = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point(date('d-m-Y', strtotime($row['date'])), $row['count']));
            }

            //We set the vertical bar chart's dataset and render the graph
            $chart_vertical->setDataSet($dataSet);
            $chart_vertical->setTitle(ACTIVITY_BUSIEST_DAYS);
            $chart_vertical->render("generated-graphs/shellshock_activity_busiest_days.png");
            echo '<h3>Shellshock commands activity inside the honeypot</h3>';
            echo '<p>The following vertical bar chart visualizes the top 20 busiest days of shellshock commands activity, by counting the number of input commands to the system.</p>';
            echo '<img src="generated-graphs/shellshock_activity_busiest_days.png">';
            echo '<br />';
        }
    }

    public function printActivityPerDay()
    {
        $db_query = "SELECT COUNT(command) AS count, date_trunc('day', timestamp) AS date
          FROM connections
          WHERE command <> ''
          GROUP BY date
          ORDER BY date ASC ";

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
            $chart->setTitle(ACTIVITY_PER_DAY);
            $chart->render("generated-graphs/shellshock_activity_per_day.png");
            echo '<p>The following line chart visualizes shellshock commands activity per day, by counting the number of input commands to the system for each day of operation.
			<br/><strong>Warning:</strong> Dates with zero input are not displayed.</p>';
            echo '<img src="generated-graphs/shellshock_activity_per_day.png">';
            echo '<br />';
        }
    }

    public function printActivityPerWeek()
    {
        $db_query = "SELECT COUNT(command) AS count, EXTRACT(week from timestamp) AS week, EXTRACT(year from timestamp) AS year,
          to_date('' || EXTRACT(week from timestamp) || ' ' || EXTRACT(year from timestamp), 'IW IYYY') AS date
          FROM connections
          WHERE command <> ''
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
            $chart->setTitle(ACTIVITY_PER_WEEK);
            $chart->render("generated-graphs/shellshock_activity_per_week.png");
            echo '<p>The following line chart visualizes shellshock commands activity per week, by counting the number of input commands to the system for each day of operation.</p>';
            echo '<img src="generated-graphs/shellshock_activity_per_week.png">';
            echo '<br /><hr /><br />';
        }
    }

    public function printTop10OverallCommands()
    {
        $db_query = "SELECT command, COUNT(command) AS count
          FROM connections
          WHERE command <> ''
          GROUP BY command
          ORDER BY COUNT(command) DESC
          LIMIT 10";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>Top 10 commands (overall)</h3>';
            echo '<p>The following table displays the top 10 commands (overall) entered by attackers in the honeypot system.</p>';
            echo '<p><a href="include/export.php?type=Commands">CSV of all input commands</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Command</th>';
            echo '<th>Count</th>';
            echo '</tr></thead><tbody>';


            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['command'], $row['count']));

                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . xss_clean($row['command']) . '</td>';
                echo '<td>' . $row['count'] . '</td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';


            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_COMMANDS_OVERALL);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 30, 90, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render("generated-graphs/top10_overall_commands.png");
            echo '<p>This vertical bar chart visualizes the top 10 commands (overall) entered by attackers in the honeypot system.</p>';
            echo '<img src="generated-graphs/top10_overall_commands.png">';
            echo '<hr /><br />';
        }
    }

    public function printTop10Combinations()
    {
        $db_query = "SELECT command, command_data, COUNT(command) AS count
          FROM connections
          WHERE command <> '' AND command_data <> ''
          GROUP BY command, command_data
          ORDER BY COUNT(command) DESC
          LIMIT 10 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart,a new pie chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>Top 10 combinations command / command_data</h3>';
            echo '<p>The following table displays the top 10 combinations of command and data entered by attackers in the honeypot system.</p>';
            echo '<p><a href="include/export.php?type=Combos">CSV of all combinations</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Command</th>';
            echo '<th>Data</th>';
            echo '<th>Count</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['command'] . '/' . $row['command_data'], $row['count']));

                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . xss_clean($row['command']) . '</td>';
                echo '<td>' . xss_clean($row['command_data']) . '</td>';
                echo '<td>' . $row['count'] . '</td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_COMBINATIONS);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 40, 75, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render("generated-graphs/top10_combinations.png");
            echo '<p>This vertical bar chart visualizes the top 10 combinations of command and data entered by attackers in the honeypot system.</p>';
            echo '<img src="generated-graphs/top10_combinations.png">';
            echo '<hr /><br />';

        }
    }

    public function printTop10Commands()
    {
        $db_query = "SELECT command, COUNT(command) AS count
          FROM connections
          WHERE command <> ''
          GROUP BY command
          ORDER BY COUNT(command) DESC
          LIMIT 10 ";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart,a new pie chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>Top 10 commands</h3>';
            echo '<p>The following table displays the top 10 commands entered by attackers in the honeypot system.</p>';
            echo '<p><a href="include/export.php?type=Commands">CSV of all commands</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Command</th>';
            echo '<th>Count</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset
            foreach ($rows as $row) {
                $dataSet->addPoint(new Point($row['command'], $row['count']));

                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . xss_clean($row['command']) . '</td>';
                echo '<td>' . $row['count'] . '</td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_COMMANDS);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 40, 75, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render("generated-graphs/top10_input_commands.png");
            echo '<p>This vertical bar chart visualizes the top 10 commands entered by attackers in the honeypot system.</p>';
            echo '<img src="generated-graphs/top10_input_commands.png">';
            echo '<hr /><br />';

        }
    }

    public function printTop10Headers()
    {
        $db_query = "SELECT headers, COUNT(headers) AS count
          FROM connections
          WHERE headers <> ''
          GROUP BY headers
          ORDER BY COUNT(headers) DESC
          LIMIT 10";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>Top 10 Headers</h3>';
            echo '<p>The following table displays the top 10 haders sended by attackers as part of request.</p>';
            echo '<p><a href="include/export.php?type=Headers">CSV of all headers</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Headers</th>';
            echo '<th>Count</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach($rows as $row) {
                $dataSet->addPoint(new Point($row['headers'], $row['count']));

                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . xss_clean($row['headers']) . '</td>';
                echo '<td>' . $row['count'] . '</td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_HEADERS);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 30, 90, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render("generated-graphs/top10_headers.png");
            echo '<p>This vertical bar chart visualizes the top 10 haders sended by attackers as part of request.</p>';
            echo '<img src="generated-graphs/top10_headers.png">';
            echo '<hr /><br />';
        }
    }

    public function printTop10Urls()
    {
        $db_query = "SELECT url, COUNT(url) AS count
          FROM connections
          WHERE url <> ''
          GROUP BY url
          ORDER BY COUNT(url) DESC
          LIMIT 10";

        $rows = R::getAll($db_query);

        if (count($rows)) {
            //We create a new vertical bar chart and initialize the dataset
            $chart = new VerticalBarChart(600, 300);
            $dataSet = new XYDataSet();

            //We create a skeleton for the table
            $counter = 1;
            echo '<h3>Top 10 urls</h3>';
            echo '<p>The following table displays the top 10 urls used by attackers.</p>';
            echo '<p><a href="include/export.php?type=Urls">CSV of all urls</a><p>';
            echo '<table><thead>';
            echo '<tr class="dark">';
            echo '<th>ID</th>';
            echo '<th>Url</th>';
            echo '<th>Count</th>';
            echo '</tr></thead><tbody>';

            //For every row returned from the database we add a new point to the dataset,
            //and create a new table row with the data as columns
            foreach($rows as $row) {
                $dataSet->addPoint(new Point($row['url'], $row['count']));

                echo '<tr class="light word-break">';
                echo '<td>' . $counter . '</td>';
                echo '<td>' . xss_clean($row['url']) . '</td>';
                echo '<td>' . $row['count'] . '</td>';
                echo '</tr>';
                $counter++;
            }

            //Close tbody and table element, it's ready.
            echo '</tbody></table>';

            //We set the bar chart's dataset and render the graph
            $chart->setDataSet($dataSet);
            $chart->setTitle(TOP_10_URLS);
            //For this particular graph we need to set the corrent padding
            $chart->getPlot()->setGraphPadding(new Padding(5, 40, 120, 50)); //top, right, bottom, left | defaults: 5, 30, 50, 50
            $chart->render("generated-graphs/top10_urls.png");
            echo '<p>This vertical bar chart visualizes the top 10 urls used by attackers.</p>';
            echo '<img src="generated-graphs/top10_urls.png">';
            echo '<hr /><br />';
        }
    }
}

?>
