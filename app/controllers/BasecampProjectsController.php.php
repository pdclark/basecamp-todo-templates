<?php

class BasecampProjectsController extends BasecampController {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		return Response::json( $this->api->getProjects() );
	}

}
