var mysql = require('mysql');
require('dotenv').config();

var db_host = (process.env.NODE_ENV == 'development') ? process.env.REMOTE_HOST : 'localhost';

var pool = mysql.createPool({
  host : db_host,
  user: process.env.MYSQL_U,
  password: process.env.MYSQL_P,
  database: 'timer'
});

exports.pool = pool;
