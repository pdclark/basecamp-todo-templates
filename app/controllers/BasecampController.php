<?php

class BasecampController extends BaseController {

	/**
	 * @var \Basecamp\BasecampClient
	 */
	protected $api;

	function __construct() {
		$this->api = \Basecamp\BasecampClient::factory( array(
			'auth'        => $_ENV['basecamp_auth'],
			'username'    => $_ENV['basecamp_username'],
			'password'    => $_ENV['basecamp_password'],
			'user_id'     => $_ENV['basecamp_company_id'],
			'app_name'    => $_ENV['basecamp_app_name'],
			'app_contact' => $_ENV['basecamp_app_contact'],
		) );
	}

}