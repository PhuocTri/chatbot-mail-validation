<?php
/* >_ Code by Vy Nghia */

$dbhost = 'localhost';
$dbuser = 'username';
$dbpass = 'password';
$dbname = 'database';

$con = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname, $con);