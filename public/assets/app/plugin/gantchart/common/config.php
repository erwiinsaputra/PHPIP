<?php

include ('../../codebase/connector/db_sqlite3.php');
include ('../../codebase/connector/gantt_connector.php');

// SQLite
// $dbtype = "SQLite3";
// $res = new SQLite3(dirname(__FILE__)."/samples.sqlite");
// $res->busyTimeout(1000);

// Mysql
require_once ("../../codebase/connector/db_pdo.php");
$res = new PDO("mysql:host=localhost;dbname=gant_chart", "", "");
$dbtype = "PDO";

?>