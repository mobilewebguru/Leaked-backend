<?php
	// --- APIs --- ;

	// Includes ;
	require( 'includes/application.php' ) ;

	// Action ;	
	$action = tep_get_value_require( "action" ) ;

	if( $action == "" )
	{

	}
	else if( $action == 'signinfacebook' )
	{
		require( 'mobileservice/users.php' ) ;
		signinfacebook() ;
	}
	else if( $action == 'login' )
	{
		require( 'mobileservice/users.php' ) ;
		login() ;
	}
	else if( $action == 'registerwithemail' )
	{
		require( 'mobileservice/users.php' ) ;
		registerwithemail() ;
	}
	else if( $action == 'forgotpassword' )
	{
		require( 'mobileservice/users.php' ) ;
		forgotpassword() ;
	}
    else if( $action == 'changeprofilepicture' )
	{
		require( 'mobileservice/users.php' ) ;
		changeprofilepicture() ;
	}    
	else if( $action == 'checkusername' )
	{
		require( 'mobileservice/users.php' ) ;
		checkusername() ;
	}
    else if( $action == 'savebiography' )
	{
		require( 'mobileservice/users.php' ) ;
		savebiography() ;
	}    
	else if( $action == 'editprofile' )
	{
		require( 'mobileservice/users.php' ) ;
		editprofile() ;
	}
    else if( $action == 'saveprofile' )
	{
		require( 'mobileservice/users.php' ) ;
		saveprofile() ;
	}
    else if( $action == 'connectfacebook' )
    {
		require( 'mobileservice/users.php' ) ;
		connectfacebook() ;
	}
	else if( $action == 'getfeedposts' )
	{
		require( 'mobileservice/posts.php' ) ;
		getfeedposts() ;
	}
	else if( $action == 'getactivityposts' )
	{
		require( 'mobileservice/posts.php' ) ;
		getactivityposts() ;
	}
	else if( $action == 'getsaveposts' )
	{
		require( 'mobileservice/posts.php' ) ;
		getsaveposts() ;
	}
    else if( $action == 'getaccountmarks' )
	{
		require( 'mobileservice/posts.php' ) ;
		getaccountmarks() ;
	}
    else if( $action == 'getmarkedposts' )
    {
		require( 'mobileservice/posts.php' ) ;
		getmarkedposts() ;
	}
    else if( $action == 'getaccountreposts' )
    {
    	require( 'mobileservice/posts.php' ) ;
		getaccountreposts() ;
	}
    else if( $action == 'getaccountreplies' )
    {
        require( 'mobileservice/posts.php' ) ;
		getaccountreplies() ;
	}     
	else if( $action == 'poststatus' )
	{
		require( 'mobileservice/posts.php' ) ;
		poststatus() ;
	}
    else if( $action == 'repoststatus' )
	{
		require( 'mobileservice/posts.php' ) ;
		repoststatus() ;
	}    
    else if( $action == 'deletestatus' )
	{
		require( 'mobileservice/posts.php' ) ;
		deletestatus() ;
	}
    else if( $action == 'markstatus' )
	{
		require( 'mobileservice/posts.php' ) ;
		markstatus() ;
	}
    else if( $action == 'replystatus' )
    {
		require( 'mobileservice/posts.php' ) ;
		replystatus() ;
	}
    else if( $action == 'reportstatus' )
    {
    	require( 'mobileservice/posts.php' ) ;
		reportstatus() ;
	}    
    else if( $action == 'viewuser' )
    {
		require( 'mobileservice/follows.php' ) ;
		viewuser() ;
	}    
    else if( $action == 'getfollowers' )
	{
		require( 'mobileservice/follows.php' ) ;
		getfollowers() ;
	}
    else if( $action == 'getfollowings' )
	{
		require( 'mobileservice/follows.php' ) ;
		getfollowings() ;
	}    
	else if( $action == 'followuser' )
	{
		require( 'mobileservice/follows.php' ) ;
		followuser() ;
	}
	else if( $action == 'unfollowuser' )
	{
		require( 'mobileservice/follows.php' ) ;
		unfollowuser() ;
	}
    else if( $action == 'blockuser' )
	{
		require( 'mobileservice/follows.php' ) ;
		blockuser() ;
	}
	else if( $action == 'unblockuser' )
	{
		require( 'mobileservice/follows.php' ) ;
		unblockuser() ;
	}
	else if( $action == 'searchusers' )
	{
		require( 'mobileservice/follows.php' ) ;
		searchusers() ;
	}	
	else if( $action == 'getwarblfriends' )
	{
		require( 'mobileservice/follows.php' ) ;
		getwarblfriends() ;
	}
	else if( $action == 'getfacebookfriends' )
	{
		require( 'mobileservice/follows.php' ) ;
		getfacebookfriends() ;
	}
?>