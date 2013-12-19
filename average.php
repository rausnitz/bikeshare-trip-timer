<?php 

include('settings.php');
include('functions/favicon.php');
include('html/header.php');
include('html/title.php');

?>

<div id='explain'>
<p>The average excludes any trip that was more than four times as long as the fastest trip recorded for the chosen combination of stations.
Doing this helps weed out recreational or indirect rides&mdash;which often last several hours (or, in a few cases, several days) and would heavily distort the average if left in.</p>
<p>For the vast majority of station combinations, none of the trips were more than four times the duration of the fastest trip.
For a few though, this was very common.
For example, <a href='results.php?stationstart=31248&stationend=31217'>two stations located close together on the National Mall</a> seem to be used together mainly as pickup and dropoff points for leisurely rides,
but rarely to travel the short distance between them directly.</p>

<?php

include('html/footer.php');

?>