<?php
	// --- Favorites --- ;

	/*
	 * @api			favoritevideo
	 * @paramter	account
	 * @paramter	identifier
	 * @paramter	title
	 * @paramter	thumbnail
	 */
	function favoritevideo()
	{
		// Parameters ;
		$account 			= tep_get_value_require( "account" ) ;
		$identifier 		= tep_get_value_require( "identifier" ) ;
		$title 				= tep_get_value_require( "title" ) ;
		$thumbnail 			= tep_get_value_require( "thumbnail" ) ;
		$date				= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Check Video ;
		$sqlSearch 			= "SELECT * FROM tbl_video WHERE identifier = '$identifier' LIMIT 0, 1" ;
		$searchResult 		= tep_db_query( $sqlSearch ) ;
		$searchList 		= db_result_array( $searchResult ) ;

		if( count( $searchList ) == 0 )
		{
			// Database Insert ;
			$sqlInsert 		= "INSERT INTO tbl_video ( videoid, identifier, title, thumbnail, created_at ) VALUES ( '', '$identifier', '$title', '$thumbnail', '$date' )";
			tep_db_query( $sqlInsert ) ;

			// Database Search ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList 	= db_result_array( $searchResult ) ;
		}

		// Video ID ;
		$videoid			= $searchList[ 0 ][ "videoid" ] ;

		// Check Favorite ;
		$sqlSearch 			= "SELECT * FROM tbl_favorite WHERE userid = '$account' AND videoid = '$videoid' LIMIT 0, 1" ;
		$searchResult 		= tep_db_query( $sqlSearch ) ;
		$searchList 		= db_result_array( $searchResult ) ;

		if( count( $searchList ) == 0 )
		{
			// Database Insert ;
			$sqlInsert 		= "INSERT INTO tbl_favorite ( favoriteid, userid, videoid, created_at  ) VALUES ( NULL, '$account', '$videoid', '$date' )" ;
			tep_db_query( $sqlInsert ) ;
		}

		// Database Search ;
		$sqlSearch 			= "SELECT video.videoid, video.identifier, video.title, video.thumbnail, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = '$account' AND post.videoid = '$videoid' ) as posted, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.videoid = '$videoid' ) as posts,  ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.userid = '$account' AND favorite.videoid = '$videoid' ) as favorited, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.videoid = '$videoid' ) as favorites FROM tbl_video video WHERE video.videoid = '$videoid' LIMIT 0, 1" ;
		$searchResult 		= tep_db_query( $sqlSearch ) ;
		$searchList 		= db_result_array( $searchResult ) ;

		// Result ;
		echo '{"result":"successed","video":'.json_encode( $searchList[ 0 ] ).'}' ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			unfavoritevideo
	 * @paramter	account
	 * @paramter	videoid
	 */
	function unfavoritevideo()
	{
		// Parameters ;
		$account 			= tep_get_value_require( "account" ) ;
		$videoid 			= tep_get_value_require( "videoid" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Delete ;
		$sqlDelete 			= "DELETE FROM tbl_favorite WHERE userid = '$account' AND videoid = '$videoid'" ;
		tep_db_query( $sqlDelete ) ;

		// Database Search ;
		$sqlSearch 			= "SELECT video.videoid, video.identifier, video.title, video.thumbnail, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = '$account' AND post.videoid = '$videoid' ) as posted, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.videoid = '$videoid' ) as posts,  ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.userid = '$account' AND favorite.videoid = '$videoid' ) as favorited, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.videoid = '$videoid' ) as favorites FROM tbl_video video WHERE video.videoid = '$videoid' LIMIT 0, 1" ;
		$searchResult 		= tep_db_query( $sqlSearch ) ;
		$searchList 		= db_result_array( $searchResult ) ;

		// Result ;
		echo '{"result":"successed","video":'.json_encode( $searchList[ 0 ] ).'}' ;

		// Database Close ;
		tep_db_close() ;
	}
?>