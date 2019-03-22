<?php
$class = 'alert';//message
//echo 'fgfh:',$params['class'];die;
if (!empty($params['class'])) {
    if($params['class'] == 'success'){
        $class .= ' alert-success';
    }else if($params['class'] == 'error'){
        $class .= ' alert-danger';
    }
    //$class .= ' ' . $params['class'];
}
?>

<div class="<?= h($class) ?>"><?= h($message) ?></div>
