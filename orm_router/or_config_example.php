<?php
{ # orm_router specific vars
    define('URI_PREFIX', '/orm_router');
    $ControllerClass = 'ExampleController';
}

{ # new config style
    $db_user = '<your-db-user-here>';
    $db_password = '<your-password-here>';
    $db_name = '<your-database-name-here>';
    $db_type = '<mysql-pgsql-sqlite>';
    #$db_port = '<port>';
}

{ # postgres-specific options
    # $search_path = 'schema1, schema2, etc';
}
?>
