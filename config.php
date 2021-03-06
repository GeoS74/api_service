<?php
define('DB_CHARSET', 'utf8'); //в функции set_charset нельзя передавать кодировку с '-' (utf-8). Почитать: https://www.php.net/manual/ru/mysqlinfo.concepts.charset.php
define('CHARSET', 'utf-8');
define('BASE', '/api');
define('DOMEN', 'http://localhost');

//session
ini_set('session.use_cookies', true);
ini_set('session.use_only_cookies', true);
ini_set('session.use_strict_mode', true);
ini_set('session.cookie_httponly', true);
ini_set('session.use_trans_sid', false);

date_default_timezone_set('Europe/Moscow'); //Устанавливает временную зону по умолчанию для всех функций даты/времени в скрипте
//часовой пояс для БД устанавливается в скрипте 
//$mysqli->query("SET GLOBAL time_zone = '+3:00'")

//charset
ini_set('default_charset', CHARSET);
mb_internal_encoding( CHARSET );

//token
define('MAX_LIMIT_REQUESTS', 20000);
?>