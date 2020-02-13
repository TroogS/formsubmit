<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require 'rb-sqlite.php';

// Security checks
$getDataCount = count($_GET);
if($getDataCount < 2) exit("ERR: Missing arguments");
if(!array_key_exists("formsubmitkey", $_GET) || $_GET["formsubmitkey"] == "") exit("ERR: Missing key");

// Prepare get data
$formSubmitKey = $_GET["formsubmitkey"];
unset($_GET["formsubmitkey"]);
$serializedData = serialize($_GET);

// Prepare sqlite connection
R::setup('sqlite:db.db');
R::useFeatureSet( 'novice/latest' );

// Write entry
$entry = R::dispense( 'entry' );
$entry->time = time();
$entry->submitKey = $formSubmitKey;
$entry->data = $serializedData;
R::store( $entry );

exit("OK");
?>