<div id='explain'>
<p><strong>Start station:</strong> <?php echo $startStationData[0]; ?><br />
<strong>End station:</strong> <?php echo $endStationData[0]; ?></p>

<?php
/* HTML comment below provides station installation dates for reference in HTML code. */
?>

<!--
Start station installed on <?php echo date('F j, Y', $startStationData[4]); ?>.
End station installed on <?php echo date('F j, Y', $endStationData[4]); ?>.
 -->