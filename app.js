var express = require('express');
var app = express();
var router = express.Router();

var d3_array = require('d3-array');
var humanizeDuration = require('humanize-duration');
var db = require('./db.js');

app.use('/timer', router);
app.set('view engine', 'pug');
app.locals.pretty = true;
app.listen(8080);

router.use(express.static('public'));

router.get('/', function (req, res) {
  var pool = db.pool;
  pool.getConnection(function(err, connection) {
    connection.query('SELECT id, name, lat, lon FROM stations', function(err, results) {
      connection.release();
      res.render('index', {
        stations: results
      });
    });
  });
});

router.get('/results/:start_station/:end_station/', function (req, res) {
  var start_station, end_station;
  var pool = db.pool;
  pool.getConnection(function(err, connection) {
    connection.query('SELECT * FROM stations WHERE id = ?', [req.params.start_station], function(err, results) {
    start_station = results[0];
    var start_name = start_station.name;
      connection.query('SELECT * FROM stations WHERE id = ?', [req.params.end_station], function(err, results) {
        end_station = results[0];
        var end_name = end_station.name;
        connection.query('SELECT duration FROM history WHERE start_station = ? AND end_station = ?', [start_name, end_name], function(err, results) {
          connection.release();
          var durations = [];
          for (var i = 0; i < results.length; i++) {
            durations.push(results[i].duration);
          }
          var any_results = durations.length > 0 ? true : false;
          var shortest_trip = d3_array.min(durations);
          var chart_values = [], chart_values_if_needed = [];
          for (var i = 0; i < durations.length; i++) {
            if (durations[i] < shortest_trip * 4) {
              chart_values.push(durations[i]);
            } else if (durations[i] < shortest_trip * 20) {
              chart_values_if_needed.push(durations[i]);
            }
          }
          var filler_results = chart_values.length * 2 < durations.length && chart_values_if_needed.length > 0 ? true: false;
          var average_duration = humanizeDuration(d3_array.sum(chart_values) / chart_values.length, {round: true, conjunction: ' and '});
          var send = {
              any_results: any_results,
              filler_results: filler_results,
              durations: durations,
              chart_values: chart_values,
              chart_values_if_needed: chart_values_if_needed,
              average_duration: average_duration
          }
          res.type('json');
          res.send(send);
        });
      });
    });
  });
});
