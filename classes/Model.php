<?php

class Model {

	function __construct($vars) {
        self::updateFields($vars);
	}

    public static function updateFields($vars) {
		foreach ($vars as $key => $val) {
			$this->{$key} = $val;
		}
    }

	public static function create($vars) {
		if (!count($vars)) trigger_error("Model::create needs at least one key-value pair", E_USER_ERROR);

		$db = $GLOBALS['db'];
		if (!$db) trigger_error('problem connecting to database', E_USER_ERROR);

		# populate object
		$ClassName = get_called_class();
		$table_name = ClassName2table_name($ClassName);
		$obj = new $ClassName($vars);

		list($varNameList, $varValList) =
			Model::sqlFieldsAndValsFromArray($vars);

		$sql = "
			insert into $table_name ($varNameList)
			values ($varValList);
		";
		$result = mysqli_query($db, $sql);

		if ($result) {
			#todo return object
			return $result;
		}
		else {
			trigger_error("Model::create could not create object", E_USER_ERROR);
		}
	}

	public static function get($where=NULL) {
		$table_name = "company"; #todo
		$sql = "
			select * from $table_name
		";
		if ($where) {
			$sql .= "where $where";
		}
		$sql .= ";";

		return Model::query_fetch($sql);
	}



	private static function sqlLiteral($val) {
		if (is_string($val)) {
			$db = $GLOBALS['db'];
			$val = mysqli_real_escape_string($db, $val);
			return "'$val'";
		}
		elseif ($val === NULL) { return "NULL"; }
		elseif ($val === true) { return 1; }
		elseif ($val === false) { return 0; }
		else { return $val; }
	}

	private static function sqlFieldsAndValsFromArray($vars) {
		$keys = array_keys($vars);
		$varNameList = implode(', ', $keys);

		$varValLiterals = array();
		foreach ($keys as $key) {
			$val = $vars[$key];
			$varValLiterals[] = Model::sqlLiteral($val);
		}

		$varValList = implode(', ', $varValLiterals);
		return array($varNameList, $varValList);
	}

	private static function mysqli_fetch_all($result) {
		$rows = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$rows[] = $row;
		}
		return $rows;
	}

	private static function query_fetch($sql) {
		$db = $GLOBALS['db'];
		if ($db) {
			$result = mysqli_query($db, $sql);
			$rows = Model::mysqli_fetch_all($result);
			return $rows;
		}
		else {
			trigger_error("problem connecting to DB");
			die("problem connecting to DB");
		}
	}

}

