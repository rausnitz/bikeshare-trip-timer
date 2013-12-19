<?php

mysql_connect($mysqlHost, $mysqlUser, $mysqlPass);
mysql_select_db($mysqlDb);

$startStationEsc = mysql_real_escape_string($startStation);
$endStationEsc = mysql_real_escape_string($endStation);

$findStartStation = mysql_query
("SELECT * FROM stationdata WHERE number='$startStationEsc'");
$findEndStation = mysql_query
("SELECT * FROM stationdata WHERE number='$endStationEsc'");

$startStationData = mysql_fetch_row($findStartStation);
$endStationData = mysql_fetch_row($findEndStation);

$stationPairEsc = mysql_real_escape_string($stationPair);
$findPairData = mysql_query
("SELECT * FROM tripdata WHERE pair='$stationPairEsc'");
$pairData = mysql_fetch_row($findPairData);

mysql_close();

?>