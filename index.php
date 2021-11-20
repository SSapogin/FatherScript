<?php
include('core/fatherScript.php');

$fatherScript = FatherScript::Instance();
$events = $fatherScript->select('Events')->where(['DATE' => '2021-11-01', '>'])->execute();
echo '<pre>';
var_dump($events);

