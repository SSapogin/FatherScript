<?php

function arrangeByKey($data, $key)
{
    if (!$data || !$key) {
        return $data;
    }
    $result = [];
    foreach ($data as $item) {
        if (!$item[$key]) {
            continue;
        }
        $result[$item[$key]] = $item;
    }
    return $result;
}