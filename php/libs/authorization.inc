<?php
function accessControl() {
    checkRequestScheme();
    checkHeaderAuth('Authorization');
    return checkToken( getToken() );
}

function checkRequestScheme() {
    if($_SERVER['REQUEST_SCHEME'] !== 'https') {
        Flight::halt(406, 'Not Acceptable');
    }
}

function checkHeaderAuth($header) {
    if(!array_key_exists($header, getallheaders())) {
        Flight::halt(401, 'Unauthorized');
    }
}

function getToken() {
    preg_match('/Basic ([\w\d]+)/', getallheaders()['Authorization'], $matches);

    if(count($matches) < 2) {
        Flight::halt(401, 'Unauthorized');
    }
    else {
        return $token = $matches[1];
    }
}

function checkToken($token) {
    $db = new DataBase();
    $result = $db -> mysql_qw('SELECT token, last_visit, count_visit, inn, is_customer FROM access_tokens WHERE token=?', $token);

    if(!$result -> num_rows) {
        Flight::halt(403, 'Forbidden');
    }
    else {
        $token_data = $result -> fetch_assoc();
        checkCountVisit($token_data);
        return [
            'inn' => $token_data['inn'],
            'is_customer' => $token_data['is_customer'],
        ];
    }
}

function checkCountVisit($data) {
    //если последний визит был более суток назад - сбросить счётчик
    //иначе проверить кол-во обращений и, если нет превышения лимита, увеличить его на 1
    $db = new DataBase();
    if( (time()-strtotime($data['last_visit'])) > 86400 ) {
        $db -> mysql_qw("UPDATE access_tokens SET count_visit=DEFAULT, last_visit=DEFAULT WHERE token=?", $data['token']);
    }
    else {
        if( ++$data['count_visit'] > MAX_LIMIT_REQUESTS ){
            Flight::halt(403, 'Exceeding the limit requests');
        }
        else $db -> mysql_qw("UPDATE access_tokens SET count_visit=?, last_visit=DEFAULT WHERE token=?", $data['count_visit'], $data['token']);
    }
}
?>