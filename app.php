<?php
// require_once 'vendor/mikecao/flight/flight/autoload.php';
// require_once 'vendor/mikecao/flight/flight/Flight.php';
require_once 'vendor/autoload.php';
require_once 'php/controllers/authorization.php';
require_once 'php/controllers/orders.php';
//https://flightphp.com/learn


//autoload class
spl_autoload_register(function ($class) {
    include_once './php/class/' . $class . '.php';
});

//set default path for template files
Flight::set('flight.views.path', './client/tpl');
define('PREFIX', '/service');

Flight::route('GET /server', function () {
    // accessControl();
    Flight::render('server');
});



 



//~~~~~~~~~~~~~~~~~~~~~~~main api~~~~~~~~~~~~~~~~~~~~~~~\\
//направление заявки на ремонт
Flight::route('POST '.PREFIX.'/order', function () {
    accessControl();
    if (Flight::get('client')['is_customer'] === 'false') {
        Flight::halt(403, 'Forbidden');
    }

    $body = (array)json_decode(Flight::request()->getBody());
    $body['inn_customer'] = Flight::get('client')['inn'];

    checkCredentialsOrder($body);
    checkDublicateOrder($body);
    addOrder($body);
});
//получить список заявок на ремонт
Flight::route('GET '.PREFIX.'/orders', function () {
    accessControl();
    if (Flight::request()->query['only_new'] === 'true') {
        Flight::set('only_new', true);
    }
    getOrders();
});
//получить заявку по uid
Flight::route('GET '.PREFIX.'/order/@uid', function ($uid) {
    accessControl();
    Flight::set('uid', $uid);
    getOrder();
});
//подтверждение начала ремонта
Flight::route('POST '.PREFIX.'/order/@uid', function ($uid) {
    accessControl();
    if (Flight::get('client')['is_customer'] === 'true') {
        Flight::halt(403, 'Forbidden');
    }

    Flight::set('uid', $uid);
    $body = (array)json_decode(Flight::request()->getBody());

    updStartRepair($body);
});




//~~~~~~~~~~~~~~~~~~~~~~~docs api~~~~~~~~~~~~~~~~~~~~~~~\\
Flight::route('GET '.PREFIX.'/docs(/@page)', function ($page) {
    if (!$page) $page = 'main';
    Flight::render('./docs/' . $page);
});


//~~~~~~~~~~~~~~~~~~~~~~~generate token~~~~~~~~~~~~~~~~~~~~~~~\\
//page token editor
Flight::route('GET /token', function () {
    Flight::render('token');
});
//get all tokens
Flight::route('GET /tokens', function () {
    try {
        $db = new DataBase();
        $result = $db->mysql_qw("SELECT * FROM access_tokens ORDER BY organization DESC");
        $data = [];
        if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) $data[] = $row;
        }
        echo json_encode($data);
    } catch (Exception $error) {
        Flight::halt(400, json_encode(["error" => $error->getMessage()]));
    }
});
//add token
Flight::route('POST /token', function () {

    if (!Flight::request()->data->inn) {
        Flight::halt(400, json_encode(["error" => "inn organization is empty"]));
    }
    if (!Flight::request()->data->organization) {
        Flight::halt(400, json_encode(["error" => "organization is empty"]));
    }

    $token = bin2hex(random_bytes(25));

    try {
        $db = new DataBase();
        $db->mysql_qw(
            "INSERT INTO access_tokens (inn, organization, contacts, token, is_customer) VALUES(?, ?, ?, ?, ?)",
            Flight::request()->data->inn,
            Flight::request()->data->organization,
            Flight::request()->data->contacts,
            $token,
            Flight::request()->data->is_customer ? 'true' : 'false'
        );
        echo json_encode([
            "id" => $db->get_last_id(),
            "inn" => Flight::request()->data->inn,
            "organization" => Flight::request()->data->organization,
            "contacts" => Flight::request()->data->contacts,
            "token" => $token,
            "is_customer" => Flight::request()->data->is_customer ? 'true' : 'false'
        ]);
    } catch (Exception $error) {
        Flight::halt(400, json_encode(["error" => $error->getMessage()]));
    }
});
//del token
Flight::route('DELETE /token/@id', function ($id) {
    // echo json_encode( Flight::request()->query -> id_token );
    try {
        $db = new DataBase();
        $db->mysql_qw("DELETE FROM access_tokens WHERE id=?", $id);
        echo json_encode(["id" => $id]);
    } catch (Exception $error) {
        Flight::halt(400, json_encode(["error" => $error->getMessage()]));
    }
});

//~~~~~~~~~~~~~~~~~~~~~~~init~~~~~~~~~~~~~~~~~~~~~~~\\
//create database
Flight::route('GET /create/db', function () {
    try {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, "");
        $mysqli->set_charset(DB_CHARSET);
        $mysqli->query("CREATE DATABASE " . DB_NAME);
        $mysqli->query("SET GLOBAL time_zone = '+3:00'");
        echo 'database created';
    } catch (Exception $error) {
        Flight::halt(400, json_encode(["error" => $error->getMessage()]));
    }
});
//create table for tokens
Flight::route('GET /create/table/token', function () {
    try {
        $db = new DataBase();
        $db->mysql_qw("CREATE TABLE access_tokens (
            id SMALLINT NOT NULL AUTO_INCREMENT, 
            inn VARCHAR(12),
            organization VARCHAR(255),
            contacts VARCHAR(255),
            token VARCHAR(53),
            last_visit TIMESTAMP NOT NULL DEFAULT now(),
            count_visit INTEGER NOT NULL DEFAULT 0,
            is_customer ENUM('false', 'true') NOT NULL DEFAULT 'false',
        PRIMARY KEY(id))");

        $db->mysql_qw('CREATE INDEX inn ON access_tokens(inn)');
        $db->mysql_qw('CREATE UNIQUE INDEX token ON access_tokens(token)');

        echo 'table for tokens created';
    } catch (Exception $error) {
        Flight::halt(400, json_encode(["error" => $error->getMessage()]));
    }
});
//create table for orders
Flight::route('GET /create/table/orders', function () {
    try {
        $db = new DataBase();
        $db->mysql_qw("CREATE TABLE orders (
            id SMALLINT NOT NULL AUTO_INCREMENT,
            date_create TIMESTAMP NOT NULL DEFAULT now(),
            inn_customer VARCHAR(12) NOT NULL,
            accept_start_repair ENUM('false', 'true') NOT NULL DEFAULT 'false',
            date_start_repair TIMESTAMP NOT NULL DEFAULT now(),

            uid VARCHAR(50) NOT NULL,
            order_num VARCHAR(100),
            garage_num VARCHAR(25),
            invent_num VARCHAR(25),
            reg_num VARCHAR(25),
            vin_code VARCHAR(17),
            car_model VARCHAR(255),
             
            car_type VARCHAR(255),
            year_issue VARCHAR(4),
            mileage VARCHAR(10),
            problems TEXT,
            contact VARCHAR(255),
            basis VARCHAR(255),
            comments VARCHAR(255),
        PRIMARY KEY(id))");

        $db->mysql_qw('CREATE INDEX inn_customer ON orders(inn_customer)');
        $db->mysql_qw('CREATE INDEX uid ON orders(uid)');

        echo 'table for orders created';
    } catch (Exception $error) {
        Flight::halt(400, json_encode(["error" => $error->getMessage()]));
    }
});


Flight::map('notFound', function () {
    Flight::halt(404, 'not found');
});



//~~~~~~~~~~~~~~~~~~~~~~~test~~~~~~~~~~~~~~~~~~~~~~~\\
Flight::route('GET /test', function () {
    Flight::render('testAPIrequest');
});


Flight::start();
?>