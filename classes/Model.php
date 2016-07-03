<?php

class Model {

    function __construct($vars=array()) {
        $this->updateFields($vars);
    }


    # create and populate object, save to db
    public static function create($vars, $ClassName=null) {
        $ClassName = ($ClassName ? $ClassName
                                 : get_called_class());
        if (class_exists($ClassName)) {
            $obj = new $ClassName($vars);
            return $obj->insertAsNew();
        }
        else {
            $obj = new self($vars);
            return $obj->insertAsNew(true, $ClassName);
        }
    }

    public static function update($vars, $ClassName=null) {
        $ClassName = ($ClassName ? $ClassName
                                 : get_called_class());
        $id = $ClassName::arrGetId($vars);
        $obj = $ClassName::get1($id); 
        $obj->updateFields($vars);
        return $obj->updateExisting();
    }

    public static function get($varsOrSql=array(), $ClassName=null, $only1=false) {
        $sql = self::resolveSelectSql($varsOrSql, $ClassName);
        return self::query_fetch($sql, $ClassName, $only1);
    }

    public static function view($varsOrSql=array(), $ClassName=null) {
        $sql = self::resolveSelectSql($varsOrSql, $ClassName);
        return self::query_view($sql, $ClassName);
    }

    public static function get1($vars=array(), $ClassName=null) {
        return self::get($vars, $ClassName, true);
    }

    public function save($ClassName=null) {
        if ($this->getId()) {
            return $this->updateExisting($ClassName);
        }
        else {
            return $this->insertAsNew(true, $ClassName);
        }
    }

    # implementation

    public function updateFields($vars) {
        foreach ($vars as $key => $val) {
            $this->{$key} = $val;
        }
    }

    public static function resolveSelectSql($varsOrSql, $ClassName=null) {
        $seemsLikeSql = (is_string($varsOrSql) && !is_numeric($varsOrSql));
        if ($seemsLikeSql) {
            return $varsOrSql;
        }
        else {
            # syntactic sugar: just pass num to fetch by ID
            if (is_numeric($varsOrSql)) {
                $idField = self::getIdFieldName();
                $id = (int)$varsOrSql;
                $vars = array( $idField => $id );
            }
            else {
                $vars = $varsOrSql;
            }

            return self::buildSelectSql($vars, $ClassName);
        }
    }


    public function insertAsNew($fetch_full_obj=true, $ClassName=null) {
        $ClassName = ($ClassName ? $ClassName
                                 : get_called_class());
        $table_name = self::table_name($ClassName);

        $objVars = get_object_vars($this);
        $result = Db::insertRow($table_name, $objVars);

        if ($result) {
            $idField = (class_exists($ClassName)
                            ? $ClassName::getIdFieldName()
                            : self::getIdFieldName($ClassName));

            $sequenceName = Db::sequenceName($table_name, $idField);
            $lastInsertId = $db->lastInsertId($sequenceName);

            $this->setId($lastInsertId); #todo fill in default fields too?

            if ($fetch_full_obj) {
                #todo do we need class_exists check here?
                $vars = array(
                    $this->getIdFieldName() => $this->getId()
                );
                $obj = self::get1($vars, $ClassName);
                return $obj;
            }
            else {
                return $this;
            }
        }
        else {
            Db::error("Model::create could not create object.", $sql);
        }
    }

    # save changes of existing obj/row to db
    public function updateExisting($ClassName=null) {
        $table_name = self::table_name($ClassName);

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
        $result = $db->query($sql);

        if ($result) {
            return $this;
        }
        else {
            Db::error("Model::create could not create object.", $sql);
        }

    }

    public static function buildSelectSql($vars, $ClassName=null) {
        $table_name = self::table_name($ClassName);
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




    private function setId($val, $ClassName=null) {
        $ClassName = ($ClassName ? $ClassName
                                 : get_called_class());
        $idField = $ClassName::getIdFieldName();
        $this->{$idField} = $val;
    }

    private function getId() {
        $idField = self::getIdFieldName();
        return $this->{$idField};
    }

    private static function arrGetId($arr) {
        $idField = self::getIdFieldName();
        return $arr[$idField];
    }

    private static function getIdFieldName($ClassName=null) {
        return 'iid';
        /* #todo make flexible
        return self::table_name() . '_id';
        */
    }

    public static function table_name($ClassName=null) {
        $ClassName = ($ClassName ? $ClassName
                                 : get_called_class());
        return ClassName2table_name($ClassName);
    }

    public static function ClassName($table_name) {
        return table_name2ClassName($table_name);
    }

    private static function fetch_all($result, $ClassName=null) {
        if ($result) {
            $ClassName = ($ClassName ? $ClassName
                                     : get_called_class());
            $rows = array();
            $db = Db::conn();
            while ($row = self::fetch1($result, $ClassName)) {
                $rows[] = $row;
            }
            return $rows;
        }
        else {
            $sql = ''; #todo how best to show this error?
            Db::error("fetch_all: result was false", $sql);
        }
    }

    private static function fetch1($result, $ClassName=null) {
        if ($result) {
            $ClassName = ($ClassName ? $ClassName
                                     : get_called_class());
            return (class_exists($ClassName)
                        ? $result->fetchObject($ClassName)
                        : $result->fetch(PDO::FETCH_ASSOC));
        }
        else {
            $sql = ''; #todo how best to show this error?
            Db::error("fetch1: result was false", $sql);
        }
    }

    private static function query_fetch($sql, $ClassName=null, $only1=false) {
        $db = Db::conn();
        $result = $db->query($sql);
        if ($only1) {
            return self::fetch1($result, $ClassName);
        }
        else {
            return self::fetch_all($result, $ClassName);
        }
    }

    private static function query_view($sql, $ClassName=null) {
        $vars = requestVars();
        $query_string = http_build_query(array(
            'sql' => $sql,
        ));
        $db_viewer_url = "/db_viewer/db_viewer.php?$query_string";
        header("302 Temporary");
        header("Location: $db_viewer_url");
    }

    public static function log($msg) {
        log_msg($msg);
    }


    public static function error($msg) {
        return json_error($msg);
        #die($msg);
        #trigger_error($msg, E_USER_ERROR);
    }

    public function get_public_vars() {
        # subclasses don't have to share all their vars
        # but by default they do
        return get_object_vars($this);
    }
}

