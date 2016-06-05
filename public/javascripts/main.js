var markers = new L.featureGroup(), start_marker = new L.featureGroup(), end_marker = new L.featureGroup(), chosen_markers = new L.featureGroup(), default_marker_options = {fillOpacity: 0.5, color: '#888'}, start_marker_options = {color: 'green', fillOpacity: 0.8}, end_marker_options = {color: 'red', fillOpacity: 0.8};
var window_width = $(window).width(), state = 'choosing', transition = false, event_ready = true, initial_display = true, target_start = true, selected_start, selected_end, static_map_height = 125, url = [location.protocol, '//', location.host, location.pathname].join(''), chart_values = [];

var map = L.map('map', {
  minZoom: 9
});

L.tileLayer(
    'https://api.mapbox.com/styles/v1/' + mapbox_style + '/tiles/{z}/{x}/{y}?access_token=' + mapbox_token, {
        tileSize: 512,
        zoomOffset: -1,
        attribution: '© <a href="https://www.mapbox.com/map-feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

function adjust_view() {
  if (state == 'choosing') {
    // make map interactive
    map.dragging.enable();
    map.touchZoom.enable();
    map.doubleClickZoom.enable();
    map.scrollWheelZoom.enable();
    map.keyboard.enable();
    if (map.tap) map.tap.enable();

    $('#chart_container').hide();
    $('#about').html('<a href="https://github.com/rausnitz/bikeshare-trip-timer" target="_blank">About</a>').show();
    $('#map').animate({
      height: $(window).height() - $('#below_map').height()
    }, (transition ? 250 : 0), function() {
      map.invalidateSize();
      if (initial_display && markers.getBounds().isValid()) map.fitBounds(markers.getBounds());
      initial_display = false;
    });
    $('#map').css('cursor', 'grab');
    $('.leaflet-control-zoom').show();
    transition = false;
  }
  if (state == 'viewing') {
    event_ready = false;
    $('#map').css('cursor', 'default');
    $('#map').animate({
      height: static_map_height
    }, 250, function() {
      map.invalidateSize();
      chosen_markers = new L.featureGroup();
      start_marker.addTo(chosen_markers);
      end_marker.addTo(chosen_markers);
      markers.clearLayers();
      chosen_markers.addTo(map);
      draw_chart(chart_values);
      if (chosen_markers.getBounds().isValid()) map.fitBounds(chosen_markers.getBounds(), {animate: false, padding: [20,20]});
      event_ready = true;
    });
    $('#map path').css('cursor', 'default');
    $('.leaflet-control-zoom').hide();

    // make map static
    map.dragging.disable();
    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();
    map.keyboard.disable();
    if (map.tap) map.tap.disable();
  }
}

function initialize_markers() {
  markers.clearLayers();
  start_marker.clearLayers();
  end_marker.clearLayers();
  chosen_markers.clearLayers();
  var GenericMarker = L.CircleMarker.extend({
     options: default_marker_options
  });
  for (var i = 0; i < stations.length; i++) {
    var s = stations[i];
    var radius = map.tap ? 14 : 10; // larger markers on touchscreens
    new GenericMarker([s.lat, s.lon], {station_index: i}).on('click', on_marker_click).setRadius(radius).addTo(markers);
  }
  markers.addTo(map);
  $('#go').html('Click the map markers to choose stations.');
}

function on_marker_click() {
  var selected_index = this.options.station_index;
  var selected_name = stations[selected_index].name;
  function setColors() {
    markers.setStyle(default_marker_options);
    start_marker.setStyle(start_marker_options).bringToFront();
    end_marker.setStyle(end_marker_options).bringToFront();
    if (selected_start == selected_end) end_marker.setStyle({color: 'yellow'});
  }
  if (state == 'choosing') {
    if (target_start) {
      $('#selected_start .selected_station').hide().html(selected_name).fadeIn(500);
      $('#selected_end .pointer').show();
      $('#selected_start .pointer').hide();
      selected_start = stations[selected_index].id;
      start_marker = new L.featureGroup();
      this.addTo(start_marker);
      setColors();
    } else {
      $('#selected_end .selected_station').hide().html(selected_name).fadeIn(500);
      $('#selected_end .pointer').hide();
      $('#selected_start .pointer').show();
      selected_end = stations[selected_index].id;
      end_marker = new L.featureGroup();
      this.addTo(end_marker);
      setColors();
    }
    target_start = !target_start;
    if (selected_start && selected_end && selected_start != selected_end) {
      $('#go').html('<span id="go_click">Click here for the results</span>. Or keep choosing stations.');
      $( '#go_click' ).click(function() {
        get_durations();
      });
    } else if (selected_start == selected_end) {
      $('#go').html('Choose two different stations.');
    } else {
      $('#go').html('Choose another station.');
    }
    if (selected_start && selected_end) {
      $('.pointer').css('color', '#888')
    }
    adjust_view();
  }
}

function get_durations() {
  $('.pointer').hide();
  $('#go').html('<img src="images/loading-bar.gif">');
  adjust_view();
  $.getJSON( url + 'results/' + selected_start + '/' + selected_end + '/', function(data) {
    if (data.any_results) {
      show_results(data);
      state = 'viewing';
      adjust_view();
      $('#favicon').attr('href', 'images/favicon-green.png');
      $('#go').html('<span id="start_over">Click here to choose new stations</span>.');
      var message = '', disclaimer = '', number_format = d3.format(',');
      message += data.durations.length == 1 ? 'There was <span class="results_number">1</span> trip' : 'There were <span class="results_number">' + number_format(data.durations.length) + '</span> trips';
      message += ' between the stations you chose in 2015. ';
      message += data.durations.length == 1 ? 'It lasted <span class="results_number">' : 'The average trip lasted <span class="results_number">';
      message += data.average_duration + '</span>.';
      $('#about').hide();
      if (chart_values.length < data.durations.length) {
        disclaimer = 'Read more about how the average is calculated <a href="https://github.com/rausnitz/bikeshare-trip-timer#about_averages" target="_blank">here</a>.';
        $('#about').html(disclaimer).show();
      }
      $('#results_info').html(message).show();
      $('#start_over').click(function() {
        if (event_ready) {
          $('#results_info').hide();
          $('.selected_station').html('');
          $('.pointer').css('color', 'black');
          $('#selected_start .pointer').show();
          selected_start = null, selected_end = null, target_start = true, transition = true;
          initialize_markers();
          state = 'choosing';
          adjust_view();
        }
      });
    } else {
      $('#go').html('<span id="no_trips">No trips were taken between the stations you chose in 2015. Click the map markers to try other stations.</span>').fadeIn(500);
      adjust_view();
      $('#favicon').attr('href', 'images/favicon-red.png');
    }
  });
}

function show_results(duration_data) {
  chart_values = [];
  for (var i = 0; i < duration_data.chart_values.length; i++) {
    chart_values.push(duration_data.chart_values[i] / 60000);
  }
  if (duration_data.filler_results) {
    for (var i = 0; i < duration_data.chart_values_if_needed.length; i++) {
      chart_values.push(duration_data.chart_values_if_needed[i] / 60000);
    }
  }
  draw_chart(chart_values);
}

function draw_chart(values) {
  $('#chart_container').show();
  $('#chart').empty();
  $('#chart').show();

  var last = Math.floor(d3.max(values)) + 1;
  var margin = {top: 20, bottom: 15, left: 20, right: 20};
  var width = $('#chart').width() - margin.left - margin.right;
  var height = Math.max($(window).height() - $('#below_map').height() - static_map_height - margin.top - margin.bottom - 10, 100);
  var svg = d3.select('#chart').append('svg')
    .attr('width', $('#chart').width())
    .attr('height', height + margin.top + margin.bottom);
  var g = svg.append('g').attr('transform', 'translate(' + margin.left + ',' + margin.top + ')');
  $('#chart_container, #chart').css('height', height + margin.top + margin.bottom);

  var x = d3.scale.linear().domain([0, last]).range([0, width]);
  var histogram = d3.layout.histogram().bins(x.ticks(last));
  var data = histogram(values);
  var inner_height = height - margin.top - margin.bottom;
  var y = d3.scale.linear().domain([0, d3.max(data, function(d) { return d.y; })]).range([inner_height, 0]);

  var bar = g.selectAll('.bar')
    .data(data)
    .enter().append('g')
    .attr('class', 'bar')
    .attr('transform', function(d) { return 'translate(' + x(d.x) + ',' + y(d.y) + ')'; });

  bar.append('rect')
    .attr('x', 1)
    .attr('width', d3.max([x(data[0].dx) - 1, 1]))
    .attr('height', function(d) { return inner_height - y(d.y); });

  bar.append('text')
    .attr('y', -2)
    .attr('x', x(data[0].dx) / 2 )
    .attr('text-anchor', 'middle')
    .attr('class', 'bar_label')
    .text(function(d) { return d3.format(',.0f')(d.y); })
    .style('visibility', function(d) {
      if (d.y == 0) return 'hidden';
    });

  var hide_all_bars = false;
  d3.selectAll('.bar_label').each(function() {
      if (d3.select(this).node().getBBox().width > x(data[0].dx) - 1) hide_all_bars = true;
  });
  if (hide_all_bars) d3.selectAll('.bar_label').style('visibility', 'hidden');

  var ticks = d3.min([width / 100, 10]);
  var x_axis = d3.svg.axis().scale(x).orient('bottom').tickFormat(d3.format("d")).ticks(ticks);
  g.append('g')
    .attr('class', 'x axis')
    .attr('transform', 'translate(0,' + inner_height + ')')
    .call(x_axis);

  g.append('text')
    .attr('x', width / 2 )
    .attr('y',  inner_height + margin.top + margin.bottom)
    .attr('text-anchor', 'middle')
    .attr('class', 'axis-label')
    .text('duration in minutes');

  g.append('text')
    .attr('transform', 'rotate(-90)')
    .attr('text-anchor', 'middle')
    .attr('y', -2)
    .attr('x', inner_height / -2)
    .attr('class', 'axis-label')
    .text('trips');
}

// initial view
$('#results_info').hide();
$('#selected_end .pointer').hide();
$('#go').html('<img src="images/loading-bar.gif" width="1" height="1">'); // preload this gif
$('#go').empty();
initialize_markers();
adjust_view();
$(window).on( 'orientationchange', function() { adjust_view(); } );
$(window).on( 'resize', function() { adjust_view(); } );
