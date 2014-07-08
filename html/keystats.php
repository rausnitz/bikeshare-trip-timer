<?php
$graphTrips = Array();

foreach ($allTrips as $value) {
	if ($value < (min($allTrips) * 4)) {
	  $graphTrips[] = round(($value / 60),2);
	}
	else if ($value < 600) {
      $graphTrips[] = round(($value / 60),2);
	}
}

if ((count($graphTrips) * 2) < count($allTrips)) {
  $graphTrips = Array();
  foreach ($allTrips as $value) {
	if ($value < (min($allTrips) * 20)) {
      $graphTrips[] = round(($value / 60),2);
	  }
	}
}

$includedTrips = Array();

foreach ($allTrips as $value) {
	if ($value <= (min($allTrips) * 4)) {
      $includedTrips[] = round(($value / 60),2);
	}
}

$nonLongAverage = array_sum($includedTrips)/count($includedTrips) * 60;
$excludedTrips = count($allTrips) - count($includedTrips);

$graphEnd = max(ceil(max($graphTrips)),10);

?>

<script src="http://d3js.org/d3.v3.min.js"></script>

<p>Capital Bikeshare members took
<span class='key-stats'> <?php echo convert_trips(count($allTrips)); ?></span>
between the stations you chose. The average<sup>&lowast;</sup> trip lasted
<span class='key-stats'><?php echo convert_seconds($nonLongAverage); ?></span>.</p>
	
<div id="chart"></div>

<script>
	
var values = <?php echo json_encode($graphTrips);?>;
var last = <?php echo $graphEnd;?>;

var formatCount = d3.format(",.0f");

var margin = {top: 10, right: 10, bottom: 40, left: 10},
    width = 640 - margin.left - margin.right,
    height = 120 - margin.top - margin.bottom;

var x = d3.scale.linear()
    .domain([0, last])
    .range([0, width]);

var data = d3.layout.histogram()
    .bins(x.ticks(last))
    (values);

var y = d3.scale.linear()
    .domain([0, d3.max(data, function(d) { return d.y; })])
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var svg = d3.select("#chart").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

var bar = svg.selectAll(".bar")
    .data(data)
    .enter().append("g")
    .attr("class", "bar")
    .attr("transform", function(d) { return "translate(" + x(d.x) + "," + y(d.y) + ")"; });

bar.append("rect")
    .attr("x", 1)
    .attr("width", x(data[0].dx) - 1)
    .attr("height", function(d) { return height - y(d.y); });
	
bar.append("text")
    .attr("dy", ".75em")
    .attr("y", -10)
    .attr("x", x(data[0].dx) / 2 )
    .attr("text-anchor", "middle")
	.attr("font-weight","bold")
	.text(function(d) { return formatCount(d.y); });

svg.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height + ")")
    .call(xAxis);
	
svg.append("text")
    .attr("x", width / 2 )
    .attr("y",  height + margin.bottom)
    .style("text-anchor", "middle")
	.attr("class","axis-label")
    .text("DURATION IN MINUTES");
	
svg.append("text")
    .attr("transform", "rotate(-90)")
    .attr("y", 0-margin.left)
    .attr("x",0 - (height / 2))
    .attr("dy", "1em")
	.attr("class","axis-label")
    .text("TRIPS");
 
</script>