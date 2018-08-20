<?php
	// --- Users --- ;

	/*
	 * @api			signinfacebook
	 * @paramter	username
	 * @paramter	email
	 * @paramter	name
	 * @paramter	facebookid
	 * @paramter	friends	 
	 */
	function signinfacebook()
	{
		// Parameters ;
		$username 		= tep_get_value_require( "username" ) ;
		$name 			= tep_get_value_require( "name" ) ;
		$email 			= tep_get_value_require( "email" ) ;
		$facebookid		= tep_get_value_require( "facebookid" ) ;
		$avatar 		= "http://graph.facebook.com/$facebookid/picture?width=120" ;
		$friends		= $_POST[ "friends" ] ;
		$date 			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		$sqlSearch		= "SELECT * FROM tbl_user WHERE facebook_id = '$facebookid'" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		if( count( $searchList ) == 0 )
		{
			// Database Insert ;
			$sqlInsert		= "INSERT INTO `tbl_user` ( `userid`, `username`, `password`, `name`, `email`, `avatar`, `biography`, `notification`, `privated`, `facebook_id`, `twitter_id`, `created_at` ) VALUES ( NULL , '$username', '', '$name', '$email', '$avatar', '', '', '', '$facebookid', '', '$date' )" ;
			tep_db_query( $sqlInsert ) ;
		}
		else
		{
			// Database Update ;
			$sqlUpdate		= "UPDATE tbl_user SET username = '$username', name = '$name', email = '$email', avatar = '$avatar' WHERE facebook_id = '$facebookid'" ;
			tep_db_query( $sqlUpdate ) ;
		}

		if( $friends )
		{
			foreach( $friends as $friend )
			{
				$sqlDelete		= "DELETE FROM tbl_friend WHERE userid = '$facebookid' AND joined = '$friend'" ;
				tep_db_query( $sqlDelete ) ;

				// Database Insert ;
				$sqlInsert		= "INSERT INTO `tbl_friend` ( `friendid`, `userid`, `joined`, `created` ) VALUES ( NULL , '$facebookid', '$friend', '$date' )" ;
				tep_db_query( $sqlInsert ) ;
			}
		}

		// Database Search ;
		$sqlSearch			= "SELECT user.userid, user.username, user.name, user.email, user.avatar, user.biography, user.notification, user.privated, user.facebook_id, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples FROM tbl_user user WHERE user.facebook_id = '$facebookid' LIMIT 0, 1" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;
		
		// Result ;
		echo '{"result":"successed","user":'.json_encode( $searchList[ 0 ] ).'}' ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			signintwitter
	 * @paramter	username
	 * @paramter	email
	 * @paramter	name
   	 * @paramter	facebookid
	 */
	function signintwitter()
	{
		// Parameters ;
		$username 		= tep_get_value_require( "username" ) ;
		$name 			= tep_get_value_require( "name" ) ;
		$email 			= tep_get_value_require( "email" ) ;
		$facebookid		= tep_get_value_require( "facebookid" ) ;
		$avatar 		= "http://graph.facebook.com/$facebookid/picture?width=120" ;
		$date 			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		$sqlSearch		= "select * from tbl_user where facebook_id = '$facebookid'" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		if( count( $searchList ) == 0 )
		{
			// Database Insert ;
			$sqlInsert		= "INSERT INTO tbl_user ( userid, username, password, name, email, avatar, facebook_id, quality, created_at ) VALUES ( NULL , '$username', '', '$name', '$email', '$avatar', '$facebookid', '', '$date' )" ;
			tep_db_query( $sqlInsert ) ;
		}
		else
		{
			// Database Update ;
			$sqlUpdate		= "UPDATE tbl_user SET username = '$username', name = '$name', email = '$email', avatar = '$avatar' WHERE facebook_id = '$facebookid'" ;
			tep_db_query( $sqlUpdate ) ;
		}

		// Database Search ;
		$sqlSearch		= "SELECT user.userid, user.username, user.name, user.email, user.avatar, user.biography, user.notification, user.privated, user.facebook_id, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples FROM tbl_user user WHERE user.username = '$username' AND user.password = '$password' LIMIT 0, 1" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;
		
		// Result ;
		echo '{"result":"successed","user":'.json_encode( $searchList[ 0 ] ).'}' ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			login
	 * @paramter	username
	 * @paramter	password
	 */
	function login()
	{
		// Parameters ;
		$username 		= tep_get_value_require( "username" ) ;
		$password 		= tep_get_value_require( "password" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		$sqlSearch		= "SELECT user.userid, user.username, user.password as userpassword, user.name, user.email, user.avatar, user.biography, user.notification, user.privated, user.facebook_id, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples FROM tbl_user user WHERE user.username = '$username' AND user.password = '$password' LIMIT 0, 1" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		// Result ;
		if( count( $searchList ) )
		{
			echo '{"result":"successed","user":'.json_encode( $searchList[ 0 ] ).'}' ;
		}
		else
		{
			echo '{"result":"failed","message":"Invalid Email/Password"}' ;
		}

		// Database Close;
		tep_db_close() ;
	}

	/*
	 * @api			registerwithemail
	 * @paramter	avatar
     * @paramter	name
	 * @paramter	username
     * @paramter	email
     * @paramter	password
	 */
	function registerwithemail()
	{
		// Parameters ;
		$name   		= tep_get_value_require( "name" ) ;
		$username		= tep_get_value_require( "username" ) ;
		$email			= tep_get_value_require( "email" ) ;
		$password		= tep_get_value_require( "password" ) ;
		$date			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Check Username ;
		$sqlSearch		= "SELECT * FROM tbl_user WHERE username = '$username'" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		if( count( $searchList ) )
		{
			// Result ;
			echo '{"result":"failed","message":"Invalid Username"}' ;
		}
		else
		{
			// Check Email ;
			$sqlSearch		= "select * from tbl_user where email = '$email'" ;
			$searchResult	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;

			if( count( $searchList ) )
			{
				// Result ;
				echo '{"result":"failed","message":"Invalid Email"}' ;
			}
			else
			{
				// Check Avatar ;
				$avatar_url		= "" ;
				$avatar_path	= "uploads/avatars/avatar_$date.jpg" ;

				if( move_uploaded_file( $_FILES[ 'avatar' ][ 'tmp_name' ], $avatar_path ) )
				{
					$avatar_url		= HTTP_CATALOG_SERVER."uploads/avatars/avatar_$date.jpg" ;
				}

				// Database Insert ;
				$sqlInsert		= "INSERT INTO `tbl_user` ( `userid`, `username`, `password`, `name`, `email`, `avatar`, `biography`, `notification`, `privated`, `facebook_id`, `twitter_id`, `created_at` ) VALUES ( NULL , '$username', '$password', '$name', '$email', '$avatar_url', '', '', '', '', '', '$date' )";
				tep_db_query( $sqlInsert ) ;

				// Database Search ;
				$sqlSearch			= "SELECT user.userid, user.username, user.name, user.email, user.avatar, user.biography, user.notification, user.privated, user.facebook_id, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples FROM tbl_user user WHERE user.username = '$username' LIMIT 0, 1" ;
				$searchResult	= tep_db_query( $sqlSearch ) ;
				$searchList		= db_result_array( $searchResult ) ;
		
				// Result ;
				echo '{"result":"successed","user":'.json_encode( $searchList[ 0 ] ).'}' ;
			}
		}

		// Database Close ;
		tep_db_close() ;
	}
	
	/*
	 * @api				forgotpassword
	 * @paramter		email
	 */
	function forgotpassword()
	{
		// Parameters ;
		$email 			= tep_get_value_require( "email" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Password ;
		$chars			= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789``-=~!@#$%^&*()_+,./<>?;:[]{}\|';
		$max			= strlen( $chars ) - 1 ;
		$password		= '';

		for( $i = 0 ; $i < mt_rand( 8, 15 ) ; $i ++ )
		{
			$password .= $chars[ mt_rand( 0, $max ) ] ;
		}

		// Mail ;
		$to 			= $email ;
		$subject 		= 'Forgot Password!' ;
		$message 		= "Your new password is $password";
		$headers 		= 'From: hello@blakegentry.com' . "\r\n" .
						  'Reply-To: hello@blakegentry.com' . "\r\n" .
						  'X-Mailer: PHP/' . phpversion() ;
		mail( $to, $subject, $message, $headers ) ;

		// Database Update ;
		$sqlUpdate		= "UPDATE tbl_user SET password = '$password' WHERE email = '$email'" ;
		tep_db_query( $sqlUpdate ) ;

		// Result ;
		echo '{"result":"successed","message":"'.$password.'"}' ;

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			checkusername
	 * @paramter	username
	 */
	function checkusername()
	{
		// Parameters ;
		$username  		= tep_get_value_require( "username" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Search ;
		$sqlSearch		= "SELECT * FROM tbl_user WHERE user_username = '$username'" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		// Result ;
		if( count( $searchList ) )
		{
			echo '{"result":"successed","message":"Valid Username"}' ;
		}
		else
		{
			echo '{"result":"failed","message":"Invalid Username"}' ;
		}

		tep_db_close() ;
	}

	/*
	 * @api			changeprofilepicture
	 * @paramter	account
	 * @paramter	avatar	 
	 */
	function changeprofilepicture()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$date			= tep_now_time() ;
		$avatar_path	= "uploads/avatars/avatar_$date.jpg" ;

		// Database Connect ;
		tep_db_connect() ;

		// Check Avatar ;
		if( move_uploaded_file( $_FILES[ 'avatar' ][ 'tmp_name' ], $avatar_path ) )
		{
			$avatar_url		= HTTP_CATALOG_SERVER."uploads/avatars/avatar_$date.jpg" ;

			// Database Update ;
			$sqlUpdate		= "UPDATE tbl_user SET avatar = '$avatar_url' WHERE userid = '$account'" ;
			tep_db_query( $sqlUpdate ) ;
		}

		// Database Search ;
		$sqlSearch		= "SELECT user.userid, user.username, user.name, user.email, user.avatar, user.biography, user.notification, user.privated, user.facebook_id, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples FROM tbl_user user WHERE user.userid = '$account' LIMIT 0, 1" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		// Result ;
		if( count( $searchList ) )
		{
			echo '{"result":"successed","user":'.json_encode( $searchList[ 0 ] ).'}' ;
		}
		else
		{
			echo '{"result":"failed","message":"Connection Error"}' ;
		}

		tep_db_close() ;
	}


	/*
	 * @api			savebiography
	 * @paramter	account
	 * @paramter	biography
	 */
	function savebiography()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$biography		= tep_get_value_require( "biography" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Update ;
		$sqlUpdate		= "UPDATE tbl_user SET biography = '$biography' WHERE userid = '$account'" ;
		tep_db_query( $sqlUpdate ) ;
	
		// Database Search ;
		$sqlSearch		= "SELECT user.userid, user.username, user.name, user.email, user.avatar, user.biography, user.notification, user.privated, user.facebook_id, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples FROM tbl_user user WHERE user.userid = '$account' LIMIT 0, 1" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		// Successed ;
		echo '{"result":"successed","user":'.json_encode( $searchList[ 0 ] ).'}' ;
	
		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			editprofile
	 * @paramter	account
	 * @paramter	name
	 * @paramter	username
	 * @paramter	email
	 * @paramter    password
	 */
	function editprofile()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$name 			= tep_get_value_require( "name" ) ;
		$username 		= tep_get_value_require( "username" ) ;
		$email 			= tep_get_value_require( "email" ) ;
		$password 		= tep_get_value_require( "password" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Check Username ;
		$sqlSearch		= "SELECT * FROM tbl_user WHERE userid <> '$account' AND username = '$username'" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		// Result ;
		if( count( $searchList ) )
		{
			echo '{"result":"failed","message":"Invalid Username"}' ;
		}
		else
		{
			// Check Email ;
			$sqlSearch		= "SELECT * FROM tbl_user WHERE userid <> '$account' AND email = '$email'" ;
			$searchResult	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;

			// Result ;
			if( count( $searchList ) )
			{
				echo '{"result":"failed","message":"Invalid Email"}' ;
			}
			else
			{
				// Check Password ;
				if( $password != "" )
				{
					// Database Update ;
					$sqlUpdate		= "UPDATE tbl_user SET password = '$password' WHERE userid = '$account'" ;
					tep_db_query( $sqlUpdate ) ;
				}

				// Database Update ;
				$sqlUpdate		= "UPDATE tbl_user SET username = '$username', name = '$name', email = '$email' WHERE userid = '$account'" ;
				tep_db_query( $sqlUpdate ) ;

				// Database Search ;
				$sqlSearch		= "SELECT user.userid, user.username, user.name, user.email, user.avatar, user.biography, user.notification, user.privated, user.facebook_id, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples FROM tbl_user user WHERE user.userid = '$account' LIMIT 0, 1" ;
				$searchResult	= tep_db_query( $sqlSearch ) ;
				$searchList		= db_result_array( $searchResult ) ;

				// Successed ;
				echo '{"result":"successed","user":'.json_encode( $searchList[ 0 ] ).'}' ;
			}
		}

		// Database Close ;
		tep_db_close() ;
	}


	/*
	 * @api			saveprofile
	 * @paramter	account
	 * @paramter	name
	 * @paramter	username
	 * @paramter	email
	 * @paramter    biography
	 */
	function saveprofile()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$name 			= tep_get_value_require( "name" ) ;
		$username 		= tep_get_value_require( "username" ) ;
		$email 			= tep_get_value_require( "email" ) ;
		$biography 		= tep_get_value_require( "biography" ) ;

		// Database Connect ;
		tep_db_connect() ;

		// Check Username ;
		$sqlSearch		= "SELECT * FROM tbl_user WHERE userid <> '$account' AND username = '$username'" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		// Result ;
		if( count( $searchList ) )
		{
			echo '{"result":"failed","message":"Invalid Username"}' ;
		}
		else
		{
			// Check Email ;
			$sqlSearch		= "SELECT * FROM tbl_user WHERE userid <> '$account' AND email = '$email'" ;
			$searchResult	= tep_db_query( $sqlSearch ) ;
			$searchList		= db_result_array( $searchResult ) ;

			// Result ;
			if( count( $searchList ) )
			{
				echo '{"result":"failed","message":"Invalid Email"}' ;
			}
			else
			{
				// Database Update ;
				$sqlUpdate		= "UPDATE tbl_user SET username = '$username', name = '$name', email = '$email' WHERE userid = '$account'" ;
				tep_db_query( $sqlUpdate ) ;

				// Database Search ;
				$sqlSearch		= "SELECT user.userid, user.username, user.name, user.email, user.avatar, user.biography, user.notification, user.privated, user.facebook_id, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples FROM tbl_user user WHERE user.userid = '$account' LIMIT 0, 1" ;
				$searchResult	= tep_db_query( $sqlSearch ) ;
				$searchList		= db_result_array( $searchResult ) ;

				// Successed ;
				echo '{"result":"successed","user":'.json_encode( $searchList[ 0 ] ).'}' ;
			}
		}

		// Database Close ;
		tep_db_close() ;
	}

	/*
	 * @api			connectfacebook
	 * @paramter	facebookid
	 * @paramter	friends	 
	 */
	function connectfacebook()
	{
		// Parameters ;
		$account		= tep_get_value_require( "account" ) ;
		$facebookid		= tep_get_value_require( "facebookid" ) ;
		$friends		= $_POST[ "friends" ] ;
		$date 			= tep_now_time() ;

		// Database Connect ;
		tep_db_connect() ;

		// Database Search ;
		$sqlSearch		= "SELECT * FROM tbl_user WHERE userid = '$account'" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;

		if( count( $searchList ) )
		{
			// Database Update ;
			$sqlUpdate		= "UPDATE tbl_user SET facebook_id = '$facebookid' WHERE userid = '$account'" ;
			tep_db_query( $sqlUpdate ) ;
		}

		if( $friends )
		{
			foreach( $friends as $friend )
			{
				$sqlDelete		= "DELETE FROM tbl_friend WHERE userid = '$facebookid' AND joined = '$friend'" ;
				tep_db_query( $sqlDelete ) ;

				// Database Insert ;
				$sqlInsert		= "INSERT INTO `tbl_friend` ( `friendid`, `userid`, `joined`, `created` ) VALUES ( NULL , '$facebookid', '$friend', '$date' )" ;
				tep_db_query( $sqlInsert ) ;
			}
		}

		// Database Search ;
		$sqlSearch			= "SELECT user.userid, user.username, user.name, user.email, user.avatar, user.biography, user.notification, user.privated, user.facebook_id, ( SELECT COUNT( * ) FROM tbl_post post WHERE post.userid = user.userid ) AS posts, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.following = user.userid ) AS followers, ( SELECT COUNT( * ) FROM tbl_follow follow WHERE follow.userid = user.userid ) AS followings, ( SELECT COUNT( * ) FROM tbl_view view WHERE view.viewed = user.userid ) AS peoples FROM tbl_user user WHERE user.userid = '$account' LIMIT 0, 1" ;
		$searchResult	= tep_db_query( $sqlSearch ) ;
		$searchList		= db_result_array( $searchResult ) ;
		
		// Result ;
		echo '{"result":"successed","user":'.json_encode( $searchList[ 0 ] ).'}' ;

		// Database Close ;
		tep_db_close() ;
	}
?>