<?php
function tep_validate_password($plain, $encrypted)
{
	if (tep_not_null($plain) && tep_not_null($encrypted)) {
		// split apart the hash / salt
		$stack = explode(':', $encrypted);

		if (sizeof($stack) != 2) return false;

		if (hash_hmac("sha256", utf8_encode($plain), utf8_encode($stack[1]), false) == $stack[0]) {
			return true;
		}
	}

	return false;
}

function tep_encrypt_password($plain)
{
	$password = '';

	for ($i = 0; $i < 10; $i++) {
		$password .= tep_rand();
	}

	$salt = substr(md5($password), 0, 4);

	$password = hash_hmac("sha256", utf8_encode($plain), utf8_encode($salt), false); //md5($salt . $plain) . ':' . $salt;

	return $password . ":" . $salt;
}

function get_user_status_color($status)
{
	switch ($status) {
		case 'checked_in':
			return 'green';
		case 'looking':
			return 'yellow';
		case 'busy':
			return 'red';
		case 'designated_driver':
			return 'orange';
	}

	return "white";
}

function get_address($address, $key = "")
{
	$result = "";
	$result .= $address[$key . 'address_street_1'] . "";
	if ($address[$key . 'address_street_2'] != '') {
		$result .= " " . $address[$key . 'address_street_2'];
	}
	$result .= ", ";
	$result .= $address[$key . 'address_city'] . ", ";
	$result .= $address[$key . 'address_state'] . " ";
	$result .= $address[$key . 'address_zip'] . ", ";
	$result .= $address[$key . 'address_country'];

	return $result;
}

function upload_file($file_name, $require = true)
{
	global $message_cls, $upload_img_path;
	if (!isset($_FILES[$file_name]) || $_FILES[$file_name]['tmp_name'] == '') {
		if ($require) {
			$message_cls->set_error($file_name, "Empty file");
		}

		return "";
	}

	$file = $_FILES[$file_name];

	$year = date('Y');
	$month = date('m');
	$day = date('d');

	$upload_dir = $year . "/" . $month . "/" . $day . "/";

	chmod(DIR_WS_UPLOAD, 0755);
	if (!is_dir(DIR_WS_UPLOAD . $upload_dir)) {
		if (mkdir(DIR_WS_UPLOAD . $upload_dir, 0755, true)) {
			chmod(DIR_WS_UPLOAD . $upload_dir, 0755);
		}
	}
	$new_image_file = $upload_dir . date('YmdHis') . "_" . urlencode($file['name']);

	if (move_uploaded_file($file["tmp_name"], DIR_WS_UPLOAD . $new_image_file)) {
		$upload_img_path = DIR_WS_UPLOAD . $new_image_file;

		return HTTP_WS_UPLOAD . $new_image_file;
	} else {
		$message_cls->set_error($file_name, "Error upload file.");
	}

	return "";
}

function formatted_mobile_image($original_img_url, $original_img_path)
{
	$formatted_img = image_resize($original_img_path, MOBILE_IMAGE_WIDTH, MOBILE_IMAGE_HEIGHT, true);

	$url_info = pathinfo($original_img_url);
	$img_info = pathinfo($formatted_img);

	return $url_info['dirname'] . "/" . $img_info['basename'];
}

function thumb_mobile_image($original_img_url, $original_img_path)
{
	$formatted_img = image_resize($original_img_path, AVATAR_IMAGE_WIDTH, AVATAR_IMAGE_HEIGHT, true);

	$url_info = pathinfo($original_img_url);
	$img_info = pathinfo($formatted_img);

	return $url_info['dirname'] . "/" . $img_info['basename'];
}

function upload_avatar($user_id, $user_name, $file_name)
{
	global $message_cls;

	if (!isset($_FILES[$file_name]) || $_FILES[$file_name]['tmp_name'] == '') {
		return "";
	}

	$file = $_FILES[$file_name];

	$avatar_dir = "avatar/";
	$avatar_dir .= ($user_id - ($user_id % 10000)) . "/";
	chmod(DIR_WS_UPLOAD, 0755);
	if (!is_dir(DIR_WS_UPLOAD . $avatar_dir)) {
		if (mkdir(DIR_WS_UPLOAD . $avatar_dir, 0755, true)) {
			chmod(DIR_WS_UPLOAD . $avatar_dir, 0755);
		}
	}

	$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
	$new_image_file = $user_name . "." . $ext;
	@unlink(DIR_WS_UPLOAD . $avatar_dir . $new_image_file);

	if (move_uploaded_file($file["tmp_name"], DIR_WS_UPLOAD . $avatar_dir . $new_image_file)) {
		$avartar_image = image_resize(DIR_WS_UPLOAD . $avatar_dir . $new_image_file, AVATAR_IMAGE_WIDTH, AVATAR_IMAGE_HEIGHT, true);
		@unlink(DIR_WS_UPLOAD . $avatar_dir . $new_image_file);
		@rename($avartar_image, DIR_WS_UPLOAD . $avatar_dir . $new_image_file);

		return HTTP_WS_UPLOAD . $avatar_dir . $new_image_file;
	}

	return "";
}

function export_table_csv($table_name, $export_file_name)
{
	export_query_csv("select * from " . $table_name);
}

function export_query_csv($query, $export_file_name)
{
	$result = tep_db_query($query);

	if (!$result) {
		echo '<script lanuage="javascript">alert("No export data.")</script>';
	}

	$filed_count = mysql_num_fields($result);
	$headers = array();
	for ($i = 0; $i < $filed_count; $i++) {
		$headers[] = mysql_field_name($result, $i);
	}

	$fp = fopen("php://output", "w");
	if ($fp) {
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=" . $export_file_name);
		header("Pragma: no-cache");
		fputcsv($fp, $headers);
		while ($row = tep_db_fetch_array($result)) {
			fputcsv($fp, array_values($row));
		}
	}
}

function get_longitude_length($lat, $long, $lat_length = 111000)
{
	return abs($lat_length * cos($lat));
}

function get_mile_from_meter($meter)
{
	return round(($meter / 1000 * 0.625), 2);
}

/*
 * user profile
 */

function get_user_name($user_id)
{
	$user = teb_one_query(TABLE_USERS, array("user_id" => $user_id));

	return $user["user_name"];
}

function get_user_fullname($user_id)
{
	$user = teb_one_query(TABLE_USERS, array("user_id" => $user_id));

	return $user["user_firstname"] . " " . $user["user_lastname"];
}

function is_block_user_in_venue($venue_id, $user_id)
{
	return teb_query("select count(*) as data_count from " . TABLE_VENUES_BLOCKED_USERS . " where user_id='" . $user_id . "' and venue_id='" . $venue_id . "'", "data_count");
}

function block_user_in_venue($venue_id, $user_id)
{
	$blocked = teb_one_query(TABLE_VENUES_BLOCKED_USERS, array("user_id" => $user_id, "venue_id" => $venue_id));
	if ($blocked == '') {
		tep_db_perform(TABLE_VENUES_BLOCKED_USERS, array("user_id" => $user_id, "venue_id" => $venue_id), "insert");
	}
}

function get_show_user_name($user_profile)
{
	if ($user_profile['user_name_display_type'] == 'fullname') {
		return $user_profile['user_firstname'] . " " . $user_profile['user_lastname'];
	}

	if ($user_profile['user_name_display_type'] == 'shortname') {
		return $user_profile['user_firstname'] . " " . substr($user_profile['user_lastname'], 0, 1);
	}

	// snipstamp
	return $user_profile['user_name'];
}

/*
 * user actions
 */
function get_action_review($action)
{
	$str = "";

	if ($action['action_type'] == 'signup') {
		$str = "Registred to our ";
	} elseif ($action['action_type'] == 'venue') {
		$str = "Followed venue ";
	} elseif ($action['action_type'] == 'qrcode') {
		$str = "Skined qrcode ";
	} elseif ($action['action_type'] == 'checkin') {
		$str = "Checked-in ";
	} elseif ($action['action_type'] == 'review') {
		$str = "Writed review ";
	} elseif ($action['action_type'] == 'comment') {
		$str = "Writed comment ";
	}

	$str .= "@" . tep_get_after_date_time($action['action_time']);

	return $str;
}

function add_point($user_id, $point_mark)
{
	$point = array("user_id" => $user_id, "point_week" => date('W'), "point_year" => date('Y'));

	$old_point = teb_one_query(TABLE_POINTS, $point);
	if ($old_point == "") {
		$point['point_mark'] = $point_mark;

		return tep_db_perform(TABLE_POINTS, $point, "insert");
	} else {
		return tep_db_perform(TABLE_POINTS, array("point_mark" => $old_point['point_mark'] + $point_mark), "update", "user_id='" . $user_id . "' and point_week='" . date('W') . "' and point_year='" . date('Y') . "'");
	}
}

function checked_in_venue($venue_id, $user_id, $point_mark, $latitude, $longitude)
{
	$now_time = tep_now_datetime();

	// add location
	tep_db_perform(TABLE_LOCATIONS, array("user_id" => $user_id, "location_latitude" => $latitude, "location_longitude" => $longitude, "location_timestamp" => $now_time), "insert");
	$new_location_id = tep_db_insert_id();

	// finished last check-in
	$last_check_in = teb_query("select * from " . TABLE_CHECK_INS . " where user_id='" . $user_id . "' order by status_changed_from desc limit 1");
	if ($last_check_in['checked_time'] == 0) {
		$checked_time = $now_time - $last_check_in['status_changed_from'];

		tep_db_perform(TABLE_CHECK_INS, array("check_status" => "checked_out", "checked_time" => $checked_time, "status_changed_to" => $now_time), "update", "location_id='" . $last_check_in['location_id'] . "'");
	}

	// add check-in
	tep_db_perform(TABLE_CHECK_INS, array("venue_id" => $venue_id, "user_id" => $user_id, "location_id" => $new_location_id, "check_status" => "checked_in", "status_changed_from" => $now_time), "insert");
	$new_check_in_id = tep_db_insert_id();

	// add actions
	$state = tep_db_perform(TABLE_USER_ACTIONS, array("venue_id" => $venue_id, "user_id" => $user_id, "action_type" => "checkin", "releation_id" => $new_check_in_id, "point" => $point_mark, "action_time" => $now_time), "insert");

	// update user location info
	$user_info = array(
		"user_latest_location_latitude" => $latitude,
		"user_latest_location_longitude" => $longitude,
		"user_latest_location_timestamp" => $now_time,
		"user_past_location_id" => $new_location_id,
		"user_status" => "checked_in",
		"venue_id" => $venue_id);

	$state = tep_db_perform(TABLE_USERS, $user_info, "update", "user_id='" . $user_id . "'");

	return $state;
}