<?php
/*
 $Id: validations.php v 1.0 2012/07/10 $

-----------------------------------------------
Copyright (c) 2012 ChengTong-Yilin
http://www.chengtong-yilin.com
-----------------------------------------------
*/
function tep_not_null($value, $key = "", $str = "")
{
	global $message_cls;

	if (!isset($value)) {
		return false;
	}

	if (is_array($value)) {
		if (sizeof($value) > 0) {
			return true;
		} else {
			if ($key != '') $message_cls->set_error($key, tep_get_message('ERROR_MUST_BE_NOT', $str));
			return false;
		}
	} else {
		if ((is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
			return true;
		} else {
			if ($key != '') $message_cls->set_error($key, tep_get_message('ERROR_MUST_BE_NOT', $str));
			return false;
		}
	}
}

function tep_check_check($value, $check_value, $key = "", $str = "")
{
	global $message_cls;

	if ($value == $check_value) {
		return true;
	}

	if ($key != '') $message_cls->set_error($key, tep_get_message('ERROR_MUST_BE_CHECK', $str));
	return false;
}

function tep_length_check($value, $min_len = 0, $max_len = 0, $key = '', $str = '')
{
	global $message_cls;

	if (($min_len > 0 && strlen($value) < $min_len) || ($max_len > 0 && strlen($value) > $max_len)) {
		if ($key != '') $message_cls->set_error($key, tep_get_message('ERROR_MUST_BE_LENGTH', $str, $min_len, $max_len));

		return false;
	}

	return true;
}

function tep_email_check($email, $key = "", $str = "")
{
	global $message_cls;
	if ($email == '') {
		return true;
	}
	if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
		if ($key != '') $message_cls->set_error($key, tep_get_message('ERROR_MUST_IS_INCORRECT', $str));
		return false;
	}
	return true;
}

function tep_phone_check($phone, $key = "", $str = "")
{
	global $message_cls;
	if ($phone == '') {
		return true;
	}
	if (!preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $phone)) {
		if ($key != '') $message_cls->set_error($key, tep_get_message('ERROR_MUST_IS_INCORRECT', $str));
		return false;
	}

	return true;
}

function tep_url_check($url, $key = "", $str = "")
{
	global $message_cls;

	if ($url == '') return true;

	if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url)) {
		return true;
	} else {
		if ($key != '') $message_cls->set_error($key, tep_get_message('ERROR_MUST_IS_INCORRECT', $str));
		return false;
	}
}

function tep_userid_check($userid, $key = "", $str = "")
{
	if ($userid == '') return true;
	global $message_cls;

	if (!preg_match("/^[a-z]/i", $userid) || preg_match("/[^a-z0-9\-_]/i", $userid)) {
		if ($key != '') $message_cls->set_error($key, tep_get_message('ERROR_MUST_IS_INCORRECT', $str));
		return false;
	}

	$error_ids = array("admin", "zoom");
	for ($i = 0; $i < count($error_ids); $i++) {
		if (strtolower($userid) == $error_ids[$i]) {
			$message_cls->set_error($key, tep_get_message('ERROR_USER_ID'));
			return false;
		}
	}

	return true;
}

function tep_password_check($password, $key = "", $str = "")
{
	global $message_cls;

	$password_validate = false;
	for ($i = 0; $i < 10; $i++) {
		if (strpos($password, '' . $i) !== false) {
			$password_validate = true;
			break;
		}
	}
	if ($password_validate) {
		$password_validate = false;
		$need_chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',);
		for ($i = 0; $i < count($need_chars); $i++) {
			if (strpos($password, '' . $need_chars[$i]) !== false) {
				$password_validate = true;
				break;
			}
		}
	}

	if ($password_validate === false && $key != '') {
		$message_cls->set_error($key, tep_get_message('ERROR_MUST_IS_INCORRECT', $str));
	}

	return $password_validate;
}

function tep_date_check($date, $split = "-", $format = "y-m-d", $key = "", $str = "")
{
	global $message_cls;

	$date = explode($split, substr($date, 0, 10));
	$format = explode($split, $format);

	if (count($date) < 3) {
		$message_cls->set_error($key, tep_get_message('ERROR_FORAMT_DATE', $str));

		return false;
	}

	$year = 0;
	$month = 0;
	$day = 0;
	for ($i = 0; $i < 3; $i++) {
		if ($format[$i] == 'y') {
			$year = (int)$date[$i];
		} elseif ($format[$i] == 'm') {
			$month = (int)$date[$i];
		} elseif ($format[$i] == 'd') {
			$day = (int)$date[$i];
		}
	}

	if (checkdate($month, $day, $year)) {
		//$message_cls->set_error($key, tep_get_message('ERROR_FORAMT_DATE', $str));
		return true;
	} else {
		$message_cls->set_error($key, tep_get_message('ERROR_FORAMT_DATE', $str));

		return false;
	}
}

function tep_datetime_check($timestr, $split = "-", $format = "y-m-d", $key = "", $str = "")
{
	global $message_cls;

	if (strlen($timestr) < 16) {
		$message_cls->set_error($key, tep_get_message('ERROR_FORAMT_DATETIME', $str));

		return false;
	}

	$time = explode(":", substr($timestr, 11, 6));
	$date = explode($split, substr($timestr, 0, 10));
	$format = explode($split, $format);

	if (count($date) < 3) {
		$message_cls->set_error($key, tep_get_message('ERROR_FORAMT_DATETIME', $str));

		return false;
	}

	if (count($time) < 2) {
		$message_cls->set_error($key, tep_get_message('ERROR_FORAMT_DATETIME', $str));

		return false;
	}

	$year = 0;
	$month = 0;
	$day = 0;
	for ($i = 0; $i < 3; $i++) {
		if ($format[$i] == 'y') {
			$year = (int)$date[$i];
		} elseif ($format[$i] == 'm') {
			$month = (int)$date[$i];
		} elseif ($format[$i] == 'd') {
			$day = (int)$date[$i];
		}
	}

	if (checkdate($month, $day, $year)) {
		if ($time[0] * 1 < 0 || $time[0] * 1 >= 24) {
			$message_cls->set_error($key, tep_get_message('ERROR_FORAMT_DATETIME', $str));

			return false;
		}

		if ($time[1] * 1 < 0 || $time[1] * 1 > 60) {
			$message_cls->set_error($key, tep_get_message('ERROR_FORAMT_DATETIME', $str));

			return false;
		}

		return true;
	} else {
		$message_cls->set_error($key, tep_get_message('ERROR_FORAMT_DATETIME', $str));

		return false;
	}
}

function tep_equals_check($value, $like, $key = "", $str = "")
{
	global $message_cls;

	$like_value = tep_get_value_require($like);
	if ($value != $like_value) {
		if ($key != '') {
			$message_cls->set_error($key, tep_get_message('ERROR_NOT_EQUALS', $str));
		}

		return false;
	}

	return true;
}

function tep_integer_check($value, $min = "", $max = "", $key = "", $str = "")
{
	global $message_cls;

	if (is_numeric($value)) {
		$value = $value * 1;
		if (is_int($value)) {
			if ($min != "" && $value * 1 < $min) {
				$message_cls->set_error($key, tep_get_message('ERROR_MINIMUM', $str, $min));

				return false;
			}

			if ($max != "" && $value * 1 > $max) {
				$message_cls->set_error($key, tep_get_message('ERROR_MAXIMUM', $str, $max));

				return false;
			}

			return true;
		} else {
			$message_cls->set_error($key, tep_get_message('ERROR_FORAMT_INTEGER', $str));

			return false;
		}
	} else {
		$message_cls->set_error($key, tep_get_message('ERROR_FORAMT_INTEGER', $str));

		return false;
	}
}

function tep_unique_check($table, $compare, $question = "", $key = "", $str = "")
{
	global $message_cls;

	$sql = "select count(*) as data_count from " . $table . " where 1=1";
	foreach ($compare as $field => $value) {
		$sql .= (" and `" . $field . "`='" . $value . "'");
	}
	if ($question != "") {
		$sql .= (" and " . $question);
	}
	$data_count = teb_query($sql, "data_count");

	if ($data_count * 1 == 0) {
		return true;
	} else {
		if ($key != '') {
			$message_cls->set_error($key, tep_get_message('ERROR_ALREADY_EXIST', $str));
		}

		return false;
	}
}

function tep_count_check($table, $compare, $question = "", $key = "", $str = "")
{
	global $message_cls;

	$sql = "select count(*) as data_count from " . $table . " where 1=1";
	foreach ($compare as $field => $value) {
		$sql .= (" and `" . $field . "`='" . $value . "'");
	}
	if ($question != "") {
		$sql .= (" and " . $question);
	}
	$result = teb_query($sql);
	if ($result['data_count'] > 0) {
		return true;
	} else {
		if ($key != '') {
			$message_cls->set_error($key, tep_get_message('ERROR_NOT_EXIST', $str));
		}

		return false;
	}
}

function tep_empty_file($key)
{
	if (isset($_FILES[$key]) && $_FILES[$key]['size'] > '') {
		return false;
	} else {
		return true;
	}
}

function tep_zipcode_check($coutry_code, $state_code, $cityname, $zipcode, $key = "", $str = "")
{
	global $message_cls;

	$zipcount = teb_query("select count(*) as data_count from " . TABLE_CITIES_EXTENDED . " where zip='" . $zipcode . "' and city='" . $cityname . "' and state_code='" . $state_code . "' and country_code='" . $coutry_code . "'");

	if ($zipcount['data_count'] > 0) {
		return true;
	} else {
		if ($key != '') {
			$message_cls->set_error($key, tep_get_message('ERROR_FORAMT_ZIPCODE', $str));
		}

		return false;
	}
}

function tep_validations($key, $value, $valudataions, $name)
{
	if ($valudataions == '') {
		return true;
	}

	$valudataions = explode(';', $valudataions);
	for ($i = 0; $i < count($valudataions); $i++) {
		$validation = $valudataions[$i];
		if ($validation == '') continue;

		$parameter = '';
		$pos = strpos($validation, '[');
		if ($pos !== false) {
			$parameter = substr($validation, $pos + 1);
			$parameter = substr($parameter, 0, strlen($parameter) - 1);

			$parameter = explode(',', $parameter);

			$validation = substr($validation, 0, $pos);
		}

		switch ($validation) {
			case 'require':
				tep_not_null($value, $key, $name);
				break;
			case 'check':
				tep_check_check($value, $parameter[0], $key, $name);
				break;
			case 'userid':
				tep_userid_check($value, $key, $name);
				break;
			case 'length':
				tep_length_check($value, $parameter[0], $parameter[1], $key, $name);
				break;
			case 'email':
				tep_email_check($value, $key, $name);
				break;
			case 'url':
				tep_url_check($value, $key, $name);
				break;
			case 'password':
				tep_password_check($value, $key, $name);
				break;
			case 'equals':
				tep_equals_check($value, $parameter[0], $key, $name);
				break;
			case 'date':
				tep_date_check($value, $parameter[0], $parameter[1], $key, $name);
				break;
			case 'datetime':
				tep_datetime_check($value, $parameter[0], $parameter[1], $key, $name);
				break;
			case 'integer':
				if ($parameter == "") {
					tep_integer_check($value, "", "", $key, $name);
				} else {
					tep_integer_check($value, $parameter[0], $parameter[1], $key, $name);
				}
				break;
			default:
				die('undefined validation function : ' . $validation);
				break;
		}
	}
}