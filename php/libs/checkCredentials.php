<?php
//проверка на обязательные поля для строк
function checkDefaultKeyString($key, $data, &$errors)
{
    if (!array_key_exists($key, $data)) {
        return $errors[] = sprintf("поле %s обязательно для заполнения", $key);
    }
    if (!is_string($data[$key])) {
        return;     
    }
    if (!mb_strlen( trim($data[$key]) )) {
        return $errors[] = sprintf("поле %s обязательно для заполнения", $key);     
    }
}

//проверка на обязательные поля для массивов
function checkDefaultKeyArray($key, $data, &$errors)
{
    if (!array_key_exists($key, $data)) {
        return $errors[] = sprintf("поле %s обязательно для заполнения", $key);
    }
    if (!is_array($data[$key])) {
        return;     
    }
    if (!count($data[$key])) {
        return $errors[] = sprintf("передаётся пустой массив %s", $key);     
    }
}


//проверка на тип
function checkTypeValue($key, $data, $type, &$errors)
{
    if (!array_key_exists($key, $data)) return;

    switch ($type) {
        case 'string':
            if(!is_string($data[$key])) {
                $errors[] = sprintf("поле %s должно быть строкой", $key);
            }
            break;
        case 'array':
            if(!is_array($data[$key])) {
                $errors[] = sprintf("поле %s должно быть массивом", $key);
            }
            break;
    }
}
?>