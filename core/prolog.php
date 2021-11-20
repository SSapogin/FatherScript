<?php
include('functions.php');
include('fatherScript.php');

$fatherScript = FatherScript::Instance();
$events = $fatherScript->select('Events')->where(['DATE' => '2021-11-01'], '>=')->execute();
$secondary[TAG_NAME_TABLE] = arrangeByKey($fatherScript->select('Tags')->execute(), 'ID');
$secondary[CATEGORY_NAME_TABLE] = arrangeByKey($fatherScript->select('Categories')->execute(), 'ID');

$searchKey = [TAG_NAME_TABLE, CATEGORY_NAME_TABLE];
foreach ($events as &$event) {
    foreach ($searchKey as $key) {
        if(!$key)
            continue;

        $event[$key] = $secondary[$key][$event[$key]];
    }
}
unset($event);

echo '<pre>';

var_dump($events);