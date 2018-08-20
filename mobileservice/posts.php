<?php
    // --- Posts ---;

	/*
	 * @api			getfeedposts
	 * @paramter	account
	 * @paramter	last
	 * @paramter	count
	 */
	function getfeedposts()
	{
		$account 		= tep_get_value_require( "account" ) ;
		$last			= tep_get_value_require( "last" ) ;
		$count 			= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		if( $last == 0 )
		{
			$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post JOIN tbl_follow follow ON post.replied = '0' AND post.userid = follow.following WHERE post.created_at > follow.created_at AND follow.userid = '$account' UNION SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post WHERE post.replied = '0' AND post.userid = '$account' ORDER BY postid DESC LIMIT 0, $count" ;			
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;
		}
		else
		{
			$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post JOIN tbl_follow follow ON post.replied = '0' AND post.userid = follow.following WHERE post.created_at > follow.created_at AND post.postid < '$last' AND follow.userid = '$account' UNION SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post WHERE post.postid < '$last' AND post.replied = '0' AND post.userid = '$account' ORDER BY postid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;
		}

		// Posts ;
		$posts = getposts( $searchList ) ;
		// Successed ;
		echo json_encode( $posts ) ;
		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			getactivityposts
	 * @paramter	account
	 * @paramter	last
	 * @paramter	count
	 */
	function getactivityposts()
	{
		$account 		= tep_get_value_require( "account" ) ;
		$last			= tep_get_value_require( "last" ) ;
		$count 			= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		if( $last == 0 )
		{
			$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post WHERE post.userid = '$account' ORDER BY postid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;
		}
		else
		{
			$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post WHERE post.postid < '$last' AND post.userid = '$account' ORDER BY postid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;
		}

		// Posts ;
		$posts = getposts( $searchList ) ;

		// Successed ;
		echo json_encode( $posts ) ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			getsaveposts
	 * @paramter	account
	 * @paramter	last
	 * @paramter    count
	 */
	function getsaveposts()
	{
		$account 		= tep_get_value_require( "account" ) ;
		$last			= tep_get_value_require( "last" ) ;
		$count 			= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		if( $last == 0 )
		{
			$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post JOIN tbl_mark mark ON mark.postid = post.postid WHERE mark.userid = '$account' AND mark.saves = 1 ORDER BY post.postid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;
		}
		else
		{
			$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post JOIN tbl_mark mark ON mark.postid = post.postid WHERE post.postid < '$last' AND mark.userid = '$account' AND mark.saves = 1 ORDER BY post.postid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;
		}

		// Posts ;
		$posts = getposts( $searchList ) ;

		// Successed ;
		echo json_encode( $posts ) ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			getaccountmarks
	 * @paramter	account
	 */
	function getaccountmarks()
	{
		$account 		= tep_get_value_require( "account" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Mark ;
		$sqlSearch		= "SELECT SUM( hearts ) AS hearts, SUM( heart_eyes ) AS heart_eyes, SUM( angry_faces ) AS angry_faces, SUM( question_marks ) AS question_marks, SUM( wtfs ) AS wtfs, SUM( thumb_downs ) AS thumb_downs, SUM( middle_fingers ) AS middle_fingers, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.reposted != '0' AND post.userid = '$account' ) as reposts, SUM( saves ) AS saves, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.replied != '0' AND post.userid = '$account' ) as replies FROM tbl_mark WHERE userid = '$account' GROUP BY userid" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		// Marks ;
		if( count( $searchList ) )
		{
			$marks 		= $searchList[ 0 ] ;
		}
		else
		{
			$marks 		= array() ;
			$marks[ "hearts" ] = 0 ;
			$marks[ "heart_eyes" ] = 0 ;
			$marks[ "angry_faces" ] = 0 ;
			$marks[ "question_marks" ] = 0 ;
			$marks[ "wtfs" ] = 0 ;
			$marks[ "thumb_downs" ] = 0 ;
			$marks[ "middle_fingers" ] = 0 ;
			$marks[ "reposts" ] = 0 ;
			$marks[ "saves" ] = 0 ;
			$marks[ "replies" ] = 0 ;
		}

		// Successed ;
		echo '{"result":"successed","marks":'.json_encode( $marks ).'}' ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			getmarkedposts
	 * @paramter	account
	 * @paramter	mark	 
	 * @paramter	last
	 * @paramter    count
	 */
	function getmarkedposts()
	{
		$account 		= tep_get_value_require( "account" ) ;
		$mark			= tep_get_value_require( "mark" ) ;		
		$last			= tep_get_value_require( "last" ) ;
		$count 			= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		if( $last == 0 )
		{
			$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post JOIN tbl_mark mark ON mark.postid = post.postid WHERE mark.userid = '$account' AND mark.$mark != '0' ORDER BY post.postid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;
		}
		else
		{
			$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post JOIN tbl_mark mark ON mark.postid = post.postid WHERE post.postid < '$last' AND mark.userid = '$account' AND mark.$mark != '0' ORDER BY post.postid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;
		}

		// Posts ;
		$posts = getposts( $searchList ) ;

		// Successed ;
		echo json_encode( $posts ) ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			getaccountreposts
	 * @paramter	account
	 * @paramter	last
	 * @paramter    count
	 */
	function getaccountreposts()
	{
		$account 		= tep_get_value_require( "account" ) ;
		$last			= tep_get_value_require( "last" ) ;
		$count 			= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		if( $last == 0 )
		{
			$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post WHERE post.reposted != '0' AND post.userid = '$account' ORDER BY post.postid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;
		}
		else
		{
			$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post WHERE post.postid < '$last' AND post.reposted != '0' AND post.userid = '$account' ORDER BY post.postid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;
		}

		// Posts ;
		$posts = getposts( $searchList ) ;

		// Successed ;
		echo json_encode( $posts ) ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			getaccountreplies
	 * @paramter	account
	 * @paramter	last
	 * @paramter    count
	 */
	function getaccountreplies()
	{
		$account 		= tep_get_value_require( "account" ) ;
		$last			= tep_get_value_require( "last" ) ;
		$count 			= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		if( $last == 0 )
		{
			$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post WHERE post.replied != '0' AND post.userid = '$account' ORDER BY post.postid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;
		}
		else
		{
			$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post WHERE post.postid < '$last' AND post.replied != '0' AND post.userid = '$account' ORDER BY post.postid DESC LIMIT 0, $count" ;
			$searchResult 	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;
		}

		// Posts ;
		$posts 			= array() ;

		foreach( $searchList as $post )
		{
			// Post ID ;
			$postid 		= $post[ "postid" ] ;

			// Marks ;
			$post[ "marks" ] = getmarks( $postid ) ;
			
			// Add ;
			$posts[] = $post ;
		}

		// Successed ;
		echo json_encode( $posts ) ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			poststatus
	 * @paramter	account
	 * @paramter	comment
	 */
	function poststatus()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$comment		= tep_get_value_require( "comment" ) ;
		$date			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Insert ;
		$sqlInsert		= "INSERT INTO tbl_post ( postid, userid, reposted, replied, comment, created_at ) VALUES ( NULL , '$account', '0', '0', '$comment', '$date' )" ;
		tep_db_query( $sqlInsert ) ;

		// Database Search ;
		$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post WHERE post.userid = '$account' AND post.created_at = '$date' ORDER BY post.postid DESC LIMIT 0 , 1" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		// Post ;
		$post 			= $searchList[ 0 ] ;
		$postid 		= $post[ "postid" ] ;

		// Marks ;
		$post[ "marks" ] = getmarks( $postid ) ;

		// Successed ;
		echo '{"result":"successed","post":'.json_encode( $post ).'}' ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			repoststatus
	 * @paramter	account
	 * @paramter	postid
	 * @paramter	comment
	 */
	function repoststatus()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$postid			= tep_get_value_require( "postid" ) ;		
		$comment		= tep_get_value_require( "comment" ) ;
		$date			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Insert ;
		$sqlInsert		= "INSERT INTO tbl_post ( postid, userid, reposted, replied, comment, created_at ) VALUES ( NULL , '$account', '$postid', '0', '$comment', '$date' )" ;
		tep_db_query( $sqlInsert ) ;

		// Database Search ;
		$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post WHERE post.userid = '$account' AND post.created_at = '$date' ORDER BY post.postid DESC LIMIT 0 , 1" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		// Post ;
		$post 			= $searchList[ 0 ] ;
		$postid 		= $post[ "postid" ] ;

		// Marks ;
		$post[ "marks" ] = getmarks( $postid ) ;

		// Successed ;
		echo '{"result":"successed","post":'.json_encode( $post ).'}' ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			replystatus
	 * @paramter	account
	 * @paramter	postid
	 * @paramter	comment
	 */
	function replystatus()
	{

		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$postid			= tep_get_value_require( "postid" ) ;
		$comment		= tep_get_value_require( "comment" ) ;
		$date			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Insert ;
		$sqlInsert		= "INSERT INTO tbl_post ( postid, userid, reposted, replied, comment, created_at ) VALUES ( NULL , '$account', '0', '$postid', '$comment', '$date' )" ;
		tep_db_query( $sqlInsert ) ;

		// Database Search ;
		$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post WHERE post.userid = '$account' AND post.created_at = '$date' ORDER BY post.postid DESC LIMIT 0 , 1" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		// Post ;
		$post 			= $searchList[ 0 ] ;
		$postid 		= $post[ "postid" ] ;

		// Marks ;
		$post[ "marks" ] = getmarks( $postid ) ;

		// Successed ;
		echo '{"result":"successed","post":'.json_encode( $post ).'}' ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			markstatus
	 * @paramter	account
	 * @paramter	postid
	 * @paramter	mark
	 */
	function markstatus()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$postid			= tep_get_value_require( "postid" ) ;
		$mark 			= tep_get_value_require( "mark" ) ;
		$date			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		$sqlSearch		= "SELECT * FROM `tbl_mark` WHERE `userid` = '$account' AND `postid` = '$postid' AND `$mark` = '1'" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		// Database Delete ;
		$sqlDelete		= "DELETE FROM `tbl_mark` WHERE `userid` = '$account' AND `postid` = '$postid' LIMIT 1" ;
		tep_db_query( $sqlDelete ) ;

		if( count( $searchList ) == 0 )
		{
			// Database Insert ;
			$sqlInsert		= "INSERT INTO `tbl_mark` ( `markid`, `userid`, `postid`, `$mark`, `created_at` ) VALUES ( NULL, '$account', '$postid', '1', '$date' )" ;
			tep_db_query( $sqlInsert ) ;
		}

		// Marks ;
		$marks 			= getmarks( $postid ) ;

		// Successed ;
		echo '{"result":"successed","marks":'.json_encode( $marks ).'}' ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			deletestatus
	 * @paramter	account
	 * @paramter	postid
	 */
	function deletestatus()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$postid			= tep_get_value_require( "postid" ) ;
		$date			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Delete ;
		$sqlDelete		= "DELETE FROM tbl_mark WHERE postid IN ( SELECT postid FROM tbl_post WHERE replied = '$postid' )" ;
		tep_db_query( $sqlDelete ) ;

		// Database Delete ;
		$sqlDelete		= "DELETE FROM tbl_mark WHERE postid = '$postid'" ;
		tep_db_query( $sqlDelete ) ;

		// Database Delete ;
		$sqlDelete		= "DELETE FROM tbl_post WHERE ( postid = '$postid' AND userid = '$account' ) OR ( replied = '$postid' )" ;
		tep_db_query( $sqlDelete ) ;

		// Successed ;
		echo '{"result":"successed","message":"deleted"}' ;

		// Database Close ;
		tep_db_close() ;
	}
    
    /*
	 * @api			reportstatus
	 * @paramter	account
	 * @paramter	postid
	 * @paramter	comment
	 */
	function reportstatus()
	{

		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$postid			= tep_get_value_require( "postid" ) ;
		$date			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Insert ;
		$sqlInsert		= "INSERT INTO tbl_report ( reportid, userid, reported, created ) VALUES ( NULL , '$account', '$postid', '$date' )" ;
		tep_db_query( $sqlInsert ) ;

    	// Successed ;
		echo '{"result":"successed","message":"reported"}' ;

		// Database Close ;
		tep_db_close() ;
	}


	/*
	 * @api			getposts
	 * @paramter	posts
	 */
	function getposts( $posts )
	{
		// Posts;
		$result			= array() ;

		foreach( $posts as $post )
		{
			// Post ID ;
			$postid 		= $post[ "postid" ] ;

			// Marks ;
			$post[ "marks" ] = getmarks( $postid ) ;

			// Repliess ;
			$post[ "replies" ] = getreplies( $postid ) ;

			// Add ;
			$result[] = $post ;
		}

		return $result ;
	}

	/*
	 * @api			getmarks
	 * @paramter	postid
	 */
	function getmarks( $postid )
	{
		// Database Search ;
		$sqlSearch		= "SELECT SUM( hearts ) AS hearts, SUM( heart_eyes ) AS heart_eyes, SUM( angry_faces ) AS angry_faces, SUM( question_marks ) AS question_marks, SUM( wtfs ) AS wtfs, SUM( thumb_downs ) AS thumb_downs, SUM( middle_fingers ) AS middle_fingers, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.reposted = '$postid' ) as reposts, SUM( saves ) AS saves, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.replied = '$postid' ) as replies FROM tbl_mark WHERE postid = '$postid' GROUP BY postid" ;

		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		// Marks ;
		if( count( $searchList ) )
		{
			$marks 		= $searchList[ 0 ] ;
		}
		else
		{
			$marks 		= array() ;
			$marks[ "hearts" ] = 0 ;
			$marks[ "heart_eyes" ] = 0 ;
			$marks[ "angry_faces" ] = 0 ;
			$marks[ "question_marks" ] = 0 ;
			$marks[ "wtfs" ] = 0 ;
			$marks[ "thumb_downs" ] = 0 ;
			$marks[ "middle_fingers" ] = 0 ;
			$marks[ "reposts" ] = 0 ;
			$marks[ "saves" ] = 0 ;
			$marks[ "replies" ] = 0 ;
		}

		return $marks ;
	}

	/*
	 * @api			getreplies
	 * @paramter	postid
	 */
	function getreplies( $postid )
	{
		// Database Search ;
		$sqlSearch		= "SELECT post.postid, post.userid, post.reposted, post.replied, post.comment, post.created_at FROM tbl_post post WHERE post.replied = '$postid' ORDER BY post.postid ASC" ;
		$postResult 	= tep_db_query( $sqlSearch ) ;
		$postList		= db_result_array( $postResult ) ;

		$posts 			= array() ;

		foreach( $postList as $post )
		{
			// Post ID ;
			$postid		= $post[ "postid" ] ;

			// Marks ;
			$post[ "marks" ] = getmarks( $postid ) ;

			// Add ;
			$posts[] 	= $post ;
		}

		return $posts;
	}
?>