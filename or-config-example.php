<?php
# UNDER CONSTRUCTION - transitioning from consts to vars
# bear with me! :)
#   ~ 2016-06-13 RM

{ # old config style
    define('DB_HOST', '<your-host-here>');

    define('DB_USER', '<your-db-user-here>');
    define('DB_PASSWORD', '<your-password-here>');
    define('DB_NAME', '<your-database-name-here>');
    define('DB_TYPE', '<mysql-pgsql-sqlite>');

	define('URI_PREFIX', '/ormrouter');
}

{ # new config style
    $db_user = '<your-db-user-here>';
    $db_password = '<your-password-here>';
    $db_name = '<your-database-name-here>';
    $db_type = '<mysql-pgsql-sqlite>';
    #$db_port = '<port>';

    # not used yet
    #$uri_prefix = '/ormrouter';
}

{ # postgres-specific options
    # $search_path = 'schema1, schema2, etc';
}

{ # cosmetic / UI options
    $background = 'dark'; # vs 'light', the default
}
?>
