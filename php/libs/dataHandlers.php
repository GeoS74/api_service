<?php
/**
 *Убрать крайние пробелы в строке или в элементах массива строчного типа. Любые другие элементы массива просто игнорируются
 *Работает с массивами любой сложности. Использует рекурсию
 *Примечание: т.к. аргумент передаётся по ссылке, то работе с массивами можно не использовать возвращаемое значение
 *  mixed trimer( mixed &$data )
 *  @param mixed &$data - строка или массив строк
 *  @return - строка без пробелов по краям или массив с элементами без пробелов по краям
 */
function trimer(&$data)
{
    if (is_string($data)) return trim($data);

    if (is_array($data)) {
        if (!function_exists('arr_trim')) //проверяет проинициализирована ли вложенная функция arr_trim(), если нет то объявляет её
        {
            function arr_trim(&$data)
            {
                foreach ($data as &$value) {
                    if (is_string($value)) $value = trim($value);

                    if (is_array($value)) arr_trim($value);
                }
            }
        }
        arr_trim($data); //вызов вложенной функции должен быть после объявления это функции
    }
    return $data;
}

/**
 *Преобразовать специальные символы в строке или в элементах массива строчного типа в HTML-сущности. Любые другие элементы массива просто игнорируются
 *Работает с массивами любой сложности. Использует рекурсию
 *Примечание: т.к. аргумент передаётся по ссылке, то работе с массивами можно не использовать возвращаемое значение
 *  mixed htmlspecialcharser( mixed &$data )
 *  @param mixed &$data - строка или массив строк
 *  @return - преобразованная строка или массив с преобразованными элементами
 */
function htmlspecialcharser(&$data)
{
    if (is_string($data)) return htmlspecialchars($data);

    if (is_array($data)) {
        if (!function_exists('arr_htmlspecialchars')) //проверяет проинициализирована ли вложенная функция arr_htmlspecialchars(), если нет то объявляет её
        {
            function arr_htmlspecialchars(&$data)
            {
                foreach ($data as &$value) {
                    if (is_string($value)) $value = htmlspecialchars($value);

                    if (is_array($value)) arr_htmlspecialchars($value);
                }
            }
        }
        arr_htmlspecialchars($data);
    }
    return $data;
}

/**
 *Преобразовать кириллицу к английской ракладке
 *  @param string $str
 *  @return - строка после транслитерации
 */
function translit($str)
{
    if (!is_string($str)) return $str;

    $str = trim($str);

    return strtr(
        $str,
        array(

            'а' => 'a',  'к' => 'k',  'ф' => 'f',  'А' => 'A',  'К' => 'K',  'Ф' => 'F',
            'б' => 'b',  'л' => 'l',  'х' => 'x',  'Б' => 'B',  'Л' => 'L',  'Х' => 'X',
            'в' => 'v',  'м' => 'm',  'ц' => 'c',  'В' => 'V',  'М' => 'M',  'Ц' => 'C',
            'г' => 'g',  'н' => 'n',  'ы' => 'y',  'Г' => 'G',  'Н' => 'N',  'Ы' => 'Y',
            'д' => 'd',  'о' => 'o',  'э' => 'e',  'Д' => 'D',  'О' => 'O',  'Э' => 'E',
            'е' => 'e',  'п' => 'p',             'Е' => 'E',  'П' => 'P',
            'ж' => 'zh', 'р' => 'r',             'Ж' => 'Zh', 'Р' => 'R',
            'з' => 'z',  'с' => 's',             'З' => 'Z',  'С' => 'S',
            'и' => 'i',  'т' => 't',             'И' => 'I',  'Т' => 'T',
            'й' => 'j',  'у' => 'u',             'Й' => 'J',  'У' => 'U',

            'ё' => 'yo', 'Ё' => 'Yo',
            'ц' => 'c',  'Ц' => 'C',
            'ч' => 'ch', 'Ч' => 'Ch',
            'ш' => 'sh', 'Ш' => 'Sh',
            'щ' => 'shh', 'Щ' => 'Shh',
            'ъ' => '',   'Ъ' => '',
            'ь' => '',   'Ь' => '',
            'ю' => 'yu', 'Ю' => 'Yu',
            'я' => 'ya', 'Я' => 'Ya',

            //символы, которые нужно избегать: 'пробел\\/:*?"<>|+.,%&!@\'#$~()[]{}'
            //числовые значения получены с помощью функции ord()
            //для имен таблиц БД знак '-' не допустим
            ' ' => '_',   '&' => '38',
            '\\' => '92', '!' => '33',
            '/' => '47',  '@' => '64',
            ':' => '58',  '\'' => '39',
            '*' => '42',  '#' => '35',
            '?' => '63',  '$' => '36',
            '"' => '34',  '~' => '126',
            '<' => '60',  '(' => '40',
            '>' => '62',  ')' => '41',
            '|' => '124', '{' => '123',
            '+' => '43',  '}' => '125',
            '[' => '91',
            ',' => '44',  ']' => '93',
            '%' => '37',  '-' => '45',
            '.' => '46',

            //добавил 2019-01-16 из-за ошибки, возникавшей при заливке файла (модуль: GParser)
            //ошибка возникала если символ '№' содержится в имени файла
            '№' => '226',
        )
    );
}

/**
 *Генерирует код EAN8
 *  @param integer $num
 *  @return - строка (8 символов) с кодом EAN8
 */
function getEAN8($num)
{
    if (mb_strlen($num) > 7) die('Ошибка: длина аргумента превышает 7 символов. (Файл: ' . __FILE__ . ', Функция: ' . __FUNCTION__ . ')');

    $arr = array_merge(array_fill(0, 7 - strlen($num), '0'), str_split($num));

    //получение контрольной цифры

    //1) сложить все цифры, стоящие на не четных местах (слева направо)
    $odd_digits = $arr[0] + $arr[2] + $arr[4] + $arr[6];

    //2) полученную сумму умножить на три
    $odd_digits *= 3;

    //3) сложить все цифры, стоящие на четных местах (слева направо)
    $even_digits = $arr[1] + $arr[3] + $arr[5];

    //4) сложить полученные цифры в пунктах 2 и 3
    $sum = $odd_digits + $even_digits;

    //5) отбросить десятки
    $sum = mb_substr($sum, -1);
    if ($sum == 0) $sum = 10;

    //6) из 10 вычесть число полученное в п.5 - это контрольная сумма
    $arr[] = (10 - $sum);

    return implode('', $arr);
}


/**
 *Генерирует код getEAN13
 *  @param integer $num
 *  @return - строка (13 символов) с кодом EAN13
 */
function getEAN13($num)
{
    if (mb_strlen($num) > 12) die('Ошибка: длина аргумента превышает 12 символов. (Файл: ' . __FILE__ . ', Функция: ' . __FUNCTION__ . ')');

    $arr = array_merge(array_fill(0, 12 - strlen($num), '0'), str_split($num));

    //получение контрольной цифры

    //1) сложить все цифры, стоящие на четных местах (слева направо)
    $even_digits = $arr[1] + $arr[3] + $arr[5] + $arr[7] + $arr[9] + $arr[11];

    //2) полученную сумму умножить на три
    $even_digits *= 3;

    //3) сложить все цифры, стоящие на не четных местах (слева направо)
    $odd_digits = $arr[0] + $arr[2] + $arr[4] + $arr[6] + $arr[8] + $arr[10];

    //4) сложить полученные цифры в пунктах 2 и 3
    $sum = $odd_digits + $even_digits;

    //5) отбросить десятки
    $sum = mb_substr($sum, -1);
    if ($sum == 0) $sum = 10;

    //6) из 10 вычесть число полученное в п.5 - это контрольная сумма
    $arr[] = (10 - $sum);

    return implode('', $arr);
}


function dump($mixed)
{
    echo '<pre>';
    print_r($mixed);
    echo '</pre>';
}
