<?php
	// --- Notifications --- ;

	/*
	 * @api			getnotifications
	 * @paramter	account
	 * @paramter	last
	 * @paramter	count	 
	 */
	function getnotifications()
	{
		// Parameters ;
		$account 		= tep_get_value_require( "account" ) ;
		$last			= tep_get_value_require( "last" ) ;
		$count			= tep_get_value_require( "count" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		if( $last == 0 )
		{
			$sqlSearch 		= "SELECT post.postid as id, post.userid as user, post.videoid as target, 'posted' as action, post.created_at as created_at FROM tbl_post post JOIN tbl_follow follow ON follow.followed = post.userid WHERE follow.userid = '$account' UNION SELECT favorite.favoriteid as id, favorite.userid as user, favorite.videoid as target, 'favorited' as action, favorite.created_at as created_at FROM tbl_favorite favorite JOIN tbl_follow follow ON follow.followed = favorite.userid WHERE follow.userid = '$account' UNION SELECT user.followid as id, user.userid as user, user.followed as target, 'followed' as action, user.created_at as created_at FROM tbl_follow user JOIN tbl_follow follow ON follow.followed = user.userid WHERE follow.userid = '$account' UNION SELECT friend.friendid as id, friend.userid as user, '' as target, 'joined' as action, friend.created_at as created_at FROM tbl_friend friend WHERE friend.joined = '$account' ORDER BY created_at DESC LIMIT 0, $count" ;
			$searchResult	= tep_db_query( $sqlSearch ) ;
			$events			= db_result_array( $searchResult ) ;
		}
		else
		{
			$sqlSearch 		= "SELECT post.postid as id, post.userid as user, post.videoid as target, 'posted' as action, post.created_at as created_at FROM tbl_post post JOIN tbl_follow follow ON follow.followed = post.userid WHERE follow.userid = '$account' AND post.created_at < '$last' UNION SELECT favorite.favoriteid as id, favorite.userid as user, favorite.videoid as target, 'favorited' as action, favorite.created_at as created_at FROM tbl_favorite favorite JOIN tbl_follow follow ON follow.followed = favorite.userid WHERE follow.userid = '$account' AND favorite.created_at < '$last' UNION SELECT user.followid as id, user.userid as user, user.followed as target, 'followed' as action, user.created_at as created_at FROM tbl_follow user JOIN tbl_follow follow ON follow.followed = user.userid WHERE follow.userid = '$account' AND user.created_at < '$last' UNION SELECT friend.friendid as id, friend.userid as user, '' as target, 'joined' as action, friend.created_at as created_at FROM tbl_friend friend WHERE friend.joined = '$account' AND friend.created_at < '$last' ORDER BY created_at DESC LIMIT 0, $count" ;
			$searchResult	= tep_db_query( $sqlSearch ) ;
			$events			= db_result_array( $searchResult ) ;
		}

		// Notifications;
		$notifications	= array() ;

		foreach( $events as $event )
		{
			// Notification ;
			$notification	= array() ;

			// User ;
			$userid			= $event[ "user" ] ;
			$userSearch		= "SELECT user.userid, user.username, user.name, user.avatar, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) as posts, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.userid = user.userid ) as favorites, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) as follows, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = '$account' AND follow.followed = user.userid ) as followed FROM tbl_user user WHERE user.userid = '$userid' LIMIT 0, 1" ;
			$userResult		= tep_db_query( $userSearch ) ;
			$userList	 	= db_result_array( $userResult ) ;
			$notification[ "user" ] = $userList[ 0 ] ;

			// Target ;
			if( $event[ "action" ] == "posted" )
			{
				$videoid		= $event[ "target" ] ;
				$targetSearch	= "SELECT video.videoid, video.identifier, video.title, video.thumbnail, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = '$account' AND post.videoid = video.videoid ) as posted, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.videoid = video.videoid ) as posts, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.userid = '$account' AND favorite.videoid = video.videoid ) as favorited, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.videoid = video.videoid ) as favorites FROM tbl_video video WHERE video.videoid = '$videoid' LIMIT 0, 1" ;
				$targetResult 	= tep_db_query( $targetSearch ) ;
				$targetList 	= db_result_array( $targetResult ) ;
				$notification[ "target" ] = $targetList[ 0 ] ;
			}
			else if( $event[ "action" ] == "favorited" )
			{
				// Database Search ;
				$videoid		= $event[ "target" ] ;
				$targetSearch	= "SELECT video.videoid, video.identifier, video.title, video.thumbnail, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = '$account' AND post.videoid = video.videoid ) as posted, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.videoid = video.videoid ) as posts, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.userid = '$account' AND favorite.videoid = video.videoid ) as favorited, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.videoid = video.videoid ) as favorites FROM tbl_video video WHERE video.videoid = '$videoid' LIMIT 0, 1" ;
				$targetResult 	= tep_db_query( $targetSearch ) ;
				$targetList 	= db_result_array( $targetResult ) ;
				$notification[ "target" ] = $targetList[ 0 ] ;
			}
			else if( $event[ "action" ] == "followed" )
			{
				// Database Search ;
				$userid			= $event[ "target" ] ;
				$targetSearch	= "SELECT user.userid, user.username, user.name, user.avatar, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) as posts, ( SELECT COUNT( * ) FROM tbl_favorite favorite WHERE favorite.userid = user.userid ) as favorites, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) as follows, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = '$account' AND follow.followed = user.userid ) as followed FROM tbl_user user WHERE user.userid = '$userid' LIMIT 0, 1" ;
				$targetResult 	= tep_db_query( $targetSearch ) ;
				$targetList 	= db_result_array( $targetResult ) ;
				$notification["target"] = $targetList[ 0 ] ;
			}	
			else if( $event[ "action" ] == "joined" )
			{

			}

			// Action ;
			$notification[ "action" ] = $event[ "action" ] ;

			// Created At ;
			$notification[ "created_at" ] = $event[ "created_at" ] ;

			// Add ;
			$notifications[] = $notification ;
		}


		// Result ;
		echo json_encode( $notifications ) ;

		// Database Close ;
		tep_db_close() ;
	}
?>