<form method="get" action="results.php">
<span class="choosing">Choose start station:</span>
<br />
<select name="stationstart">
<?php echo $dropdownOptions; ?>
</select><br /><br />
<span class="choosing">Choose end station:</span>
<br />
<select name="stationend">
<?php echo $dropdownOptions; ?>
</select><br />
<input type="submit" value="&rarr; Find average trip time" style="margin-top:25px">
</form>