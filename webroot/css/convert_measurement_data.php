<?php
set_time_limit(0);
//database connection details
$con = mysqli_connect("gotribeprod-1.cluster-cpqh8v9nt9lt.us-west-1.rds.amazonaws.com","gotribe_aws","PHeYEbrest?3&r6r","gotribe_prod");

// Check connection
if (mysqli_connect_errno())
    echo "Failed to connect to MySQL: " . mysqli_connect_error();

$sqls = "select * from member_measurement";
$result = mysqli_query($con,$sqls);

while($rows=mysqli_fetch_assoc($result)){
    $weight = $rows['weight'] / 0.45359237;
    $leanBodyMass = $rows['leanBodyMass'] / 0.45359237;
    $boneDensity = $rows['boneDensity'] / 0.45359237;
    
    //@$rows['height'] height in centimeter
    $heightInch = $rows['height'] * 0.39370079; // 1 cm = 0.39370079 inch
    $heightFootDouble = $heightInch / 12; // @$heightFootDouble (double ); 1 foot = 12 inch
    $heightFootDoubleArr = explode('.',$heightFootDouble); 
    $heightFootInteger = $heightFootDoubleArr[0]; // foot integer
    $heightFootFloat = $heightFootDoubleArr[1]; // foot float
    $heightInchInteger = ('0.'.$heightFootFloat) * 12;
    
    $height = $heightFootInteger.'.'.round($heightInchInteger); //@height (eg. 5.8)
    
    $neckInch = $rows['neck'] * 0.39370079;
    $chestInch = $rows['chest'] * 0.39370079;
    $circumferenceBicepInch = $rows['circumferenceBicep'] * 0.39370079;
    $forearmInch = $rows['forearm'] * 0.39370079;
    $waistInch = $rows['waist'] * 0.39370079;
    $hipInch = $rows['hip'] * 0.39370079;
    $thighInch = $rows['thigh'] * 0.39370079;
    $calfInch = $rows['calf'] * 0.39370079;
    
    
    //echo $height;
    //echo '<br>';
    
    $sql = "update `member_measurement` set `weight`='".$weight."', `leanBodyMass`='".$leanBodyMass."', `boneDensity`='".$boneDensity."', `height`='".$height."', `neck`='".$neckInch."', `chest`='".$chestInch."', `circumferenceBicep`='".$circumferenceBicepInch."', `forearm`='".$forearmInch."', `waist`='".$waistInch."', `hip`='".$hipInch."', `thigh`='".$thighInch."', `calf`='".$calfInch."' where id='".$rows['id']."'";
    if(!mysqli_query($con, $sql))
        echo "Error updating record: " . mysqli_error($con);
}


$sqls = "select * from member_measurement_history";
$result = mysqli_query($con,$sqls);

while($rows=mysqli_fetch_assoc($result)){
    $weight = $rows['weight'] / 0.45359237;
    $leanBodyMass = $rows['leanBodyMass'] / 0.45359237;
    $boneDensity = $rows['boneDensity'] / 0.45359237;
    
    //@$rows['height'] height in centimeter
    $heightInch = $rows['height'] * 0.39370079; // 1 cm = 0.39370079 inch
    $heightFootDouble = $heightInch / 12; // @$heightFootDouble (double ); 1 foot = 12 inch
    $heightFootDoubleArr = explode('.',$heightFootDouble); 
    $heightFootInteger = $heightFootDoubleArr[0]; // foot integer
    $heightFootFloat = $heightFootDoubleArr[1]; // foot float
    $heightInchInteger = ('0.'.$heightFootFloat) * 12;
    
    $height = $heightFootInteger.'.'.round($heightInchInteger); //@height (eg. 5.8)
    
    $neckInch = $rows['neck'] * 0.39370079;
    $chestInch = $rows['chest'] * 0.39370079;
    $circumferenceBicepInch = $rows['circumferenceBicep'] * 0.39370079;
    $forearmInch = $rows['forearm'] * 0.39370079;
    $waistInch = $rows['waist'] * 0.39370079;
    $hipInch = $rows['hip'] * 0.39370079;
    $thighInch = $rows['thigh'] * 0.39370079;
    $calfInch = $rows['calf'] * 0.39370079;
    
    
    //echo $height;
    //echo '<br>';
    
    $sql = "update `member_measurement_history` set `weight`='".$weight."', `leanBodyMass`='".$leanBodyMass."', `boneDensity`='".$boneDensity."', `height`='".$height."', `neck`='".$neckInch."', `chest`='".$chestInch."', `circumferenceBicep`='".$circumferenceBicepInch."', `forearm`='".$forearmInch."', `waist`='".$waistInch."', `hip`='".$hipInch."', `thigh`='".$thighInch."', `calf`='".$calfInch."' where id='".$rows['id']."'";
    if(!mysqli_query($con, $sql))
        echo "Error updating record: " . mysqli_error($con);
}
mysql_close($con);
?>
