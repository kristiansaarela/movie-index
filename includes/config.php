<?php

session_start(); /** so we don't need to write it so often **/
error_reporting(0);

/** database details **/
define('HOSTNAME', 'localhost');
define('DATABASE_USER', 'root');
define('DATABASE_PASS', '');
define('DATABASE_NAME', 'movie');
define('DSN', 'mysql:host=' . HOSTNAME . ';dbname=' . DATABASE_NAME);

/** full url, needed incase we use mod_rewrite **/
define('BASE_URL', 'http://localhost/hd-united-index/');

include('quotes.php'); /** quotes, taglines w/e **/
include('functions.php'); /** functions, mostly for design **/

/** global arrays **/
$generes	= array('action', 'adventure', 'animation', 'biography', 'comedy',
					'crime', 'documentary', 'drama', 'family', 'fantasy', 'foreign',
					'history', 'horror', 'musical', 'mystery', 'romance', 'scifi',
					'sport', 'thriller', 'war', 'western');

$alphabet	= array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l',
					'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
					'y', 'z', 'num');

$type       = array('1', '2', '3', '4', '5', '6');

$types		= array('1' => 'm-HD', '2' => '720p', '3' => '480p',
					'4' => 'TV-720p', '5' => 'TV-480p', '6' => 'Concerts');

$templates 	= array('default' => 'Default', 'sidereel' => 'Sidereel', 'zethian' => 'Zethian');

/** database connection using PDO **/
try
{
    $dbh = new PDO(DSN, DATABASE_USER, DATABASE_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOException $e)
{
    log_error($e->getMessage());
}

/** for debug **/
function e($a)
{
    echo "<pre>"; print_r($a); echo "</pre>";
}