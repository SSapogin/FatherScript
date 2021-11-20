<?php

if(empty($_REQUEST['ACTION'])) {
    return false;
}

$fatherScript = FatherScript::Instance();

$fields = [];
foreach ($_REQUEST as $key => $request) {
    if(in_array($key, ['ACTION', 'EVENT', 'ID'])) {
        continue;
    }
    $fields[$key] = $request;
}

try {
    switch ($_REQUEST['EVENT']) {
        case 'added':
            $fatherScript->insert($_REQUEST['ACTION'])
                ->values($fields)
                ->execute();
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
                    ->values($fields)
                    ->where(['id' => (int)$_REQUEST['ID']])
                    ->execute();
            }
            break;
    }
} catch(PDOException $e) {
    echo '<b>Ошибка: ' . $e->getMessage().'</b>';
}