## The Bikeshare Trip Timer

[The Bikeshare Trip Timer](http://rausnitz.com/timer/) is a web app that uses real trip history [data](https://www.capitalbikeshare.com/trip-history-data) to show how long it takes Capital Bikeshare users to travel between any given pair of stations. The app is currently based on the data from 2015 (more than 3 million trips).

### <a name="about_averages"></a> Calculating average durations

The averages shown in the app are based *only* on trips that lasted less than four times as long as the shortest trip for the chosen pair of stations. This helps remove the most indirect trips from the set of trips used to calculate the average.

(A user might rent a bike from one station, ride around for an hour, and then return the bike near where they started. The trip history data doesn't tell us where users go&mdash;only where their trips started and ended.)

Let's say the durations for a pair of stations are 2 minutes, 2 minutes, 2 minutes, 3 minutes, and 20 minutes. The average duration would be 2 minutes and 15 seconds, because the 20-minute trip would be discarded.

The chart displays only those trips included in the average. The exception is when the number of trips included in the average is fewer than half the complete set of trips. (This is common for pairs of stations located near each other in tourist areas. It's not unusual for users to take a bike and ride around for much longer than it takes to complete the short trip between these pairs.) In these cases, the chart will show all trips that lasted less than 20 times as long as the shortest trip. But even in these cases, the average is still based on trips that lasted less than four times as long as the shortest trip.

### Credits

The Bikeshare Trip Timer was made by [Zach Rausnitz](http://rausnitz.com/timer/) and depends heavily on [Leaflet](http://leafletjs.com/) and [D3.js](https://d3js.org/).

### License

See LICENSE for details.
