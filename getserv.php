<?php
include 'config.php';
$host= gethostname();
$ip = gethostbyname($host);
echo "host: "  . $host. " ip: " . $ip . "</br>";
$dbhost = $CFG->dbhost;
echo substr($dbhost, 6, 1);
