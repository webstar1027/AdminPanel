<?php
error_reporting(E_ALL); ini_set('display_errors', 1); 
$conn = new mysqli("pheramor-stag.cluster-cj8f6wiiyeqq.us-east-1.rds.amazonaws.com","pheramor_super","tBOibOJwEl%rS6","pheramor_stag_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$project = "select * from pheramor_movies where parent='0' and is_deleted='0'";
$result = $conn->query($project);
while($row = $result->fetch_assoc()) {
    $data[]=array($row['id'],$row['parent'],$row['title']);
}
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="movies.csv"');
 
// do not cache the file
header('Pragma: no-cache');
header('Expires: 0');

// create a file pointer connected to the output stream
$file = fopen('php://output', 'w');
 
// send the column headers
fputcsv($file, array('ID','Category', 'Name'));
 
// Sample data. This can be fetched from mysql too
// output each row of the data
foreach ($data as $row)
{
    fputcsv($file, $row);
}
 
exit();

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

