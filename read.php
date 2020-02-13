<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require 'rb-sqlite.php';

// Security check
if(count($_GET) < 1 || !array_key_exists("formsubmitkey", $_GET) || $_GET["formsubmitkey"] == "") exit("Missing key");

$formSubmitKey = $_GET["formsubmitkey"];

// prepare 
R::setup('sqlite:db.db');
R::useFeatureSet( 'novice/latest' );

// Load entries
$entries = R::find('entry', ' submit_key LIKE ?', [ $formSubmitKey ] );
if(count($entries) == 0) exit("Keine Einträge");

// Build table data
$tableColumns = array();
$tableData = array();
foreach($entries as $entry) {
    if(isset($entry->data) && $entry->data != null && $entry->data != "") {

        // Build entry attributes
        $attributes = unserialize($entry->data);
        ksort($attributes);
        $tableColumns = array_merge($tableColumns, array_keys($attributes));
        

        $entryData = array(
            "time" => $entry->time,
            "attributes" => $attributes
        );
        array_push($tableData, $entryData);
    }
}

$tableColumns = array_unique($tableColumns);

// Build website
?>

<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= $formSubmitKey ?> Submissions</title>
        <style>
            table {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            table td, #customers th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            table tr:nth-child(even){background-color: #f2f2f2;}

            table tr:hover {background-color: #ddd;}

            table th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #4CAF50;
                color: white;
            }
        </style>
    </head>
    <body>
        <h1><?= $formSubmitKey ?> Submissions</h1>
        <table style="margin-bottom: 100px;">
            <tr>
            <th>Time</th>
                <?php foreach($tableColumns as $columnName): ?>
                    <th><?= $columnName ?></th>
                <?php endforeach; ?>
            </tr>

            <?php foreach($tableData as $tableRow): ?>
                <tr>
                    <td style="width: 1px; white-space: nowrap;"><?= date("d.m.Y - H:i:s", $tableRow["time"]) ?></td>
                    <?php foreach($tableColumns as $columnName): ?>
                        <td><?= $tableRow["attributes"][$columnName] ?? "" ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>
