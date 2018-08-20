<?php
    
    // --- Configure --- ;
    //define( 'HTTP_SERVER', "http://".$_SERVER[ "SERVER_NAME" ] ) ;
	define( 'HTTP_SERVER', "http://rockcrawlerapps.com/leak/" ) ;
    define( 'HTTP_CATALOG_SERVER', HTTP_SERVER."/" )  ;

    define( 'DIR_WS_INCLUDES', DIR_FS_DOCUMENT_ROOT . 'includes/' ) ;
    define( 'DIR_WS_FUNCTIONS', DIR_FS_DOCUMENT_ROOT . 'includes/functions/' ) ;

    define( 'SESSION_LIFETIME', 86400 ) ; // 24 hours

    define( 'DB_SERVER', 'localhost' ) ;
    define( 'DB_SERVER_USERNAME', 'tong' ) ;
    define( 'DB_SERVER_PASSWORD', '~}KHKebpk}u1' ) ;
    define( 'DB_DATABASE', 'hongjileak' ) ;

    define( 'USE_PCONNECT', 'false' ) ;
    define( 'STORE_SESSIONS', 'mysql' ) ;
    define( 'CHARSET','utf8' ) ;

    define( 'FILENAME_DEFAULT', 'index.php' ) ;

    define( 'SESSION_WRITE_DIRECTORY', DIR_WS_INCLUDES . 'cache/' ) ;

    // Password Min, Max Length
    define( 'USER_PASSWORD_MIN_LENGTH', 8 ) ;
    define( 'USER_PASSWORD_MAX_LENGTH', 30 ) ;
    define( 'SITE_TITLE', 'Leak' ) ;