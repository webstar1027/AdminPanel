<?php
set_time_limit(0);
//database connection details
$con = mysqli_connect("gotribeprod-1.cluster-cpqh8v9nt9lt.us-west-1.rds.amazonaws.com","gotribe_aws","PHeYEbrest?3&r6r","gotribe_prod");

// Check connection
if (mysqli_connect_errno())
    echo "Failed to connect to MySQL: " . mysqli_connect_error();

$sqls = "select id, duration, zonesDuration from gym_user_workout";
$result = mysqli_query($con,$sqls);

while($rows=mysqli_fetch_assoc($result)){
    $newZonesDurationArr = array();
    $duration = $rows['duration'] * 1000;
    $zonesDuration = $rows['zonesDuration'];
    $zonesDurationArr = explode(',', trim($zonesDuration));
    foreach($zonesDurationArr as $v)
        $newZonesDurationArr[] = $v * 1000;
    
    $newZonesDuration = implode(',',$newZonesDurationArr);
    $sql = "update `gym_user_workout` set `duration`='".$duration."', `zonesDuration`='".$newZonesDuration."' where id='".$rows['id']."'";
    if(!mysqli_query($con, $sql))
        echo "Error updating record: " . mysqli_error($con);
}
mysql_close($con);
?>
