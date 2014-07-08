<?php

/* Google Distance Matrix API provides biking, driving, and walking estimates */
$distanceMatrixURL = "http://maps.googleapis.com/maps/api/distancematrix/json?"
. "origins=$startStationData[2],$startStationData[3]"
. "&destinations=$endStationData[2],$endStationData[3]"
. "&sensor=false&mode=";

$jsonBikeURL = $distanceMatrixURL . "bicycling";
$jsonDriveURL = $distanceMatrixURL . "driving";
$jsonWalkURL = $distanceMatrixURL . "walking";

$jsonBikeCode = file_get_contents($jsonBikeURL, true);
$jsonBikeArray = json_decode($jsonBikeCode, true);
$bikeTime = $jsonBikeArray['rows'][0]['elements'][0]['duration']['value'];

$jsonDriveCode = file_get_contents($jsonDriveURL, true);
$jsonDriveArray = json_decode($jsonDriveCode, true);
$driveTime = $jsonDriveArray['rows'][0]['elements'][0]['duration']['value'];

$jsonWalkCode = file_get_contents($jsonWalkURL, true);
$jsonWalkArray = json_decode($jsonWalkCode, true);
$walkTime = $jsonWalkArray['rows'][0]['elements'][0]['duration']['value'];

$timeNow = time();

/* Google Directions API provides public transit estimate */
$jsonTransitURL = "http://maps.googleapis.com/maps/api/directions/json?"
. "mode=transit&origin=$startStationData[2],$startStationData[3]"
. "&destination=$endStationData[2],$endStationData[3]"
. "&sensor=false&departure_time=$timeNow";

$jsonTransitCode = file_get_contents($jsonTransitURL, true);
$jsonTransitArray = json_decode($jsonTransitCode, true);
$transitTime = $jsonTransitArray['routes'][0]['legs'][0]['duration']['value'];

$googleModes = array(
array('bicycle','biking',$bikeTime),
array('car','driving',$driveTime),
array('foot','walking',$walkTime),
array('public transit, if departing now','transit',$transitTime)
    );
	
    $modeCompare = array();
	
/* Displays Google API estimates, or error message if API request limit has been reached */
foreach ($googleModes as $mode) {

  if (is_numeric($mode[2])) {
    $modeCompare[] = "<li><span class='google-stats'>" .
    convert_seconds($mode[2]) .
    "</span> by " . $mode[0] . "</li>";
  } else {
    $modeCompare[] =	"<li><em>" . ucfirst($mode[1]) .
	" estimate unavailable (Google API not responding)".
	"</em></li>";
  }

}

$staticMap = "http://maps.googleapis.com/maps/api/staticmap?size=640x300&maptype=terrain&sensor=false"
. "&markers=icon:" . $fixedURL . $greenIcon . "|"
. $startStationData[2] . "," . $startStationData[3]
. "&markers=icon:" . $fixedURL . $redIcon . "|"
. $endStationData[2] . "," . $endStationData[3];

?>