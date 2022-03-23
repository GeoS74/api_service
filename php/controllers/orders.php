<?php
require_once(__DIR__.'/../libs/checkCredentials.php');

function checkCredentialsOrder($data)
{
    $errors = [];

    //проверка на обязательные поля
    checkDefaultKeyString('uid', $data, $errors);
    checkDefaultKeyString('order_num', $data, $errors);
    checkDefaultKeyString('garage_num', $data, $errors);
    checkDefaultKeyString('invent_num', $data, $errors);
    checkDefaultKeyString('reg_num', $data, $errors);
    checkDefaultKeyString('vin_code', $data, $errors);
    checkDefaultKeyString('car_model', $data, $errors);

    //проверка на тип
    checkTypeValue('uid', $data, 'string', $errors);
    checkTypeValue('order_num', $data, 'string', $errors);
    checkTypeValue('garage_num', $data, 'string', $errors);
    checkTypeValue('invent_num', $data, 'string', $errors);
    checkTypeValue('reg_num', $data, 'string', $errors);
    checkTypeValue('vin_code', $data, 'string', $errors);
    checkTypeValue('car_model', $data, 'string', $errors);
    checkTypeValue('car_type', $data, 'string', $errors);
    checkTypeValue('year_issue', $data, 'string', $errors);
    checkTypeValue('mileage', $data, 'string', $errors);
    checkTypeValue('problems', $data, 'array', $errors);
    checkTypeValue('contact', $data, 'string', $errors);
    checkTypeValue('basis', $data, 'string', $errors);
    checkTypeValue('comments', $data, 'string', $errors);

    if (count($errors)) {
        Flight::halt(400, json_encode(["errors" => $errors]));
    }
}

function checkDublicateOrder($data)
{
    $db = new DataBase();
    $result = $db->mysql_qw('SELECT uid FROM orders WHERE uid=? AND inn_customer=? LIMIT 1', $data['uid'],  $data['inn_customer']);
    if ($result->num_rows) {
        $errors = ["uid должен быть уникальным"];
        Flight::halt(400, json_encode(["errors" => $errors]));
    }
}

function addOrder($data)
{
    $query = "INSERT INTO orders (
        uid, 
        order_num, 
        garage_num,
        invent_num,
        reg_num, 
        vin_code, 
        car_model, 
        car_type, 
        year_issue,
        mileage, 
        problems, 
        contact,
        basis,
        comments,
        inn_customer) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $d = [
        $data['uid'],
        $data['order_num'],
        $data['garage_num'],
        $data['invent_num'],
        $data['reg_num'],
        $data['vin_code'],
        $data['car_model'],
        $data['car_type'],
        $data['year_issue'],
        $data['mileage'],
        serialize($data['problems']),
        $data['contact'],
        $data['basis'],
        $data['comments'],
        $data['inn_customer'],
    ];

    try {
        $db = new DataBase();
        $db->mysql_qw($query, $d);
        Flight::halt(201, 'Order created');
    } catch (Exception $error) {
        $errors = [$error->getMessage()];
        Flight::halt(400, json_encode(["errors" => $errors]));
    }
}
