<?php
require_once(__DIR__ . '/../libs/checkCredentials.php');
require_once(__DIR__ . '/../libs/dataHandlers.php');

function checkCredentialsOrder($data)
{
    trimer($data);

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
//направление заявки на ремонт
function addOrder($data)
{
    trimer($data);

    //обязательные поля
    $column_name = [
        'uid',
        'order_num',
        'garage_num',
        'invent_num',
        'reg_num',
        'vin_code',
        'car_model',
    ];
    $d = [
        $data['uid'],
        $data['order_num'],
        $data['garage_num'],
        $data['invent_num'],
        $data['reg_num'],
        $data['vin_code'],
        $data['car_model'],
    ];

    //не обязательные поля
    if (array_key_exists('car_type', $data)) {
        $column_name[] = 'car_type';
        $d[] = $data['car_type'];
    }
    if (array_key_exists('year_issue', $data)) {
        $column_name[] = 'year_issue';
        $d[] = $data['year_issue'];
    }
    if (array_key_exists('mileage', $data)) {
        $column_name[] = 'mileage';
        $d[] = $data['mileage'];
    }
    if (array_key_exists('problems', $data)) {
        $column_name[] = 'problems';
        $d[] = serialize($data['problems']);
    }
    if (array_key_exists('contact', $data)) {
        $column_name[] = 'contact';
        $d[] = $data['contact'];
    }
    if (array_key_exists('basis', $data)) {
        $column_name[] = 'basis';
        $d[] = $data['basis'];
    }
    if (array_key_exists('comments', $data)) {
        $column_name[] = 'comments';
        $d[] = $data['comments'];
    }

    //добавляется приложением и не зависит от данных клиента
    $column_name[] = 'inn_customer';
    $d[] = $data['inn_customer'];

    //sql запрос
    $col_name = implode(', ', $column_name);
    $placeholders = array_fill(0, count($column_name), '?');
    $placeholders = implode(', ', $placeholders);
    $query = sprintf("INSERT INTO orders (%s) VALUES (%s)", $col_name, $placeholders);

    try {
        $db = new DataBase();
        $db->mysql_qw($query, $d);
        Flight::halt(201, 'Order created');
    } catch (Exception $error) {
        sqlErrorHandler($error->getMessage());
    }
}

//подтверждение начала ремонта
function updStartRepair($data)
{
    trimer($data);
    $uid = Flight::get('uid');

    $errors = [];
    checkDefaultKeyString('accept_start_repair', $data, $errors);
    checkTypeValue('accept_start_repair', $data, 'string', $errors);

    if($data['accept_start_repair'] !== 'true'){
        $errors[] = 'поле accept_start_repair содержит не допустимое значение';
    }

    if (count($errors)) {
        Flight::halt(400, json_encode(["errors" => $errors]));
    }
    
    $uid = explode('_', $uid);
    if (count($uid) < 2) Flight::halt(404, 'not found');
    else {
        $uid = $uid[1];
        $db = new DataBase();
        $result = $db->mysql_qw('SELECT id, accept_start_repair FROM orders WHERE id=?', $uid);
        if(!$result -> num_rows) Flight::halt(404, 'not found');
        else {
            if($result -> fetch_assoc()['accept_start_repair'] === 'true') Flight::halt(400, json_encode(["errors" => ['данные не могут быть изменены, ремонт был подтверждён ранее']]));
        }
    }

    $query = sprintf("UPDATE orders SET date_start_repair=DEFAULT, accept_start_repair=? WHERE id=?");

    try {
        $db = new DataBase();
        $db->mysql_qw($query, $data['accept_start_repair'], $uid);
        Flight::halt(200, 'start repair is accepted');
    } catch (Exception $error) {
        sqlErrorHandler($error->getMessage());
    }
}

function sqlErrorHandler($message)
{
    $errors = [];

    preg_match("/Data too long for column '([\w]+)'/", $message, $match);
    if (count($match) == 2) {
        $errors[] = sprintf("длина поля '%s' превышает допустимый размер", $match[1]);
    }

    preg_match("/Data truncated for column '([\w]+)'/", $message, $match);
    if (count($match) == 2) {
        $errors[] = sprintf("поле '%s' содержит не допустимое значение", $match[1]);
    }

    if(!count($errors)) $errors[] = $message;

    Flight::halt(400, json_encode(["errors" => $errors]));
}
//получить список заявок на ремонт
function getOrders()
{
    $client = Flight::get('client');

    $column_name = [
        'date_create',
        'accept_start_repair',
        // 'date_start_repair', //меняется в зависимости от значения accept_start_repair (true/false)

        // 'uid', //меняется в зависимости от роли клиента (заказчик/исполнитель)
        'order_num',
        'garage_num',
        'invent_num',
        'reg_num',
        'vin_code',
        'car_model',

        'car_type',
        'year_issue',
        'mileage',
        'problems',
        'contact',
        'basis',
        'comments'
    ];

    if ($client['is_customer'] === 'true') {
        $only_new = Flight::has('only_new') ? 'AND accept_start_repair="false"' : '';
        $query = sprintf("SELECT uid, IF(accept_start_repair='true', date_start_repair, '') AS date_start_repair, %s FROM orders WHERE inn_customer=? %s ORDER BY id DESC", implode(', ', $column_name), $only_new);
    } else {
        $only_new = Flight::has('only_new') ? 'WHERE accept_start_repair="false"' : '';
        $query = sprintf("SELECT CONCAT(inn_customer, '_', id) AS uid, IF(accept_start_repair='true', date_start_repair, '') AS date_start_repair, %s FROM orders %s ORDER BY id DESC", implode(', ', $column_name), $only_new);
    }

    try {
        $db = new DataBase();
        $data = $db->mysql_qw($query, $client['inn']);
        $result = [];

        if ($data->num_rows) {
            while ($row = $data->fetch_assoc()) {
                $row['problems'] = unserialize($row['problems']);
                if (!is_array($row['problems'])) $row['problems'] = [];

                $result[] = $row;
            }
        }
        Flight::halt(200, json_encode($result));
    } catch (Exception $error) {
        sqlErrorHandler($error->getMessage());
    }
}

//получить заявку по uid
function getOrder()
{
    $client = Flight::get('client');
    $uid = Flight::get('uid');

    $column_name = [
        'date_create',
        'accept_start_repair',
        // 'date_start_repair', //меняется в зависимости от значения accept_start_repair (true/false)

        // 'uid', //меняется в зависимости от роли клиента (заказчик/исполнитель)
        'order_num',
        'garage_num',
        'invent_num',
        'reg_num',
        'vin_code',
        'car_model',

        'car_type',
        'year_issue',
        'mileage',
        'problems',
        'contact',
        'basis',
        'comments'
    ];

    if ($client['is_customer'] === 'true') {
        $query = sprintf("SELECT uid, IF(accept_start_repair='true', date_start_repair, '') AS date_start_repair, %s FROM orders WHERE uid=? AND inn_customer=? LIMIT 1", implode(', ', $column_name));
    } else {
        $uid = explode('_', $uid);

        if (count($uid) < 2) Flight::halt(404, 'not found');
        else {
            $uid = $uid[1];
            $db = new DataBase();
            $result = $db->mysql_qw('SELECT id FROM orders WHERE id=?', $uid);
            if(!$result -> num_rows) Flight::halt(404, 'not found');
        }

        $query = sprintf("SELECT CONCAT(inn_customer, '_', id) AS uid, IF(accept_start_repair='true', date_start_repair, '') AS date_start_repair, %s FROM orders WHERE id=? LIMIT 1", implode(', ', $column_name));
    }

    try {
        $db = new DataBase();
        $data = $db->mysql_qw($query, $uid, $client['inn']);

        if ($data->num_rows) {
            $result = $data->fetch_assoc();
            $result['problems'] = unserialize($result['problems']);
            if (!is_array($result['problems'])) $result['problems'] = [];

            Flight::halt(200, json_encode($result));
        } else {
            Flight::halt(404, 'not found');
        }
    } catch (Exception $error) {
        sqlErrorHandler($error->getMessage());
    }
}
