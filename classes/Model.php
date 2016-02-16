<?php

class Model {

    function __construct($vars=array()) {
        $this->updateFields($vars);
    }


    # create and populate object, save to db
    public static function create($vars) {
        $ClassName = get_called_class();
        $obj = new $ClassName($vars);
        return $obj->insertAsNew();
    }

    public static function update($vars) {
        $ClassName = get_called_class();
        $obj = new $ClassName($vars);
        return $obj->updateExisting();
    }

    public static function get($vars=array()) {
        $idField = self::getIdFieldName();

        if (is_string($vars)) {
            $sql = $vars;
        }
        else {
            # syntactic sugar: just pass num to fetch by ID
            if (is_int($vars)) {
                $vars = array( $idField => $vars );
            }

            $sql = self::buildSelectSql($vars);
        }

        return Model::query_fetch($sql);
    }

    public function updateFields($vars) {
        foreach ($vars as $key => $val) {
            $this->{$key} = $val;
        }
    }


    public function insertAsNew() {
        $table_name = self::table_name();

        $objVars = get_object_vars($this);
        if (!count($objVars)) { #todo is this ultimately necessary?
            trigger_error("Model::create needs at least one key-value pair", E_USER_ERROR);
        }
        list($varNameList, $varValList) = Db::sqlFieldsAndValsFromArray($objVars);

        $sql = "
            insert into $table_name ($varNameList)
            values ($varValList);
        ";

        $db = Db::conn();
        $result = mysqli_query($db, $sql);

        if ($result) {
            $this->setId(mysqli_insert_id($db)); #todo fill in default fields too?
            return $this;
        }
        else {
            Db::error("Model::create could not create object.", $sql);
        }

    }

    public static function buildSelectSql($vars) {
        $table_name = self::table_name();
        $sql = "
            select * from $table_name
        ";

        # add where clauses
        $whereOrAnd = 'where';
        foreach ($vars as $key => $val) {
            $val = Db::sqlLiteral($val);
            $sql .= "\n$whereOrAnd $key = $val";
            $whereOrAnd = 'and';
        }

        $sql .= ";";
        return $sql;
    }


    # save changes of existing obj/row to db
    public function updateExisting() {
        $table_name = self::table_name();

        $objVars = get_object_vars($this);
        list($varNameList, $varValList) = Db::sqlFieldsAndValsFromArray($objVars);

        { # build sql
            $sql = "
                update $table_name set
            ";

            $comma = false;
            foreach ($objVars as $key => $val) {
                if ($comma) $sql .= ",";
                $val = Db::sqlLiteral($val);
                $sql .= "\n$key = $val";
                $comma = true;
            }
            $idField = self::getIdFieldName();
            $id = $this->getId();
            $sql .= "
                where $idField = $id
            ";
            $sql .= ';';
        }

        $db = Db::conn();
        $result = mysqli_query($db, $sql);

        if ($result) {
            return $this;
        }
        else {
            Db::error("Model::create could not create object.", $sql);
        }

    }


    private function setId($val) {
        $ClassName = get_called_class();
        $idField = $ClassName::getIdFieldName();
        $this->{$idField} = $val;
    }

    private function getId() {
        $idField = self::getIdFieldName();
        return $this->{$idField};
    }

    private static function getIdFieldName() {
        return self::table_name() . '_id';
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

