<?php
    define( "SITE_SUB_URL", "" ) ;
    define( 'DIR_FS_DOCUMENT_ROOT', dirname( dirname( __FILE__ ) )."/" ) ;

    require( DIR_FS_DOCUMENT_ROOT.'/includes/configure.php' ) ;
    require( DIR_WS_FUNCTIONS . 'compatibility.php' ) ;
    require( DIR_WS_FUNCTIONS . 'database.php' ) ;
    require( DIR_WS_FUNCTIONS . 'validations.php' ) ;
    require( DIR_WS_FUNCTIONS . 'general.php' ) ;
    require( DIR_WS_FUNCTIONS . 'html_output.php' ) ;
    require( DIR_WS_FUNCTIONS . 'sessions.php' ) ;
?>