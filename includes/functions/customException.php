<?php
define('API_ERROR_CUSTOM', 0);
define('API_ERROR_DATABASE', 1);
define('API_ERROR_EMPTY_USER', 2);

class CustomException extends Exception {
	private $_msg_code = 0;
	private $_msg_body = "";
	private $_msg_array = array();
	
	public function __construct($msg_code, $msg_body = '') {
		$this->_msg_code = $msg_code;
		$this->_msg_body = $msg_body;
		
		$this->_msg_array[1] = "Server error!";
		$this->_msg_array[2] = "You are not memebr of PartyHub.";
	}
	
	public function get_message() {
		if (isset($this->_msg_array[$this->_msg_code]) || $this->_msg_array[$this->_msg_code] != '') {
			return $this->_msg_array[$this->_msg_code];
		} else {
			return $this->_msg_body;
		}
	}
}