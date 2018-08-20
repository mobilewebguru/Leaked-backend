<?php
	// --- Favorites --- ;

	/*
	 * @api			viewuser
	 * @paramter	account
	 * @paramter	userid
	 */
	function viewuser()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$userid			= tep_get_value_require( "userid" ) ;
		$date			= tep_now_time() ;		

		// Database Connect ;
		tep_db_connect() ;

		// Database Insert ;
		$sqlInsert 		= "INSERT INTO `tbl_view` ( `viewid`, `userid`, `viewed`, `created_at` ) VALUES ( NULL , '$account', '$userid', '$date' )" ;
		tep_db_query( $sqlInsert ) ;

		// Result ;
		echo '{"result":"successed","message":"viewed"}' ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			getfollowers
	 * @paramter	account
	 * @paramter	userid
	 * @paramter	last
	 * @paramter	count
	 */
	function getfollowers()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$userid			= tep_get_value_require( "userid" ) ;
		$last			= tep_get_value_require( "last" ) ;
		$count			= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		if( $last == 0 )
		{
			$sqlSearch		= "SELECT follow.followid AS id, user.userid, user.username, user.name, user.avatar, user.biography, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = '$account' AND follow.following = user.userid ) AS following, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples, ( SELECT COUNT( * ) FROM tbl_block block WHERE block.userid = '$account' AND block.blocked = user.userid ) AS blocking FROM tbl_follow follow JOIN tbl_user user ON user.userid = follow.userid WHERE follow.following = '$userid' ORDER BY follow.followid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList 	= db_result_array( $searchResult ) ;
		}
		else
		{
			$sqlSearch		= "SELECT follow.followid AS id, user.userid, user.username, user.name, user.avatar, user.biography, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = '$account' AND follow.following = user.userid ) AS following, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples, ( SELECT COUNT( * ) FROM tbl_block block WHERE block.userid = '$account' AND block.blocked = user.userid ) AS blocking FROM tbl_follow follow JOIN tbl_user user ON user.userid = follow.userid WHERE follow.followid < '$last' AND follow.following = '$userid' ORDER BY follow.followid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList 	= db_result_array( $searchResult ) ;
		}

		// Result ;
		echo json_encode( $searchList ) ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			getfollowings
	 * @paramter	account
	 * @paramter	userid	 
	 * @paramter	last
	 * @paramter	count
	 */
	function getfollowings()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$userid			= tep_get_value_require( "userid" ) ;
		$last			= tep_get_value_require( "last" ) ;
		$count			= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		if( $last == 0 )
		{
			$sqlSearch		= "SELECT follow.followid AS id, user.userid, user.username, user.name, user.avatar, user.biography, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = '$account' AND follow.following = user.userid ) AS following, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples, ( SELECT COUNT( * ) FROM tbl_block block WHERE block.userid = '$account' AND block.blocked = user.userid ) AS blocking FROM tbl_follow follow JOIN tbl_user user ON user.userid = follow.following WHERE follow.userid = '$userid' ORDER BY follow.followid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList 	= db_result_array( $searchResult ) ;
		}
		else
		{
			$sqlSearch		= "SELECT follow.followid AS id, user.userid, user.username, user.name, user.avatar, user.biography, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = '$account' AND follow.following = user.userid ) AS following, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples, ( SELECT COUNT( * ) FROM tbl_block block WHERE block.userid = '$account' AND block.blocked = user.userid ) AS blocking FROM tbl_follow follow JOIN tbl_user user ON user.userid = follow.following WHERE follow.followid < '$last' AND follow.userid = '$userid' ORDER BY follow.followid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList 	= db_result_array( $searchResult ) ;
		}

		// Result ;
		echo json_encode( $searchList ) ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			followuser
	 * @paramter	account
	 * @paramter	userid
	 */
	function followuser()
	{
		// Parameters ;
		$account 		= tep_get_value_require( "account" ) ;
		$userid			= tep_get_value_require( "userid" ) ;
		$date			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Check ;
		$sqlSearch 		= "SELECT * FROM tbl_follow WHERE userid = '$account' AND following = '$userid' LIMIT 0, 1" ;
		$searchResult 	= tep_db_query( $sqlSearch ) ;
		$searchList 	= db_result_array( $searchResult ) ;

		if( count( $searchList ) == 0 )
		{
			// Database Insert ;
			$sqlInsert 		= "INSERT INTO tbl_follow ( followid, userid, following, created_at ) VALUES ( NULL , '$account', '$userid', '$date' )";
			tep_db_query( $sqlInsert ) ;
		}

		// Result ;
		echo '{"result":"successed","message":"following"}' ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			unfollowuser
	 * @paramter	account
	 * @paramter	userid
	 */
	function unfollowuser()
	{
		// Parameters ;
		$account 		= tep_get_value_require( "account" ) ;
		$userid			= tep_get_value_require( "userid" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Delete ;
		$sqlDelete 		= "DELETE FROM tbl_follow WHERE userid = '$account' AND following = '$userid'" ;
		tep_db_query( $sqlDelete ) ;

		// Result ;
		echo '{"result":"successed","message":"unfollowing"}' ;

		// Database Close ;
		tep_db_close() ;
	}
    
    /*
	 * @api			blockuser
	 * @paramter	account
	 * @paramter	userid
	 */
	function blockuser()
	{
		// Parameters ;
		$account 		= tep_get_value_require( "account" ) ;
		$userid			= tep_get_value_require( "userid" ) ;
		$date			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Check ;
		$sqlSearch 		= "SELECT * FROM tbl_block WHERE userid = '$account' AND blocked = '$userid' LIMIT 0, 1" ;
		$searchResult 	= tep_db_query( $sqlSearch ) ;
		$searchList 	= db_result_array( $searchResult ) ;

		if( count( $searchList ) == 0 )
		{
			// Database Insert ;
			$sqlInsert 		= "INSERT INTO tbl_block ( blockid, userid, blocked, created ) VALUES ( NULL , '$account', '$userid', '$date' )";
			tep_db_query( $sqlInsert ) ;
		}

		// Database Delete ;
		$sqlDelete 			= "DELETE FROM tbl_follow WHERE userid = '$userid' AND following = '$account'" ;
		tep_db_query( $sqlDelete ) ;


		// Result ;
		echo '{"result":"successed","message":"blocked"}' ;

		// Database Close ;
		tep_db_close() ;
	}

    /*
     * @api			unblockuser
	 * @paramter	account
	 * @paramter	userid
	 */
	function unblockuser()
	{
		// Parameters ;
		$account 		= tep_get_value_require( "account" ) ;
		$userid			= tep_get_value_require( "userid" ) ;
		$date			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

    	// Database Delete ;
		$sqlDelete 		= "DELETE FROM tbl_block WHERE userid = '$account' AND blocked = '$userid'" ;
		tep_db_query( $sqlDelete ) ;
        
		// Result ;
		echo '{"result":"successed","message":"unblocked"}' ;

		// Database Close ;
		tep_db_close() ;
	}


	/*
	 * @api			searchusers
	 * @paramter	account
	 * @paramter	string
	 * @paramter	last
	 * @paramter	count
	 */
	function searchusers()
	{
		// Parameters ;
		$account 		= tep_get_value_require( "account" ) ;
		$string			= tep_get_value_require( "string" ) ;
		$last			= tep_get_value_require( "last" ) ;
		$count			= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		$sqlSearch		= "SELECT user.userid AS id, user.userid, user.username, user.name, user.avatar, user.biography, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = '$account' AND follow.following = user.userid ) AS following, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples, ( SELECT COUNT( * ) FROM tbl_block block WHERE block.userid = '$account' AND block.blocked = user.userid ) AS blocking FROM tbl_user user WHERE user.userid != '$account' AND ( user.username LIKE ( '%$string%' ) OR user.name LIKE ( '%$string%' ) ) AND ( user.userid NOT IN ( SELECT block.userid FROM tbl_block block WHERE block.blocked = '$account' ) ) ORDER BY user.userid ASC  LIMIT $last, $count" ;
		$searchResult 	= tep_db_query( $sqlSearch ) ;
		$searchList 	= db_result_array( $searchResult ) ;

		// Result ;
		echo json_encode( $searchList ) ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			getleakusers
	 * @paramter	account
	 * @paramter	last
	 * @paramter	count
	 */
	function getleakusers()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$last			= tep_get_value_require( "last" ) ;
		$count			= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		if( $last == 0 )
		{
			$sqlSearch		= "SELECT follow.followid as id, user.userid, user.username, user.name, user.avatar, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) as posts, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.userid = user.userid ) as favorites, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) as follows, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = '$account' AND follow.followed = user.userid ) as followed FROM tbl_user user JOIN tbl_follow follow ON follow.followed = user.userid WHERE follow.userid = '$account' ORDER BY follow.followid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList 	= db_result_array( $searchResult ) ;
		}
		else
		{
			$sqlSearch		= "SELECT follow.followid as id, user.userid, user.username, user.name, user.avatar, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) as posts, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.userid = user.userid ) as favorites, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) as follows, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = '$account' AND follow.followed = user.userid ) as followed FROM tbl_user user JOIN tbl_follow follow ON follow.followed = user.userid WHERE follow.followid < '$last' AND follow.userid = '$account' ORDER BY follow.followid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList 	= db_result_array( $searchResult ) ;
		}

		// Result ;
		echo json_encode( $searchList ) ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			getfacebookusers
	 * @paramter	account
	 * @paramter	friends
	 * @paramter	number
	 * @paramter	count
	 */
	function getfacebookusers()
	{
		// Parameters ;
		$account 			= tep_get_value_require( "account" ) ;
		$friends			= tep_get_value_require( "friends" ) ;
		$number				= tep_get_value_require( "number" ) ;
		$count				= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;
	
		// Database Search ;
		$sqlSearch			= "SELECT user.userid, user.username, user.name, user.avatar, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.userid = '$account' AND favorite.followed = user.userid ) as favorited, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) as posts, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.userid = user.userid ) as favorites, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) as follows FROM tbl_user user WHERE user.username = '%$text%' OR user.name = '%$text%' LIMIT 0, 15" ;
		$searchResult 		= tep_db_query( $sqlSearch ) ;
		$searchList 		= db_result_array( $searchResult ) ;


		// Result ;
		echo '{"result":"successed","message":'.json_encode( $searchList ).'}' ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			gettwitterusers
	 * @paramter	account
	 * @paramter	friends
	 * @paramter	number
	 * @paramter	count
	 */
	function gettwitterusers()
	{
		// Parameters ;
		$account 			= tep_get_value_require( "account" ) ;
		$friends			= tep_get_value_require( "friends" ) ;
		$number				= tep_get_value_require( "number" ) ;
		$count				= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;
	
		// Database Search ;
		$sqlSearch			= "SELECT user.userid, user.username, user.name, user.avatar, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.userid = '$account' AND favorite.followed = user.userid ) as favorited, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) as posts, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.userid = user.userid ) as favorites, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) as follows FROM tbl_user user WHERE user.username = '%$text%' OR user.name = '%$text%' LIMIT 0, 15" ;
		$searchResult 		= tep_db_query( $sqlSearch ) ;
		$searchList 		= db_result_array( $searchResult ) ;


		// Result ;
		echo '{"result":"successed","message":'.json_encode( $searchList ).'}' ;

		// Database Close ;
		tep_db_close() ;
	}
?>