<?php

class Model {

	function __construct($vars=array()) {
        $this->updateFields($vars);
	}

    public function updateFields($vars) {
		foreach ($vars as $key => $val) {
			$this->{$key} = $val;
		}
    }

	public static function create($vars) {
		if (!count($vars)) trigger_error("Model::create needs at least one key-value pair", E_USER_ERROR);

		$db = Db::conn();

		# populate object
		$ClassName = get_called_class();
		$table_name = ClassName2table_name($ClassName);
		$obj = new $ClassName($vars);

		list($varNameList, $varValList) = Db::sqlFieldsAndValsFromArray(get_object_vars($obj));

		$sql = "
			insert into $table_name ($varNameList)
			values ($varValList);
		";
		$result = mysqli_query($db, $sql);

		if ($result) {
            $obj->setId(mysqli_insert_id($db)); #todo fill in default fields too?
			return $obj;
		}
		else {
            Db::error("Model::create could not create object.", $sql);
		}
	}

	public static function get($vars=array()) {
        $idField = self::getIdFieldName();

        { # syntactic sugar
            /*if (is_string($vars)) {
                $vars = array( 'where' => $vars );
            }*/
            if (is_int($vars)) {
                $vars = array( $idField => $vars );
            }
        }

        { # build sql
            $table_name = self::table_name();
            $sql = "
                select * from $table_name
            ";

            # add where clauses
            $whereOrAnd = 'where';
            /* if (isset($vars['where'])) {
                $sql .= "\n$whereOrAnd $vars[where]";
                $whereOrAnd = 'and';
            } */
            foreach ($vars as $key => $val) {
                $val = Db::sqlLiteral($val);
                $sql .= "\n$whereOrAnd $key = $val";
                $whereOrAnd = 'and';
            }

            $sql .= ";";
        }

		return Model::query_fetch($sql);
	}



    public function insertAsNew() {
    }


    private function setId($val) {
        $ClassName = get_called_class();
        $idField = $ClassName::getIdFieldName();
        $this->{$idField} = $val;
    }

    private static function getIdFieldName() {
        return self::table_name() . '_id';

        /* #todo still need get_called_class??
        $ClassName = get_called_class();
        $table_name = ClassName2table_name($ClassName);
        return $table_name.'_id';
        */
    }

    private static function table_name() {
        $ClassName = get_called_class();
        return ClassName2table_name($ClassName);
    }

	private static function mysqli_fetch_all($result) {
        $ClassName = get_called_class();
		$rows = array();
		while ($row = mysqli_fetch_object($result, $ClassName)) {
			$rows[] = $row;
		}
		return $rows;
	}

	private static function query_fetch($sql) {
		$db = Db::conn();
        $result = mysqli_query($db, $sql);
        $rows = Model::mysqli_fetch_all($result);
        return $rows;
	}

}

