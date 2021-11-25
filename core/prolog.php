<?php
include('functions.php');
include('fatherScript.php');
include('eventlistner.php');

$fatherScript = FatherScript::Instance();

$firstDays = $fatherScript->select('WorkSchedule')->where(['DATE' => date("Y-m-d", strtotime("-6 month"))], '>=')->execute();
$secondDays = $fatherScript->select('WorkSchedule')->where(['DATE' => date("Y-m-d", strtotime("+6 month"))], '<=')->execute();
$days = $firstDays+$secondDays;

$firstEvents = $fatherScript->select('Events')->where(['DATE' => date("Y-m-d", strtotime("-6 month"))], '>=')->execute();
$secondEvents = $fatherScript->select('Events')->where(['DATE' => date("Y-m-d", strtotime("+6 month"))], '<=')->execute();
$events = $firstEvents+$secondEvents;

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
$eventsForJs = json_encode($events);
$daysForJs = json_encode($days);

echo '<script>';
echo 'window.globalData = {};';
echo 'window.globalData.events ='. $eventsForJs.';';
echo 'window.globalData.days ='. $daysForJs.';';
echo '</script>';