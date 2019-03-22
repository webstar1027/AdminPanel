<?php
set_time_limit(0);
error_reporting(1);
//database connection details
$con = mysqli_connect("gotribeprod-1.cluster-cpqh8v9nt9lt.us-west-1.rds.amazonaws.com","gotribe_aws","PHeYEbrest?3&r6r","gotribe_prod");

// Check connection
 if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

//your database name
//$cid =mysql_select_db('test',$connect);

// path where your CSV file is located
define('CSV_PATH','/var/www/html/webroot/css/');

// Name of your CSV file
$csv_file = CSV_PATH . "ce.csv"; 


if (($handle = fopen($csv_file, "r")) !== FALSE) {
   fgetcsv($handle);   
$i=0;   
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

//print_r($data); die;
        $num = count($data);
        for ($c=0; $c < $num; $c++) {


if($c==33)
{
$col[$i]['Status'] = ($data[$c]=='True')?0:1;
}



if($c==20)
{
$col[$i]['Email'] = $data[$c];

}

if($c==21)
{
$col[$i]['DOB'] = date('Y-m-d',strtotime($data[$c]));
}
 
 






}
 if($col[$i]['DOB']!=''){
   echo $sql = " Update gym_member set birth_date='".$col[$i]['DOB']."' where email='".$col[$i]['Email']."'"; 
  //mysql_query($sql);
 
 if (mysqli_query($con, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
}
 
 
 }
    fclose($handle);
}


echo "File data successfully imported to database!!";

?>
