<?php

mysql_connect($mysqlHost, $mysqlUser, $mysqlPass);
mysql_select_db($mysqlDb);

$startStation = mysql_real_escape_string($startStation);
$endStation = mysql_real_escape_string($endStation);

$findStartStation = mysql_query
("SELECT * FROM stationdata WHERE number='$startStation'");
$findEndStation = mysql_query
("SELECT * FROM stationdata WHERE number='$endStation'");

$startStationData = mysql_fetch_row($findStartStation);
$endStationData = mysql_fetch_row($findEndStation);

$result1 = mysql_query("SELECT * FROM q12013 WHERE start='$startStation' AND end='$endStation'");
$result2 = mysql_query("SELECT * FROM q22013 WHERE start='$startStation' AND end='$endStation'");
$result3 = mysql_query("SELECT * FROM q32013 WHERE start='$startStation' AND end='$endStation'");
$result4 = mysql_query("SELECT * FROM q42013 WHERE start='$startStation' AND end='$endStation'");

mysql_close();

$allTrips = Array();

while ($data = mysql_fetch_row($result1)) {
    $allTrips[] = $data[3];
}

while ($data = mysql_fetch_row($result2)) {
    $allTrips[] = $data[3];
}

while ($data = mysql_fetch_row($result3)) {
    $allTrips[] = $data[3];
}

while ($data = mysql_fetch_row($result4)) {
    $allTrips[] = $data[3];
}

?>