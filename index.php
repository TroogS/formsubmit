<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'rb.php';

var_dump($_GET);
foreach ($_GET as $key => $value)

R::setup('sqlite:db.db');
R::useFeatureSet( 'novice/latest' );

    $post = R::dispense( 'entry' );
    $post->identifier = 'Hello World';
    $post->ff = 'Hello World';
    $post->time = time();

    //create or update
    $id = R::store( $post );

// $db = new SQLite3('test.db');
// $version = $db->querySingle('SELECT SQLITE_VERSION()');

// echo $version . "\n";
?>asd