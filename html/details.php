<p>
<a href='results.php?stationstart=<?php echo $endStation; ?>&stationend=<?php echo $startStation; ?>'>
<strong>Click here to reverse the start and end stations.</strong></a>
</p>

<p>
<img src='<?php echo $staticMap; ?>' />
</p>

<p>Here are the travel time estimates from Google Maps, not accounting for traffic or parking:
<ul>

<?php

/* Produces each estimate as a list item e.g. "8 minutes and 44 seconds by car" */
foreach ($modeCompare as $mode) {
    echo $mode;
}
	
	?>

</ul></p>