<?php
#CSV Export for Kippo Graph
#Author: Kevin Breen
#Website: techanarchy.net

//TOTAL LOGIN ATTEMPTS
$db_AllCount = "SELECT COUNT(*) AS logins FROM connections";

//TOTAL DISTINCT IPs
$db_CountIP = "SELECT COUNT(DISTINCT source_ip) AS ips FROM connections";

//OPERATIONAL TIME PERIOD
$db_OpTime = "SELECT MIN(timestamp) AS start, MAX(timestamp) AS end FROM connections";

// ALL COMMANDS
$db_Commands = "SELECT command, COUNT(command) AS count
  FROM connections
  WHERE command <> ''
  GROUP BY command
  ORDER BY COUNT(command) DESC";

// ALL PATHS AND COUNT
$db_Paths = "SELECT path, COUNT(path) AS count
  FROM connections
  GROUP BY path
  ORDER BY COUNT(path) DESC";

//SHELLSHOCK RATIO
$db_Shellshock = "SELECT is_shellshock, COUNT(is_shellshock) AS count
  FROM connections
  GROUP BY is_shellshock
  ORDER BY is_shellshock";

//MOST SHELLSHOCK PER DAY
$db_ShellshockMost = "SELECT COUNT(connection) AS count, to_char(timestamp, 'DD-MM-YYYY') AS date
  FROM connections
  WHERE is_shellshock = 'true'
  GROUP BY date
  ORDER BY COUNT(connection) DESC";
//ORDER BY timestamp ASC";

//SHELLSHOCK PER DAY
$db_ShellshockDay = "SELECT COUNT(connection) AS count, to_char(timestamp, 'DD-MM-YYYY') AS date
  FROM connections
  WHERE is_shellshock = 'true'
  GROUP BY date
  ORDER BY date ASC";

//SHELLSHOCK PER WEEK
$db_ShellshockWeek = "SELECT COUNT(connection) AS count, EXTRACT(week from timestamp) AS week, EXTRACT(year from timestamp) AS year,
  to_date('' || EXTRACT(week from timestamp) || ' ' || EXTRACT(year from timestamp), 'IW IYYY') AS date
  FROM connections
  WHERE is_shellshock = 'true'
  GROUP BY week, year
  ORDER BY week ASC";

//ALL CONNECTIONS FROM SAME IP
$db_ConnIP = "SELECT source_ip, COUNT(source_ip) AS count
  FROM connections
  GROUP BY source_ip
  ORDER BY COUNT(source_ip) DESC ";

//ALL SHELLSHOCK ATTACKS FROM SAME IP
$db_ShellshockIP = "SELECT source_ip, COUNT(source_ip) AS count
  FROM connections
  WHERE is_shellshock = 'true'
  GROUP BY source_ip
  ORDER BY COUNT(source_ip) DESC";

// PROBES PER DAY
$db_ProbesDay = "SELECT COUNT(connection) AS count, to_char(timestamp, 'DD-MM-YYYY') AS date
  FROM connections
  GROUP BY date
  ORDER BY date ASC";

// PROBES PER WEEK
$db_ProbesWeek = "SELECT COUNT(connection) AS count, EXTRACT(week from timestamp) AS week, EXTRACT(year from timestamp) AS year,
  to_date('' || EXTRACT(week from timestamp) || ' ' || EXTRACT(year from timestamp), 'IW IYYY') AS date
  FROM connections
  GROUP BY week, year
  ORDER BY week ASC";

############################ INPUT ##########################

// ACTIVITY PER DAY
$db_ActivityDay = "SELECT COUNT(command) AS count, to_char(timestamp, 'DD-MM-YYYY') AS date
  FROM connections
  WHERE command <> ''
  GROUP BY date
  ORDER BY date ASC";

// ACTIVITY PER WEEK
$db_ActivityWeek = "SELECT COUNT(command) AS count, EXTRACT(week from timestamp) AS week, EXTRACT(year from timestamp) AS year,
  to_date('' || EXTRACT(week from timestamp) || ' ' || EXTRACT(year from timestamp), 'IW IYYY') AS date
  FROM connections
  WHERE command <> ''
  GROUP BY week, year
  ORDER BY week ASC";

// Commands
$db_Commands = "SELECT command, COUNT(command) AS count
  FROM connections
  WHERE command <> ''
  GROUP BY command
  ORDER BY COUNT(command) DESC";

// TOP 10 COMBINATIONS
$db_Combos = "SELECT command, command_data, COUNT(command) AS count
  FROM connections
  WHERE command <> '' AND command_data <> ''
  GROUP BY command, command_data
  ORDER BY COUNT(command) DESC";

// TOP 10 HEADERS
$db_Headers = "SELECT headers, COUNT(headers) AS count
  FROM connections
  WHERE headers <> ''
  GROUP BY headers
  ORDER BY COUNT(headers) DESC";

// TOP 10 URLS
$db_Urls = "SELECT url, COUNT(url) AS count
  FROM connections
  WHERE url <> ''
  GROUP BY url
  ORDER BY COUNT(url) DESC";

// ALL IP ACTIVITY
$db_allActivity = "SELECT timestamp, source_ip, is_shellshock, url, headers, command, command_data
  FROM connections
  ORDER BY timestamp DESC
  LIMIT 65535";

?>
