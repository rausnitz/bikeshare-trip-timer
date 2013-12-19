<?php

$startStation = $_GET['stationstart'];
$endStation = $_GET['stationend'];

/* Each unique station pair appears in the database as e.g. "31613to31619" */
$stationPair = $startStation . "to" . $endStation;

include('config.php');
include('settings.php');

include('functions/resultsdata.php');
include('functions/favicon.php');

include('html/header.php');
include('html/title.php');	
include('html/stations.php');

if ($startStation == $endStation) {
    include('functions/formdata.php');
    include('html/same.php');
	include('html/form.php');
} else {
    include('functions/numeric.php');
    include('functions/googleapi.php');

    if (empty($pairData)) {
	    include('html/explainzero.php');
        include('html/details.php');

    } else {
        include('html/keystats.php');
        include('html/details.php');
        include('html/asterisk.php');
	}

    include('html/footer.php');
}

?> 