<?php
require_once 'config.php';
require_once 'secret.php';
require_once 'vendor/autoload.php';
//https://flightphp.com/learn


//autoload class
spl_autoload_register(function ($class) {
    include_once './php/class/' . $class . '.php';
});



//page token editor
Flight::route('GET /token', function () {
    Flight::set('flight.views.path', './tpl');
    Flight::render('token');
});

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

Flight::route('POST /token', function () {

    if (!Flight::request()->data->inn) {
        Flight::halt(400, json_encode(["error" => "inn organization is empty"]));
    }
    if (!Flight::request()->data->organization) {
        Flight::halt(400, json_encode(["error" => "organization is empty"]));
    }

    $token = bin2hex(random_bytes(15));

    try {
        $db = new DataBase();
        $db->mysql_qw(
            "INSERT INTO access_tokens (organization, contacts, token) VALUES(?, ?, ?)",
            Flight::request()->data->organization,
            Flight::request()->data->contacts,
            $token
        );
        echo json_encode([
            "id" => $db->get_last_id(),
            "organization" => Flight::request()->data->organization,
            "contacts" => Flight::request()->data->contacts,
            "token" => $token
        ]);
    } catch (Exception $error) {
        Flight::halt(400, json_encode(["error" => $error->getMessage()]));
    }
});

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

//создать БД
Flight::route('GET /create/db', function () {
    try {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, "");
        $mysqli->set_charset(DB_CHARSET);
        $result = $mysqli->query("CREATE DATABASE " . DB_NAME);
        echo 'database created';
    } catch (Exception $error) {
        Flight::halt(400, json_encode(["error" => $error->getMessage()]));
    }
});
//создать таблицу токенов
Flight::route('GET /create/table/token', function () {
    try {
        $db = new DataBase();
        $db->mysql_qw("CREATE TABLE access_tokens (
            id SMALLINT NOT NULL AUTO_INCREMENT, 
            inn INT(12),
            organization VARCHAR(255),
            contacts VARCHAR(255),
            token VARCHAR(255),
        PRIMARY KEY(id))");
    } catch (Exception $error) {
        Flight::halt(400, json_encode(["error" => $error->getMessage()]));
    }
});






// Flight::route('GET /', function(){
//     Flight::set('flight.views.path', './tpl');
//     Flight::render('x');
// });

// Flight::route('GET /test', function(){
//     echo 'hello';
// });

// Flight::route('POST /test', function(){
//     $data = ["name" =>  Flight::request() -> data -> name];
//     echo json_encode($data);
// });


// Flight::route('POST /foo', function(){
//     // echo 'foo!';
//     Flight::halt(300, 'Be right back...');
//     echo Flight::request() -> data -> name;
// });

// Flight::route('POST /json', function(){
//     header('Access-Control-Allow-Origin: *');
//     header('Content-Type: text/plain');
//     echo Flight::request() -> data -> name;

//     // print_r( getallheaders() );
//     // $name = Flight::request() -> data -> name;
//     // echo Flight::json(array('name' => $name.' Hacker'));
//     // echo $_SERVER['HTTP_ORIGIN'];
//     // echo 'hello';

//     // echo Flight::request() -> getBody();

//     // print_r(Flight::request());
// });

Flight::map('notFound', function () {
    Flight::halt(404, 'not found');
});

Flight::start();
