<?php

/*
 $Id: html_output.php v 1.56 2003/07/09 01:15:48 hpdl Exp $

 Dramastyle eCommerce, Modify from Open Source E-Commerce Solutions
 http://www.dramastyle.com

 Original Solutions Information
 -----------------------------------------------
 Copyright (c) 2003 osCommerce
 Released under the GNU General Public License
 -----------------------------------------------
 */
// The HTML href link wrapper function
function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
	global $request_type, $session_started, $SID;

	if (defined("SITE_SUB_URL")) {
		$page = SITE_SUB_URL.$page;
	}
	
	if (!tep_not_null($page)) {
		die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
	}

	if ($connection == 'NONSSL')
	{
		$link = HTTP_CATALOG_SERVER;
	}
	elseif ($connection == 'SSL')
	{
		if (ENABLE_SSL == true)
		{
			$link = HTTP_CATALOG_SERVER;
		}
		else
		{
			$link = HTTP_CATALOG_SERVER;
		}
	}
	else
	{
		die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
	}

	if (tep_not_null($parameters))
	{
		$link .= $page . '?' . tep_output_string($parameters);
		$separator = '&';
	}
	else
	{
		$link .= $page;
		$separator = '?';
	}

	while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

	// Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
	if ( ($add_session_id == true) && ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') )
	{
		if (tep_not_null($SID))
		{
			$_sid = $SID;
		}
		elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) )
		{
			if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN)
			{
				$_sid = tep_session_name() . '=' . tep_session_id();
			}
		}
	}

	if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true) )
	{
		while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

		$link = str_replace('?', '/', $link);
		$link = str_replace('&', '/', $link);
		$link = str_replace('=', '/', $link);

		$separator = '?';
	}

	if (isset($_sid)) {
		$link .= $separator . tep_output_string($_sid);
	}

	return $link;
}

function tep_show_error($errors, $field = '') {
	$msg = "";
	if (is_array($errors)) {
		if (empty($errors[$field])) {
			return "";
		}
		$msg = $errors[$field];
	} else {
		$msg = $errors;
	}
	
	if ($msg != '') {
		return '<span class="error">'.$msg.'</span>'; 
	}
	
	return "";
}
?>