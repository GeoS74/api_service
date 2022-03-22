<?
/*Класс DataBase
*
*Описание:
*	Класс DataBase содержит методы для работы с БД
*
*Интерфейс:
*	mysql_qw() - функция обращается к БД
*	getLastId - возвращает последнее значение id записи в БД при выполнении INSERT-a. При выполнении других запросов значение - 0
*
*	mysql_qw - функция обращается к БД
*	mysql_last_id - возвращает последнее значение id записи в БД при выполнении INSERT-a. При выполнении других запросов значение - 0
*/
class DataBase
{
	private $last_id = 0;
	private $mysqli = null;

	function __construct()
	{
		$this -> mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$this -> mysqli -> set_charset(DB_CHARSET);
	}

	public function mysql_qw()
	{
        $params = $this -> mysql_make_qw(func_get_args());
		$mysql_result = $this -> mysqli -> query($params);
        @$this -> last_id = $this -> mysqli -> insert_id;
		return $mysql_result; 
	}

    public function get_last_id()
    {
        return $this -> last_id;
    }

	/*формирует строку sql-запроса
	*
	*Описание:
	*		string mysql_make_qw( array $args )
	*		
	*		экранирует спец. символы и возвращает строку sql-запроса
	*
	*Список аргументов:
	*		args - массив с данными
	*			   Первый элемент массива всегда шаблон с плейсхолдерами. 
	*			   Второй - либо перечень аргументов, либо массив аргументов.
	*
	*Примечание:
	*		если плейсхолдеров больше чем передаваемых аргументов, аргументы добиваются "Unknow_placeholder_n", где n-номер по порядку неизвестного аргумента
	*/
	private function mysql_make_qw($args)
	{
		$tmpl =& $args[0]; //ссылка на шаблон
    
		$tmpl = str_replace('%', '%%', $tmpl); //если в шаблоне используется спец. символ %, то он удваивается
		$tmpl = str_replace('?', '%s', $tmpl); //знаки ? заменяются спец. символами %s

		$argsList = @is_array($args[1]) ? $args[1] : array_slice($args, 1); //проверка второго аргумента на массив

		$argsList = array_values($argsList); //если ключи строковые, меняет их на числовые

		foreach($argsList as $i => $v) //экранирует спец. символы и перезаписывает массив $args. Первый элемент это шаблон
		{
			$args[$i + 1] = "'".$this -> mysqli -> real_escape_string($v)."'";
		}

		for ($i = $c = count($args) - 1; $i < $c + 20; $i++) //добивает недостающие аргументы
		{
			$args[$i + 1] = "Unknow_placeholder_" . $i;
		}
		// print_r( call_user_func_array( "sprintf", $args )."\n\n" );
		return call_user_func_array( "sprintf", $args );
	}
}
?>