<?php

mysql_connect($mysqlHost, $mysqlUser, $mysqlPass);
mysql_select_db($mysqlDb);
		
$getStationData = mysql_query("SELECT * FROM stationdata ORDER BY name ASC");

$dropdownOptions = "";

/* Creates HTML code for the drop-down form options */
while ($eachStation = mysql_fetch_array($getStationData)) {

$stationNumber = $eachStation['number'];
$stationName = $eachStation['name'];
$dropdownOptions .= "<option value=" . $stationNumber . ">" . $stationName . "</option>";

}

mysql_close();

?>