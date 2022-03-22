<?php
function accessControl() {
    checkRequestScheme();
    checkHeaderAuth('Authorization');
    checkToken( getToken() );
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
    $result = $db -> mysql_qw('SELECT token FROM access_tokens WHERE token=?', $token);

    if(!$result -> num_rows) {
        Flight::halt(403, 'Forbidden');
    }
}
?>