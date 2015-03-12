<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$call = "http://api.rottentomatoes.com/api/public/v1.0/movies.json?apikey=cew4h9q6xfgp9z6ywbjac79h&q=fjfeahfueaw";
$data[] = json_decode(file_get_contents($call));
print_r($data[0]);
//$newURL = $data[0]->movies[1]->title;
//print_r($newURL);
?>