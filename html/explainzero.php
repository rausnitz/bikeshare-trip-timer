<p>

<?php

$startInstalled = $startStationData[4];
$endInstalled = $endStationData[4];

$atLeastAMonth = 86400 * 31;
$atLeastSixMonths = $atLeastAMonth * 6;

/* Finds how long the newer of the two stations existed before the data end date.
Don't use $ageInDays if both stations were installed in the previous year. */

$ageInDays = date('z', $dataEndDate) - date('z', max($startInstalled, $endInstalled));

$spelledNumbers = array(
"zero",
"one",
"two",
"three",
"four",
"five",
"six",
"seven",
"eight",
"nine"
);

/* Says how long the newer station existed before the data end date.
This only works if the station installations and data end date were in the same year.
An if statement later in this file tests for that. */
if ($ageInDays < 2) {
  $fewDays = "less than 48 hours before ";
} elseif ($ageInDays < 10) {
  $fewDays = "only " . $spelledNumbers[$ageInDays] . " days before ";
} else {
  $fewDays = "only " . $ageInDays . " days before ";
}

/* These are phrases used repeatedly in the explanations for there being no trips. */
$explanationA = "No data is available, because ";
$explanationB = " installed after " . date('F j, Y', $dataEndDate);
$explanationC = "&mdash;the most recent date for which trip data is available";
$explanationD = "Capital Bikeshare members took <span class='key-stats'>0 trips</span>"
. " between the stations you chose in 2013. ";
$explanationE = "there's no clear reason why there were no trips. "
. "The stations may just be very far away from each other.";
$explanationF = "installed " . $fewDays . date('F j, Y', $dataEndDate);
$explanationG = "&mdash;which may explain why no trips have been recorded yet.";

/* These are groups of phrases used more than once together. */
$explanationFCG = $explanationF . $explanationC . $explanationG;
$explanationBCX = $explanationB . $explanationC . ".";
$explanationXXCXE = " before "	. date('F j, Y', $dataEndDate) . $explanationC . "&mdash;so " . $explanationE;
$explanationDX = $explanationD . "Both stations were in existence for at least ";

if ($startInstalled > $dataEndDate && $endInstalled > $dataEndDate) {
    /* Both stations installed after the data end date. */
	echo $explanationA . "both stations were" . $explanationBCX;
} elseif ($startInstalled > $dataEndDate) {
    /* Start station installed after data end date. */
	echo $explanationA . "the start station was" . $explanationBCX;
} elseif ($endInstalled > $dataEndDate) {
    /* End station installed after data end date. */
	echo $explanationA . "the end station was" . $explanationBCX;
} elseif ($startInstalled < $dataStartDate && $endInstalled  < $dataStartDate) {	
	/* Both stations installed before data start date. */
	echo $explanationD . "Both stations have been in existence since before 2013, so "	. $explanationE;
} elseif ($dataEndDate - $startInstalled > $atLeastSixMonths
          && $dataEndDate - $endInstalled > $atLeastSixMonths) {
	/* Both stations installed at least six months before data end date. */
	echo $explanationDX. "six months" . $explanationXXCXE;	
} elseif ($dataEndDate - $startInstalled > $atLeastAMonth
          && $dataEndDate - $endInstalled > $atLeastAMonth) {	
	/* Both stations installed at least a month before data end date. */
	echo $explanationDX. "a month" . $explanationXXCXE;	
} elseif (date('z', $startInstalled) > date('z', $endInstalled)) {
	/* How many days the start station existed before data end date. */
	echo $explanationD . "The start station was " . $explanationFCG;
} elseif (date('z', $startInstalled) < date('z', $endInstalled)) {
	/* How many days the end station existed before data end date. */
	echo $explanationD . "The end station was " . $explanationFCG;
} elseif (date('z', $startInstalled) == date('z', $endInstalled)) {
	/* For stations installed on same day, how long before data end date. */
	echo $explanationD . "Both stations were " . $explanationFCG;
}
?>

</p>
<p>The next batch of trip data (for January 1 to March 31) is due to be released later this spring.</p>