<?php
    
    // --- Database --- ;
    
    function teb_query( $sql, $column = "" )
    {
        $object_query = tep_db_query( $sql ) ;

        if( tep_db_num_rows( $object_query ) == 0 )
        {
            return "" ;
        }

        $rc = tep_db_fetch_array( $object_query ) ;
        
        if( $column == "" )
        {
            return $rc ;
        }
        
        return $rc[ $column ] ;
    }

    function teb_multi_query( $table_name, $where = "1=1", $query = "*", $order_by = "" )
    {
        $sql = "select " . $query . " from " . $table_name ;
        
        if( is_array( $where ) )
        {
            $sql .= ( " where 1=1" ) ;
            
            while( list( $columns, $value ) = each( $where ) )
            {
                $sql .= ( " and `" . $columns . "`='" . tep_db_input( $value ) . "'" ) ;
            }
        }
        else
        {
            $sql .= ( " where " . $where ) ;
        }
        
        if( $order_by != '' )
        {
            $sql .= " order by " . $order_by ;
        }

        return tep_db_query( $sql ) ;
    }

    function teb_delete_query( $table_name, $where = "1=1" )
    {
        $sql = "delete from " . $table_name ;

        if( is_array( $where ) )
        {
            $sql .= ( " where 1=1" ) ;
            
            while( list( $columns, $value ) = each( $where ) )
            {
                $sql .= (" and `" . $columns . "`='" . tep_db_input( $value ) . "'" ) ;
            }
        }
        else
        {
            $sql .= ( " where " . $where ) ;
        }

        return tep_db_query( $sql ) ;
    }

    function teb_one_query( $table_name, $where = "1=1", $query = "*", $order_by = "" )
    {
        $query = teb_multi_query( $table_name, $where, $query, $order_by ) ;
        
        if( tep_db_num_rows( $query ) == 0 )
        {
            return "" ;
        }

        $rc = tep_db_fetch_array( $query ) ;
        return $rc ;
    }
    
    function tep_db_connect( $server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link' )
    {
        global $$link ;

        if( USE_PCONNECT == 'true' )
        {
            $$link = mysql_pconnect( $server, $username, $password ) ;
        }
        else
        {
            $$link = mysql_connect( $server, $username, $password ) ;
        }

        if( $$link )
        {
            mysql_select_db( $database ) ;
        }

        return $$link ;
    }

    function tep_db_close( $link = 'db_link' )
    {
        global $$link ;
        return mysql_close( $$link ) ;
    }

    function tep_db_error( $query, $errno, $error )
    {
        die( $query ) ;
    }

    function tep_db_query( $query, $link = 'db_link' )
    {
        global $$link ;

        $result = mysql_query( $query, $$link ) or tep_db_error( $query, mysql_errno(), mysql_error() ) ;
        return $result ;
    }

    function tep_db_perform( $table, $data, $action = 'insert', $parameters = '', $link = 'db_link' )
    {
        reset( $data ) ;
        
        if( $action == 'insert' )
        {
            $query = 'insert into ' . $table . ' (' ;
            
            while( list( $columns, ) = each( $data ) )
            {
                $query .= '`' . $columns . '`, ' ;
            }
            
            $query = substr( $query, 0, -2 ) . ') values (' ;
            reset( $data ) ;
            
            while( list( , $value ) = each( $data ) )
            {
                switch( ( string )$value )
                {
                    case 'now()' :
                        $query .= 'now(), ' ;
                        break ;
                    
                    case 'null' :
                        $query .= 'null, ' ;
                        break ;
                        
                    default :
                        $query .= '\'' . tep_db_input( $value ) . '\', ' ;
                        break ;
                }
            }
            
            $query = substr( $query, 0, -2 ) . ')' ;
        }
        elseif( $action == 'update' )
        {
            $query = 'update ' . $table . ' set ' ;

            while( list( $columns, $value ) = each( $data ) )
            {
                switch( ( string )$value )
                {
                    case 'now()' :
                        $query .= '`' . $columns . '` = now(), ' ;
                        break ;
                    
                    case 'null' :
                        $query .= '`' . $columns .= '` = null, ' ;
                        break ;
                    
                    default :
                        $query .= '`' . $columns . '` = \'' . tep_db_input( $value ) . '\', ' ;
                        break ;
                }
            }
            
            $query = substr( $query, 0, -2 ) . ' where ' . $parameters ;
        }

        return tep_db_query( $query, $link ) ;
    }

    function tep_db_fetch_array( $db_query )
    {
        return mysql_fetch_array( $db_query, MYSQL_ASSOC ) ;
    }

    function tep_db_num_rows( $db_query )
    {
        return mysql_num_rows( $db_query ) ;
    }

    function tep_db_data_seek( $db_query, $row_number )
    {
        return mysql_data_seek( $db_query, $row_number ) ;
    }

    function tep_db_insert_id( $link = 'db_link' )
    {
        global $$link ;
        return mysql_insert_id( $$link ) ;
    }

    function tep_db_free_result( $db_query )
    {
        return mysql_free_result( $db_query ) ;
    }

    function tep_db_fetch_fields( $db_query )
    {
        return mysql_fetch_field( $db_query ) ;
    }

    function tep_db_output( $string )
    {
        return htmlspecialchars( $string ) ;
    }

    function tep_db_input($string, $link = 'db_link')
    {
        global $$link ;

        if( function_exists( 'mysql_real_escape_string' ) )
        {
            return mysql_real_escape_string( $string, $$link ) ;
        }
        elseif( function_exists( 'mysql_escape_string' ) )
        {
            return mysql_escape_string( $string ) ;
        }

        return addslashes( $string ) ;
    }

    function tep_db_prepare_input( $string )
    {
        if( is_string( $string ) )
        {
            return trim( tep_sanitize_string( stripslashes( $string ) ) ) ;
        }
        elseif( is_array( $string ) )
        {
            reset( $string ) ;
            
            while( list( $key, $value ) = each( $string ) )
            {
                $string[ $key ] = tep_db_prepare_input( $value ) ;
            }
        }

        return $string ;
    }

    function db_result_array( $result, $key_column = null )
    {
        for( $array = array() ; $row = mysql_fetch_assoc( $result ) ; isset( $row[ $key_column ] ) ? $array[ $row[ $key_column ] ] = $row : $array[] = $row ) ;
        return $array ;
    }
?>