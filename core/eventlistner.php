<?php

//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log.txt', print_r($_REQUEST, true)."\r\n", FILE_APPEND);

if(empty($_REQUEST['ACTION'])) {
    return false;
}

$result = [];
$fatherScript = FatherScript::Instance();

try {
    switch ($_REQUEST['ACTION']) {
        case 'getInfoByDate':
            $result = $fatherScript->select('WorkSchedule')
                ->where(['DATE' => $_REQUEST['DATE']])
                ->execute()[0];
            break;

        case 'delete':
            if(!empty($_REQUEST['ID'])) {
                $fatherScript->delete($_REQUEST['ACTION'])
                    ->where(['id' => (int)$_REQUEST['ID']])
                    ->execute();
            }
            break;

        case 'edit':
            if(!empty($_REQUEST['ID'])) {
                $fatherScript->update($_REQUEST['ACTION'])
                    ->values([])
                    ->where(['id' => (int)$_REQUEST['ID']])
                    ->execute();
            }
            break;
    }

    header('Content-Type: application/json');
    exit(json_encode($result));

} catch(PDOException $e) {
    echo '<b>Ошибка: ' . $e->getMessage().'</b>';
}