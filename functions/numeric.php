<?php

/* Converts e.g. "601" to "10 minutes and 1 second" */
function convert_seconds($seconds) {
    $getHours = floor($seconds / 3600);
    $getMinutes = ($seconds / 60) % 60;
    $getSeconds = $seconds % 60;
	
    $timeElements = array(
	'hour' => $getHours,
	'minute' => $getMinutes,
	'second' => $getSeconds
    );
	
    $timePieces = array();

	/* Handles singular and plural of hours, minutes, and seconds */
    foreach ($timeElements as $timeType => $timeNumber) {
      if ($timeNumber == 1) {
	      $timePieces[] = "1 " . $timeType;
      } else {
          $timePieces[] = $timeNumber . " " . $timeType . "s";
      }
    }

    if ($seconds < 60) {
        $hoursMinSecs = $timePieces[2];
    } elseif ($seconds >= 60 && $seconds < 3600) {
        $hoursMinSecs = $timePieces[1] . " and " . $timePieces[2];
    } else {
	    $hoursMinSecs = $timePieces[0] . ", " . $timePieces[1] . ", "
		. " and " . $timePieces[2];
	}

    return $hoursMinSecs;
}

/* Handles singular and plural of trips */
function convert_trips($trips) {

  if ($trips == 1) {
      $showTrips = "1 trip";
  } else {
      $showTrips = number_format($trips) . " trips";
  }
  
  return $showTrips;

}

function was_or_were($number) {
  if ($number == 1) {
      $wasOrWere = " was ";
  } else {
      $wasOrWere = " were ";
  }
  
  return $wasOrWere;

}

?>